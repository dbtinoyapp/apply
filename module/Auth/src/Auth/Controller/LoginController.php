<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Stdlib\Parameters;

/**
 * Main Action Controller for Authentication module.
 */
class LoginController extends AbstractActionController {
public function indexAction() {
        $this->layout('layout/layout-auth');
        $viewModel = new ViewModel();
        $services = $this->getServiceLocator();
        $loginForm = $services->get('FormElementManager')
                ->get('Auth/Login');

        if ($this->request->isPost() && $data = $this->params()->fromPost()) {

            $loginForm->setData($this->params()->fromPost());
            $adapter = $services->get('Auth/Adapter/UserLogin');

            // inject suffixes via shared Events
            $loginSuffix = '';
            $e = $this->getEvent();
            //$this->getEventManager()->addIdentifiers('login');
            $loginSuffixResponseCollection = $this->getEventManager()->trigger('login.getSuffix', $e);
            if (!$loginSuffixResponseCollection->isEmpty()) {
                $loginSuffix = $loginSuffixResponseCollection->last();
            }

            $data = $this->params()->fromPost();
            $adapter->setIdentity($data['credentials']['login'] . $loginSuffix)
                    ->setCredential($data['credentials']['credential']);

            $auth = $services->get('AuthenticationService');
            $result = $auth->authenticate($adapter);


            if ($result->isValid()) {
                $user = $auth->getUser();
                $settings = $user->getSettings('Core');
                $language = $settings->localization->language;
                if (!$language) {
                    $headers = $this->getRequest()->getHeaders();
                    if ($headers->has('Accept-Language')) {
                        $locales = $headers->get('Accept-Language')->getPrioritized();
                        $language = $locales[0]->type;
                    } else {
                        $language = 'en';
                    }
                }
                $services->get('Log/Core/Ats')->info('User ' . $user->login . ' logged in');

                $ref = $this->params()->fromQuery('ref', false);

                if ($ref) {
                    $ref = urldecode($ref);
                    $url = preg_replace('~^/[a-z]{2}(/)?~', '/' . $language . '$1', $ref);
                    $url = $this->getRequest()->getBasePath() . $url;
                } else {
                    $urlHelper = $services->get('ViewHelperManager')->get('url');
                    $url = $urlHelper('lang', array('lang' => $language));
                }
                $this->notification()->success(/* @translate */ 'You are now logged in.');
                return $this->redirect()->toUrl($url);
            } else {
                $services->get('Log/Core/Ats')->info('Failed to authenticate User ' . $data['credentials']['login']);

                $this->notification()->danger(/* @translate */ 'Authentication failed.');
            }
        }

        $ref = $this->params()->fromQuery('ref', false);

        if ($ref) {
            $req = $this->params()->fromQuery('req', false);
            if ($req) {
                $this->getResponse()->setStatusCode(403);
                $viewModel->setVariable('required', true);
            }
            $viewModel->setVariable('ref', $ref);
        }

        $viewModel->setVariable('loginForm', $loginForm);
        
        return $viewModel;
    }

