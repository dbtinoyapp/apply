<?php
namespace Core\Entity\Collection;

class ArrayCollection extends \Doctrine\Common\Collections\ArrayCollection
{
    public function fromArray(array $elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
        return $this;
    }
}

