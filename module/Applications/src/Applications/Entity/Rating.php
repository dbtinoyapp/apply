<?php
namespace Applications\Entity;

use Core\Entity\AbstractRatingEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Application rating entity
 * 
 * @ODM\EmbeddedDocument
 */
class Rating extends AbstractRatingEntity implements RatingInterface
{
    /**
     * Application rating value
     * @var int
     * @ODM\Int
     */
    protected $rating;
    
    /**
     * {@inheritDoc}
     * @see \Applications\Entity\RatingInterface::getRating()
     */
	public function getRating ()
    {
        return $this->rating;
    }

    /**
     * {@inheritDoc}
     * @see \Applications\Entity\RatingInterface::setRating()
     * @return Rating
     */
	public function setRating ($rating)
    {
        $this->rating = (int) $rating;
        return $this;
    }

    
    

    
    
}

