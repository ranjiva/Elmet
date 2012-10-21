<?php

namespace Elmet\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\Order;

class BaseController extends Controller
{
    public function getFeaturedTestimonial()
    {
          $session = $this->getRequest()->getSession();
          $featured = $session->get('featuredTestimonial');
          
          if ($featured == null)
          {     
                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Testimonial');
                $featured = $repository->findOneByFeatured('1');
                $session->set('featuredTestimonial', $featured);
          }     
                
          return $featured;
    }
    
    public function getOrder()
    {
        $session = $this->getRequest()->getSession();
        $order = $session->get('order');
          
        if ($order == null)
        {     
            $order = new Order();
            
            $session->set('order', $order);
        }     
                
        return $order;
    }
    
    public function getNumBasketItems()
    {
        $order = $this->getOrder();
        return count($order->getOrderItems());
        
    }
}


?>
