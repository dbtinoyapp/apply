<?php
namespace Core\Entity;

interface RatingInterface extends EntityInterface
{
    /**#@+
     * Rating values
     * @var int
     */
    const RATING_NONE      = 0;
    const RATING_POOR      = 1;
    const RATING_BAD       = 2;
    const RATING_AVERAGE   = 3;
    const RATING_GOOD      = 4;
    const RATING_EXCELLENT = 5;
    /**#@-*/
    
    /**
     * Calculates the average rating value.
     * 
     * @return int
     */
    public function getAverage();
}

