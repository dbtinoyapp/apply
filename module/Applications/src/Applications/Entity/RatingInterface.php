<?php
namespace Applications\Entity;

use Core\Entity\RatingInterface as CoreRatingInterface;

interface RatingInterface extends CoreRatingInterface
{
    
    
    /**
     * Gets the rating for an application
     * 
     * @return int 
     */
    public function getRating();
    
    /**
     * Sets the rating for an application
     * 
     * @param int $rating
     * @return RatingInterface
     */
    public function setRating($rating);
    
}

