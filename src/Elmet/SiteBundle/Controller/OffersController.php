<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;

class OffersController extends BaseController
{
    public function indexAction()
    {
          return $this->render('ElmetSiteBundle:Offers:index.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
}

?>