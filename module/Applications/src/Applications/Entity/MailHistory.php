<?php
namespace Applications\Entity;

class MailHistory extends History implements MailHistoryInterface
{
    protected $subject;
    protected $mailText;
	/**
     * @return the $subject
     */
    public function getSubject ()
    {
        return $this->subject;
    }

	/**
     * @param field_type $subject
     */
    public function setSubject ($subject)
    {
        $this->subject = $subject;
        return $this;
    }

	/**
     * @return the $mailText
     */
    public function getMailText ()
    {
        return $this->mailText;
    }

	/**
     * @param field_type $mailText
     */
    public function setMailText ($mailText)
    {
        $this->mailText = $mailText;
        return $this;
    }

    
    
}

