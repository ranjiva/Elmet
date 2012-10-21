<?php

namespace Elmet\SiteBundle\Entity;

use Doctrine\ORM\mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="testimonials")
*/
class Testimonial
{
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
     
     /**
     * @ORM\Column(type="string", length=255) 
     */
     protected $customer_details;
     
     /**
     * @ORM\Column(type="text") 
     */
     protected $testimonial;
     
     /**
     * @ORM\Column(type="string", length=1)
     */
     protected $featured;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customer_details
     *
     * @param string $customerDetails
     */
    public function setCustomerDetails($customerDetails)
    {
        $this->customer_details = $customerDetails;
    }

    /**
     * Get customer_details
     *
     * @return string 
     */
    public function getCustomerDetails()
    {
        return $this->customer_details;
    }

    /**
     * Set testimonial
     *
     * @param text $testimonial
     */
    public function setTestimonial($testimonial)
    {
        $this->testimonial = $testimonial;
    }

    /**
     * Get testimonial
     *
     * @return text 
     */
    public function getTestimonial()
    {
        return $this->testimonial;
    }

    /**
     * Set featured
     *
     * @param string $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * Get featured
     *
     * @return string 
     */
    public function getFeatured()
    {
        return $this->featured;
    }
}