<?php

namespace Doc;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use SplFileInfo;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\Renderer\RendererInterface as Renderer;
use Zend\Mvc\MvcEvent;
use Zend\View\ViewEvent;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;
use Core\Html2Doc\DocInterface;
//use Core\View\Helper\InsertFile;
use Core\View\Helper\InsertFile\FileEvent;
use Core\Entity\FileEntity;
use Core\ModuleManager\ModuleConfigLoader;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
/**
 * Make HTML to DOC
 * 
 */
class Module implements DocInterface, ResolverInterface, ServiceManagerAwareInterface
{
    CONST RENDER_FULL = 0;
    CONST RENDER_WITHOUT_DOC = 1;
    CONST RENDER_WITHOUT_ATTACHMENTS = 2;
    
    protected $serviceManager;
    
    protected $viewResolverAttached = False;
    
    protected $appendDOC = array();
    protected $appendImage = array();
    
    
     /**
     * Loads module specific configuration.
     * 
     * @return array
     */
    public function getConfig()
    {
        return ModuleConfigLoader::load(__DIR__ . '/config');
    }
    
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }
    
    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->getSharedManager()->attach('Applications', 'application.detail.actionbuttons', function ($event) {
            return 'doc/application/details/button';
        });
    }
    
    /**
     * hook into the rendering for transformation of HTML to DOC
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events) {
        $events->attach(ViewEvent::EVENT_RENDERER_POST, array($this, 'cleanLayout'), 1);
        $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'attachDOCtransformer'), 10);
    }
    
    /**
     * hook into the MVC
     * in here you could still decide, if you want to hook into the Rendering
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function attachMvc(EventManagerInterface $events) {
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'initializeViewHelper'), 100);
    }
    
    /**
     * hook into the Rendering of files
     * the manager to hook in is the viewhelper 'insertfiles'
     * 
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function initializeViewHelper(MvcEvent $e) {
        $viewhelperManager = $this->serviceManager->get('ViewhelperManager');
        if ($viewhelperManager->has('insertFile')) {
            $insertFile = $viewhelperManager->get('insertFile');
            $insertFile->attach(FileEvent::GETFILE,  array($this, 'getFile'));
            $insertFile->attach(FileEvent::RENDERFILE,  array($this, 'renderFile'));
            $insertFile->attach(FileEvent::INSERTFILE,  array($this, 'collectFiles'));
        }
    }
    
    /**
     * proxy, in case that you just got a name and have to find the associated file-entity
     * maybe this is redundant and can be deprecated
     * 
     * @param \Core\View\Helper\InsertFile\FileEvent $e
     * @return null
     */
    public function getFile(FileEvent $e) {
        $lastFileName = $e->getLastFileName();
        if (is_string($lastFileName)) {
            $repository = $this->serviceManager->get('repositories')->get('Applications/Attachment');
            $file       = $repository->find($lastFileName);
            if (isset($file)) {
                $e->setFileObject($lastFileName, $file);
                $e->stopPropagation();
                return $file;
            }
            return Null;
        }
        // if it is not a string i do presume it is already a file-Object
        return $lastFileName;
    }
    
    /**
     * here the inserted File is rendered,
     * there is a lot which still can be done like outsorcing the HTML to a template,
     * or distinguish between different File Types,
     * at the moment we assume the $file is always an (sub-)instance of \Core\File\Entity
     * 
     * @param \Core\View\Helper\InsertFile\FileEvent $e
     * @return string
     */
    public function renderFile(FileEvent $e) {
        $file = $e->getLastFileObject();
        // assume it is of the class Core\Entity\FileEntity
        $return = '<div class="col-xs-3"><a href="#attachment_' . $file->getId() . '">' . $file->getName() . '</a></div>' . PHP_EOL
                . '<div class="col-xs-3">' . $file->getType() . '</div>'
                . '<div class="col-xs-3">' . $file->prettySize . '</div>';
        /*
         * this snippet was for direct inserting an image into the DOC
        if ($file && $file instanceOf FileEntity && 0 === strpos($file->getType(), 'image')) {
            //$content = $file->getContent();
            //$url = 'data:image/' . $file->getType() . ';base64,' . base64_encode ($content);
            //$html = '<img src="' . $url . '" >';
            $html = '<a href="#1">' . $file->getName() . '</a>';
            $e->stopPropagation();
            return $html;
        }
         */
        return $return;
    }
    
    /**
     * give a summary of all inserted Files,
     * this is for having access to those files in the post-process
     * @param \Zend\View\ViewEvent $e
     */
    public function collectFiles(FileEvent $e) {
        $this->appendDOC = array();
        $files = $e->getAllFiles();
        foreach ($files as $name => $file) {
            if (!empty($file) && $file instanceOf FileEntity) {
                if (0 === strpos($file->getType(), 'image')) {
                    $this->appendImage[] = $file;
                }
                if (strtolower($file->getType()) == 'application/doc') {
                    $this->appendDOC[] = $file;
                }
            }
        }
        return Null;
    }
    
    /**
     * remove unwanted or layout related data
     * 
     * basically you rake through the viewmodel for the data you want to use for your template,
     * this may not be optimal because you have to rely on the correct naming of the viewmodels
     * 
     * if you get the data you want, you switch to the specific template by adding the conforming resolver
     * 
     * @param \Zend\View\ViewEvent $e
     */
    public function cleanLayout(ViewEvent $e) {
        $result   = $e->getResult();
        $response = $e->getResponse();
        $model = $e->getModel();
        if ($model->hasChildren()) {
            $children = $model->getChildren();
            $content = Null;
            foreach ($children as $child) {
                if ($child->captureTo() == 'content') {
                    $content = $child;
                    $this->attachViewResolver();
                }
            }
            if (!empty($content)) {
                $e->setModel($content);
            }
        }
        else {
            // attach the own resolver here too ?
            // ...
        }
    }
    
    /**
     * Attach an own ViewResolver
     */
    public function attachViewResolver() {
        if (!$this->viewResolverAttached) {
            $this->viewResolverAttached = True;
            $resolver = $this->serviceManager->get('ViewResolver');
            $resolver->attach($this,100);
        }
    }

    /**
     * Transform the HTML to DOC,
     * this is a post-rendering-process
     * 
     * put in here everything related to the transforming-process like options
     * 
     * @param \Zend\View\ViewEvent $e
     */
    public function attachDOCtransformer(ViewEvent $e) {
        $result   = $e->getResult();
        $response = $e->getResponse();
        error_reporting(0);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        
        foreach (array(self::RENDER_FULL, self::RENDER_WITHOUT_DOC, self::RENDER_WITHOUT_ATTACHMENTS ) as $render ) {
            try {
                
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $result);
                $e->setResult($result);
                
                header("Content-type: application/vnd.ms-word");
                header("Content-Disposition: attachment;Filename=CV".date("dmYGi").".doc");
                break;
            } catch (\Exception $e) {
            }
        }
        error_reporting(E_ALL);
    }
    /**
     * Look for a template with the Suffix ".doc.phtml"
     * 
     * @param string $name
     * @param \Zend\View\Renderer\RendererInterface $renderer
     * @return string|boolean
     */
    public function resolve($name, Renderer $renderer = null) {
        if ($this->serviceManager->has('ViewTemplatePathStack')) {
            // get all the Pases made up for the zend-provided resolver
            // we won't get any closer to ALL than that
            $viewTemplatePathStack = $this->serviceManager->get('ViewTemplatePathStack');
            $paths = $viewTemplatePathStack->getPaths();
            $defaultSuffix = $viewTemplatePathStack->getDefaultSuffix();
            if (pathinfo($name, PATHINFO_EXTENSION) != $defaultSuffix) {;
                $name .= '.doc.' . $defaultSuffix;
            }
            else {
                // TODO: replace Filename by Filename for DOC
            }

            foreach ($paths as $path) {
                $file = new SplFileInfo($path . $name);
                if ($file->isReadable()) {
                    // Found! Return it.
                    if (($filePath = $file->getRealPath()) === false && substr($path, 0, 7) === 'phar://') {
                        // Do not try to expand phar paths (realpath + phars == fail)
                        $filePath = $path . $name;
                        if (!file_exists($filePath)) {
                            break;
                        }
                    }
                    //if ($this->useStreamWrapper()) {
                    //    // If using a stream wrapper, prepend the spec to the path
                    //    $filePath = 'zend.view://' . $filePath;
                    //}
                    return $filePath;
                }
            }
        }
        // TODO: Resolving to an DOC has failed, this could have implications for the transformer
        return false;
    }
    
}