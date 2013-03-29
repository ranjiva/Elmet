<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;

class BeddingsController extends BaseController
{
    public function indexAction()
    {
          return $this->render('ElmetSiteBundle:Beddings:index.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
}

?>