<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;
use Elmet\SiteBundle\Entity\Testimonial;
use Symfony\Component\HttpFoundation\Response;

class TestimonialController extends BaseController
{
    /** * @codeCoverageIgnore */
    public function createAction()
    {
          $testimonial = new Testimonial();
          
          $testimonial->setCustomerDetails("Ranjiva Prasad");
          $testimonial->setFeatured('1');
          $testimonial->setTestimonial("Great Job!");
          
          $em = $this->getDoctrine()->getEntityManager();
          $em->persist($testimonial);
          $em->flush();
          
          return new Response('Created testimonial id '.$testimonial->getId());
    }
    
    public function fetchAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Testimonial');
        $testimonials = $repository->findAll();        
        
        return $this->render('ElmetSiteBundle:Testimonial:index.html.php',array('testimonials' => $testimonials,'featured' => $this->getFeaturedTestimonial(),'numBasketItems' => $this->getNumBasketItems()));
           
    }
}

?>