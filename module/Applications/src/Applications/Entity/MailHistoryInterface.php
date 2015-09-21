<?php
namespace Applications\Entity;

interface MailHistoryInterface extends HistoryInterface
{
    public function setSubject($subject);
    public function getSubject();
    
    public function setMailText($text);
    public function getMailText();
}

