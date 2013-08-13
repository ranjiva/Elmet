<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;
use Elmet\SiteBundle\Entity\Testimonial;
use Symfony\Component\HttpFoundation\Response;

class TestimonialController extends BaseController
{ 
    public function fetchAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT t FROM ElmetSiteBundle:Testimonial t ORDER BY t.id DESC');
        
        $testimonials = $query->getResult(); 
        
        return $this->render('ElmetSiteBundle:Testimonial:index.html.php',array('testimonials' => $testimonials,'featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
           
    }
}

?>