<?php

namespace Elmet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {
          return $this->render('ElmetSiteBundle:Home:index.html.php');
        //return new Response('<html><body>Hello!</body></html>');
    }
}

?>