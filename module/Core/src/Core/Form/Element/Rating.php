<?php
namespace Core\Form\Element;


use Zend\Form\Element;
use Zend\Form\Element\Select;
use Core\Entity\RatingInterface;

/**
 * Star rating element.
 * 
 */
class Rating extends Select
{
    
    /** 
     * Seed empty option
     * @var String
     */
    protected $emptyOption = /*@translate*/ 'Not Rated';
    
    /**
     * Seed value options
     * 
     * @var array
     */
    protected $valueOptions = array(
        RatingInterface::RATING_POOR      => /*@translate*/ 'Poor',
        RatingInterface::RATING_BAD       => /*@translate*/ 'Bad',
        RatingInterface::RATING_AVERAGE   => /*@translate*/ 'Average',
        RatingInterface::RATING_GOOD      => /*@translate*/ 'Good',
        RatingInterface::RATING_EXCELLENT => /*@translate*/ 'Excellent',
    );
    
    /**
     * Seed attributes
     * 
     * @var array
     */
    protected $attributes = array(
        'type' => 'select',
        'class' => 'rating',
    );
}

