<?php

namespace Elmet\SiteBundle\Entity;

class Contact
{
    protected $name;
    protected $tel;
    protected $email;
    protected $enquiry;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getTel()
    {
        return $this->tel;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getEnquiry()
    {
        return $this->enquiry;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setTel($tel)
    {
        $this->tel = $tel;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function setEnquiry($enquiry)
    {
        $this->enquiry = $enquiry;
    }
}

?>
