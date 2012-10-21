<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;

class CustServicesController extends BaseController
{
    public function indexAction()
    {
          return $this->render('ElmetSiteBundle:CustServices:index.html.php',array('featured' => $this->getFeaturedTestimonial(),'numBasketItems' => $this->getNumBasketItems()));
    }
}

?>