<?php

namespace Auth\Form;

class UserProfile extends UserInfo {

    public function init() {
        parent::init();
        $this->setName('user-profile-form');
        //->setHydrator(new \Core\Model\Hydrator\ModelHydrator());
//        $this->add(
//                $this->forms->get('Auth/UserBaseFieldset')
//        );
        $this->add($this->forms->get('DefaultButtonsFieldset'));
    }

//    public function setObject($object) {
//        $this->get('base')->setObject($object);
//        return parent::setObject($object);
//    }

}
