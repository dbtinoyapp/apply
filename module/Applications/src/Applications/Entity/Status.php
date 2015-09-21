<?php
namespace Applications\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Application status entity 
 *
 * @ODM\EmbeddedDocument
 */
class Status extends AbstractEntity implements StatusInterface
{
    /**
     * status values
     */
    protected static $orderMap = array(
        self::NEWLY => 10,
        self::HIRED => 20,
        self::PROCESS => 30,
        self::FAILED => 40
    );

    /**
     * name of the status
     * 
     * @var string
     * @ODM\String
     */
    protected $name;

    /**
     * integer for ordering states.
     * 
     * @var string
     * @ODM\String
     */
    protected $order;

    public function __construct($status = self::NEWLY)
    {
        $constant = 'self::' . strtoupper($status);
        if (!defined($constant)) {
            throw new \DomainException('Unknown status: ' . $status);
        }
        $this->name=constant($constant);
        $this->order=$this->getOrder();
    }

    /**
     * @see \Applications\Entity\StatusInterface::getName()
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @see \Applications\Entity\StatusInterface::getOrder()
     * @return Int
     */
    public function getOrder()
    {
        return self::$orderMap[$this->getName()];
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getStates()
    {
        $states = self::$orderMap;
        asort($states, SORT_NUMERIC);
        return array_keys($states);
    }
}