<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;

class CustServicesController extends BaseController
{
    public function indexAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:index.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function deliveryAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:delivery.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function returnsAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:returns.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function privacyAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:privacy.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function coloursAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:colours.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function samplesAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:samples.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function measuringAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:measuring.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
}

?>