    /**
     * Login with HybridAuth
     *
     * Passed in Params:
     * - provider: HybridAuth provider identifier.
     *
     * Redirects To: Route 'home'
     */
    public function loginAction() {

        $ref = urldecode($this->getRequest()->getBasePath() . $this->params()->fromQuery('ref'));
        $provider = $this->params('provider', '--keiner--');
        $hauth = $this->getServiceLocator()->get('HybridAuthAdapter');
        $hauth->setProvider($provider);
        $auth = $this->getServiceLocator()->get('AuthenticationService');

        $result = $auth->authenticate($hauth);
        $resultMessage = $result->getMessages();
        if (array_key_exists('firstLogin', $resultMessage) && $resultMessage['firstLogin'] === True) {
            $this->getServiceLocator()->get('Log/Core/Ats')->debug('first login via ' . $provider);

            if (array_key_exists('user', $resultMessage)) {
                $user = $auth->getUser();

                $password = substr(md5(uniqid()), 0, 6);
                $login = $user->info->getEmail();
                $scheme = '';
                $domain = '';
                $uri = $this->getRequest()->getUri();
                if (isset($uri)) {
                    $scheme = $uri->getScheme();
                    $domain = $uri->getHost();
                }
                $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
                $basePath = $viewHelperManager->get('basePath')->__invoke();

                $user->login = $login;
                $user->setPassword($password);
                $user->role = 'user';

                $mail = $this->templatedMail(
                        array('displayName' => $user->info->getDisplayName(),
                            'provider' => $provider,
                            'user' => $login,
                            'password' => $password,
                            'uri' => $scheme . '://' . $domain . $basePath)
                );
                $mail->template('first-login');
                $mail->addTo($user->info->getEmail());
                $mail->setSubject(/* @translate */ 'PeoplePlus Tech Recruitment registration.');
            }
            if (isset($mail) && $mail->send()) {
                $this->getServiceLocator()->get('Log/Core/Ats')->info('Mail first-login sent to ' . $user->info->getEmail());
            } else {
                $this->getServiceLocator()->get('Log/Core/Ats')->warn('No Mail was sent');
            }
        }

        $user = $auth->getUser();
        $this->getServiceLocator()->get('Log/Core/Ats')->info('User ' . $auth->getUser()->getInfo()->getDisplayName() . ' logged in via ' . $provider);
        $settings = $user->getSettings('Core');
        if (null !== $settings->localization->language) {
            $basePath = $this->getRequest()->getBasePath();
            $ref = preg_replace('~^' . $basePath . '/[a-z]{2}(/)?~', $basePath . '/' . $settings->localization->language . '$1', $ref);
        }
        return $this->redirect()->toUrl($ref); //Route('lang/home', array('lang' => $this->params('lang')));
    }

    /**
     * Login via an external Application. This will get obsolet as soon we'll have a full featured Rest API.
     *
     * Passed in params:
     * - appKey: Application identifier key
     * - user: Name of the user to log in
     * - pass: Password of the user to log in
     *
     * Returns an json response with the session-id.
     * Non existant users will be created!
     *
     */
    public function loginExternAction() {

        $services = $this->getServiceLocator();
        $adapter = $services->get('ExternalApplicationAdapter');
        $config = $services->get('config');

        $adapter->setIdentity($this->params()->fromPost('user'))
                ->setCredential($this->params()->fromPost('pass'))
                ->setApplicationKey($this->params()->fromPost('appKey'));

        $auth = $services->get('AuthenticationService');
        $result = $auth->authenticate($adapter);

        if ($result->isValid()) {
            $services->get('Log/Core/Ats')->info('User ' . $this->params()->fromPost('user') .
                    ' logged via ' . $this->params()->fromPost('appKey'));

            // the external login may include some parameters for an update
            $updateParams = $this->params()->fromPost();
            unset($updateParams['user'], $updateParams['pass'], $updateParams['appKey']);
            $resultMessage = $result->getMessages();
            $password = Null;
            if (array_key_exists('firstLogin', $resultMessage) && $resultMessage['firstLogin'] === True) {
                $password = substr(md5(uniqid()), 0, 6);
                $updateParams['password'] = $password;
            }
            if (!empty($updateParams)) {
                $user = $auth->getUser();
                try {
                    foreach ($updateParams as $updateKey => $updateValue) {
                        if ('email' == $updateKey) {
                            $user->info->email = $updateValue;
                        }
                        $user->$updateKey = $updateValue;
                    }
                } catch (Exception $e) {
                    
                }
                $services->get('repositories')->store($user);
            }

            $resultMessage = $result->getMessages();
            // TODO: send a mail also when required (maybe first mail failed or email has changed)
            if (array_key_exists('firstLogin', $resultMessage) && $resultMessage['firstLogin'] === True) {
                // first external Login
                $userName = $this->params()->fromPost('user');
                $this->getServiceLocator()->get('Log/Core/Ats')->debug('first login for User: ' . $userName);
                //
                if (preg_match("/^(.*)@\w+$/", $userName, $realUserName)) {
                    $userName = $realUserName[1];
                }
                $scheme = '';
                $domain = '';
                $uri = $this->getRequest()->getUri();
                if (isset($uri)) {
                    $scheme = $uri->getScheme();
                    $domain = $uri->getHost();
                }
                $viewHelperManager = $services->get('ViewHelperManager');
                $basePath = $viewHelperManager->get('basePath')->__invoke();
                $mail = $this->templatedMail(array(
                    'displayName' => $userName,
                    'password' => $password,
                    'uri' => $scheme . '://' . $domain . $basePath,
                ));
                $mail->template('first-login');
                $mail->addTo($user->getInfo()->getEmail());
                $mail->informationComplete();

                if (isset($mail) && $mail->send()) {
                    $this->getServiceLocator()->get('Log/Core/Ats')->info('Mail first-login sent to ' . $userName);
                } else {
                    $this->getServiceLocator()->get('Log/Core/Ats')->warn('No Mail was sent');
                }
            }

            return new JsonModel(array(
                'status' => 'success',
                'token' => session_id()
            ));
        } else {
            $services->get('Log/Core/Ats')->info('Failed to authenticate User ' . $this->params()->fromPost('user') .
                    ' via ' . $this->params()->fromPost('appKey'));

            $this->getResponse()->setStatusCode(403);
            return new JsonModel(array(
                'status' => 'failure',
                'code' => $result->getCode(),
                'messages' => $result->getMessages(),
            ));
        }
    }

