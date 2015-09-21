<?php

namespace Cv\Entity;

use Core\Entity\EntityInterface;

interface OtherLanguageInterface extends EntityInterface {

    public function setLanguage($language);

    public function getLanguage();

    public function setLevelReading($level);

    public function getLevelReading();

    public function setLevelSpoken($level);

    public function getLevelSpoken();

}
