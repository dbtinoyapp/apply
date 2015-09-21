<?php
namespace Settings\Entity;

interface ModuleSettingsContainerInterface extends SettingsContainerInterface
{
    public function getModuleName();
}

