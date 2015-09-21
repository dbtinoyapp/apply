<?php

namespace Cv\Form;

use Zend\Form\Fieldset;
use Cv\Entity\NativeLanguage as NativeLanguageEntity;
use Core\Entity\Hydrator\EntityHydrator;

class NativeLanguageFieldset extends AbstractLanguageFieldset {

    public function init() {
        $this->setName('nativeLanguage')
                ->setHydrator(new EntityHydrator())
                ->setObject(new NativeLanguageEntity())
                ->setLabel('Native Language');
        parent::init();
    }

}
