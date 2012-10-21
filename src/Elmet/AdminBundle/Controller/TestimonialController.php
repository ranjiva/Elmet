<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\Testimonial;
use Symfony\Component\HttpFoundation\Response;

class TestimonialController extends Controller
{ 
    public function viewAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Testimonial');
        $testimonials = $repository->findAll();        
        
        return $this->render('ElmetAdminBundle:Testimonial:index.html.twig', array('testimonials' => $testimonials));
    }
    
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Testimonial');
        $testimonial = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($testimonial);
        $em->flush();
        
        return $this->viewAction();
    }
    
    public function updateAction()
    {
        
        if($this->getRequest()->get('save_new') != null){
            
            $testimonial = new Testimonial();
            
            $testimonial->setTestimonial($this->getRequest()->get('testimonial_new'));
            $testimonial->setFeatured($this->getRequest()->get('featured_new'));
            $testimonial->setCustomerDetails($this->getRequest()->get('customer_new'));

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($testimonial);
            $em->flush();
            
        } else {
            $params = $this->getRequest()->request->all();
            $keys = array_keys($params);
            $testimonial_id = 0;
            
            foreach($keys as $key) {
                $param = $params[$key];
                
                if($param=='Save') {
                   $index = stripos($key,'_');
                   $testimonial_id = substr($key,$index+1);
                }
            }
            
            if ($testimonial_id != 0) {
                
                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Testimonial');
                $testimonial = $repository->findOneById($testimonial_id);              
                
                $testimonial->setTestimonial($this->getRequest()->get('testimonial_'.$testimonial_id));
                $testimonial->setFeatured($this->getRequest()->get('featured_'.$testimonial_id));
                $testimonial->setCustomerDetails($this->getRequest()->get('customer_'.$testimonial_id));
                
                $em = $this->getDoctrine()->getEntityManager();
                $em->merge($testimonial);
                $em->flush();
            }
            
        }
        
        return $this->viewAction();
    }
}