    public function groupAction() {
        //$adapter = $this->getServiceLocator()->get('ExternalApplicationAdapter');
        $auth = $this->getServiceLocator()->get('AuthenticationService');
        $userGrpAdmin = $auth->getUser();
        $this->getServiceLocator()->get('Log/Core/Ats')->info('User ' . $auth->getUser()->getInfo()->getDisplayName());
        $grp = $this->params()->fromQuery('group');

        //$this->getServiceLocator()->get('Log/Core/Ats')->info('Get ' . var_export($_GET, true));
        // if the request is made by an external host, add his identification-key to the name
        $loginSuffix = '';
        $e = $this->getEvent();
        $loginSuffixResponseCollection = $this->getEventManager()->trigger('login.getSuffix', $e);
        if (!$loginSuffixResponseCollection->isEmpty()) {
            $loginSuffix = $loginSuffixResponseCollection->last();
        }
        // make out of the names a list of Ids
        $params = $this->getRequest()->getQuery();
        $groupUserId = array();
        $notFoundUsers = array();
        //$users = $this->getRepository();
        $users = $this->getServiceLocator()->get('repositories')->get('Auth/User');
        if (!empty($params->group)) {
            foreach ($params->group as $grp_member) {
                $user = $users->findByLogin($grp_member . $loginSuffix);
                if (!empty($user)) {
                    $groupUserId[] = $user->id;
                } else {
                    $notFoundUsers[] = $grp_member . $loginSuffix;
                }
            }
        }
        $name = $params->name;
        if (!empty($params->name)) {
            $group = $this->auth()->getUser()->getGroup($params->name, /* create */ true);
            $group->setUsers($groupUserId);
        }
        $this->getServiceLocator()->get('Log/Core/Ats')->info('Update Group Name: ' . $name . PHP_EOL . str_repeat(' ', 36) . 'Group Owner: ' . $userGrpAdmin->getLogin() . PHP_EOL .
                str_repeat(' ', 36) . 'Group Members Param: ' . implode(',', $params->group) . PHP_EOL .
                str_repeat(' ', 36) . 'Group Members: ' . count($groupUserId) . PHP_EOL . str_repeat(' ', 36) . 'Group Members not found: ' . implode(',', $notFoundUsers));

        return new JsonModel(array(
        ));
    }

    /**
     * Logout
     *
     * Redirects To: Route 'home'
     */
    public function logoutAction() {
        $auth = $this->getServiceLocator()->get('AuthenticationService');
        $this->getServiceLocator()->get('Log/Core/Ats')->info('User ' . ($auth->getUser()->login == '' ? $auth->getUser()->info->displayName : $auth->getUser()->login) . ' logged out');
        $auth->clearIdentity();

        $hauth = $this->getServiceLocator()->get('HybridAuthAdapter');
        $hauth->logoutAllProviders();
        unset($_SESSION['HA::STORE']);

        $this->notification()->success(/* @translate */ 'You are now logged out');
        return $this->redirect()->toRoute(
                        'lang', array('lang' => $this->params('lang'), 'logout' => true)
        );
    }

}
