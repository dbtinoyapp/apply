<?php

namespace Cv\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Training extends Education implements EducationInterface
{
}