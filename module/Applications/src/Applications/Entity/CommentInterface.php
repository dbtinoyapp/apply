<?php
namespace Applications\Entity;

use Core\Entity\IdentifiableEntityInterface;
use Auth\Entity\UserInterface;

interface CommentInterface extends IdentifiableEntityInterface
{
    
    /**
     * Sets the user
     * 
     * @param UserInterface $user
     * @return CommentInterface
     */
    public function setUser(UserInterface $user);
    
    /**
     * Gets the user
     * 
     * @return UserInterface
     */
    public function getUser();
    
    /**
     * Gets the comment message
     * 
     * @return string
     */
    public function getMessage();
    
    /**
     * Sets the comment message
     * 
     * @param string $message
     * @return CommentInterface
     */
    public function setMessage($message);
    
    /**
     * Gets this comment's application rating
     * 
     * @return RatingInterface
     */
    public function getRating();
    
    /**
     * Sets this comment's application rating
     * 
     * @param RatingInterface $rating
     * @return CommentInterface
     */
    public function setRating(RatingInterface $rating);
}

