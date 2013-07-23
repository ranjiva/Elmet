<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\Order;

class DeliveryController extends Controller
{ 
    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
        $order = $repository->findOneById($id);        
              
        return $this->render('ElmetAdminBundle:Delivery:index.html.twig', array('order' => $order, 'message' => ""));
            
    }
    
    public function updateAction()
    {
        $id = $this->getRequest()->get('id');
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
        $order = $repository->findOneById($id);
        
        $order->setDeliveryName($this->getRequest()->get('name'));
        $order->setDeliveryAddress($this->getRequest()->get('address1'));
        $order->setDeliveryAddress2($this->getRequest()->get('address2'));
        $order->setDeliveryTown($this->getRequest()->get('town'));
        $order->setDeliveryPostcode($this->getRequest()->get('postcode'));
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($order);
        $em->flush();
            
        return $this->render('ElmetAdminBundle:Delivery:index.html.twig', array('order' => $order, 'message' => "Delivery details have been updated successfully"));
    }
}
