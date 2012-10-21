<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('ElmetAdminBundle:Home:index.html.twig');
    }
}
