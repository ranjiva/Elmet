<?php

namespace Elmet\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class HelloController extends Controller
{
    
    public function indexAction($name)
    {
        return new Response('<html><body>Hello Dear Dr '.$name.'!</body></html>');
    }
}

?>