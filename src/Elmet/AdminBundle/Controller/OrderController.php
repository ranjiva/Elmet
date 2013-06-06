<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{    
    public function searchAction()
    { 
         return $this->render('ElmetAdminBundle:Order:search.html.twig');
    }
    
    public function detailsAction($id) {
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
        $order = $repository->findOneById($id);
        
        return $this->render('ElmetSiteBundle:PayPal:email.html.php',array('order' => $order));
        
    }
    
    public function resultsAction()
    {
        //search by the most restrictive criteria first. If that does not return any results then
        //search by the next restrictive criteria
        
        $found = false;
            
        if ($found == false) {
            
            $custom = $this->getRequest()->get('custom');
            
            if ($custom != null) {
            
                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
                $order = $repository->findOneById($custom);
            
                if ($order != null) {
                    $orders = array();
                    $orders[] = $order;
                    $found = true;
                }
            }
        }
        
        if ($found == false) {
            
            $email = $this->getRequest()->get('email');
            
            if ($email != null) {
            
                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
                $orders = $repository->findByEmail($email);
            
                if (count($orders) > 0) {
                    $found = true;
                }
            }
        }
        
        if ($found == false) {
            
            $postcode = $this->getRequest()->get('postcode');
            
            if ($postcode != null) {
            
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery('SELECT o FROM ElmetSiteBundle:Order o WHERE o.billing_postcode = :postcode');
                $query->setParameter('postcode',$postcode);
                $orders = $query->getResult();     
               
                if (count($orders) > 0) {
                    $found = true;
                }
            }
        }
        
        if ($found == false) {
            
            $name = $this->getRequest()->get('name');
            
            if ($name != null) {
            
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery('SELECT o FROM ElmetSiteBundle:Order o WHERE o.billing_name = :name');
                $query->setParameter('name',$name);
                $orders = $query->getResult();     
               
                if (count($orders) > 0) {
                    $found = true;
                }
            }
        }
        
        if ($found == false) {
            
            $status = $this->getRequest()->get('status');
            
            if ($status != null) {
            
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery('SELECT o FROM ElmetSiteBundle:Order o WHERE o.order_status = :status');
                $query->setParameter('status',$status);
                $orders = $query->getResult();     
               
                if (count($orders) > 0) {
                    $found = true;
                }
            }
        }
        
        if ($found == false) {
            $orders = array();
        }
        
        return $this->render('ElmetAdminBundle:Order:results.html.twig',array('orders' => $orders));
        
    }
    
    
    
    
}
