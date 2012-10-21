<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;

class HomeController extends BaseController
{
    public function indexAction()
    {         
          return $this->render('ElmetSiteBundle:Home:index.html.php',array('featured' => $this->getFeaturedTestimonial(),'numBasketItems' => $this->getNumBasketItems()));
        
    }
}

?>