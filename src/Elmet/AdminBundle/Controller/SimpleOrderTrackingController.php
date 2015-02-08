<?php

namespace Elmet\AdminBundle\Controller;

use Elmet\AdminBundle\Controller\OrderTrackingController;

class SimpleOrderTrackingController extends OrderTrackingController
{    
    
    public function viewAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT ot FROM ElmetAdminBundle:OrderTracking ot JOIN ot.order o WHERE (ot.tracking_status != \'Dispatched\' and ot.tracking_status != \'Cancelled\') ORDER BY o.created ASC');
        $trackingDetails = $query->getResult();
        
        return $this->render('ElmetAdminBundle:SimpleOrderTracking:view.html.twig',array('trackingDetails' => $trackingDetails));
    }
    
    public function dispatchAction($id) {
        
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:OrderTracking');
          
        $trackingDetail = $repository->findOneById($id);
        
        return $this->render('ElmetAdminBundle:SimpleOrderTracking:dispatch.html.twig',array('trackingDetail' => $trackingDetail));
    }
    
    public function sendAction() {
        
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:OrderTracking');
          
        $trackingDetail = $repository->findOneById($this->getRequest()->get('id'));
        
        $trackingDetail->setTrackingStatus('Dispatched');
        $trackingDetail->getOrder()->setOrderStatus('Dispatched');

        $dateParts = explode("/",$this->getRequest()->get('dispatchdate'));
        $date = date_create($dateParts[2]."-".$dateParts[1]."-".$dateParts[0]);

        $trackingDetail->setShipmentDate($date);                 
        $trackingDetail->setTrackingNumber($this->getRequest()->get('trackingnumber'));
        
        $this->sendDispatchEmail($trackingDetail);
        
        $em->merge($trackingDetail);
        $em->flush();
        
        return $this->render('ElmetAdminBundle:SimpleOrderTracking:dispatch_email_confirmation.html.twig',array('trackingDetail' => $trackingDetail));
    }
    
    public function sendDispatchEmail($trackingDetail) {
         
        $message = \Swift_Message::newInstance()
                    ->setSubject('Elmet Curtains - Notice of Dispatch')
                    ->setFrom($this->container->getParameter('orders_address'))
                    ->setTo($trackingDetail->getOrder()->getEmail())
                    ->setBody($this->renderView('ElmetAdminBundle:SimpleOrderTracking:dispatch_email.html.twig', array('trackingDetail' => $trackingDetail)),'text/html');

        $this->get('mailer')->send($message);
        
    }
    
    
    
    
    
    
    
    public function updateAction()
    {    
         if($this->getRequest()->get('process') != null){

             $em = $this->getDoctrine()->getEntityManager();
             $batchRepository = $this->getDoctrine()->getRepository('ElmetAdminBundle:Batch');
             $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:OrderTracking');

             $batch = $batchRepository->findOneBy(array('batch_status' => $this->getRequest()->get('batch')));
             
             $nextItemId = $batch->getNextItemId();
             
             $params = $this->getRequest()->request->all();
             $keys = array_keys($params);
             $id = 0;

             foreach($keys as $key) {
                $param = $params[$key];

                if($param=='selected') {
                   $index = stripos($key,'_');
                   $id = substr($key,$index+1);
                   
                   $orderTracking = $repository->findOneById($id);
                   
                   if (($orderTracking->getTrackingStatus() == 'Received') || ($orderTracking->getTrackingStatus() == 'In Progress')) {
                       $orderTracking->setTrackingStatus("In Progress");
                       $orderTracking->setItemId($nextItemId);
                       $orderTracking->setBatch($batch);
                       $nextItemId = $nextItemId + 1;
                       $em->merge($orderTracking);
                   }
                }
                
            }
            
            $batch->setNextItemId($nextItemId);
            $em->merge($batch);
            $em->flush();
            
            $response = $this->viewAction();
            
         } else if ($this->getRequest()->get('dispatch') != null) {
             
             $em = $this->getDoctrine()->getEntityManager();
             $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:OrderTracking');
             
             $selectedDetails = array();
             
             $params = $this->getRequest()->request->all();
             $keys = array_keys($params);
             $id = 0;

             foreach($keys as $key) {
                $param = $params[$key];

                if($param=='selected') {
                   $index = stripos($key,'_');
                   $id = substr($key,$index+1);
                   
                   $orderTracking = $repository->findOneById($id);
                   
                   if (($orderTracking->getTrackingStatus() == 'In Progress') || ($orderTracking->getTrackingStatus() == 'Processed')) {
                       $orderTracking->setTrackingStatus("Processed");
                       $orderTracking = $em->merge($orderTracking);
                       $selectedDetails[] = $orderTracking;
                   }
                }    
            }

            $em->flush();
            
            if (empty($selectedDetails)) {
                $response =  $this->viewAction();
            } else {
                $response = $this->generateResponse($selectedDetails,$this->getRequest()->get('dispatch_date'));
            } 
             
         } else if ($this->getRequest()->get('load') != null) {
             
             $em = $this->getDoctrine()->getEntityManager();
             $query = $em->createQuery('SELECT ot FROM ElmetAdminBundle:OrderTracking ot JOIN ot.order o WHERE o.id = :id');
            
             $trackingFile = $this->getRequest()->files->get('tracking_file');
             $fileName = $trackingFile->getPathname();
             
             $lines = file($fileName);
             
             $closedBatches = array();
             $dispatchedOrders = array();
             
             foreach($lines as $line) {
                 
                 $elements = explode(",",$line);
                 
                 $query->setParameter('id',intval($elements[0]));
                 
                 try {
                    $trackingDetail = $query->getSingleResult();
                 }
                 catch (\Doctrine\ORM\NoResultException $e) {
                    $trackingDetail = null;
                 }
                                  
                 if ($trackingDetail != null) {
                     
                     $trackingDetail->setTrackingStatus('Dispatched');
                     $trackingDetail->getOrder()->setOrderStatus('Dispatched');

                     $dateParts = explode("/",$elements[1]);
                     $date = date_create($dateParts[2]."-".$dateParts[1]."-".$dateParts[0]);

                     $trackingDetail->setShipmentDate($date);                 
                     $trackingDetail->setTrackingNumber($elements[2]);
                     $batch = $trackingDetail->getBatch();
                     $batch->setBatchStatus('Closed');
                     $closedBatches[$batch->getId()] = $batch;
                     $em->merge($batch);
                     $em->merge($trackingDetail);
                     
                     $dispatchedOrders[] = $trackingDetail;
                 }
             }
             
             $em->flush();
             
             $this->sendDispatchEmails($dispatchedOrders);
             
             //if a closed batch has any unshipped orders then move them to the next batch
             
             $this->moveUnshippedOrders($closedBatches);
             
             $response =  $this->viewAction();
             
         }
         
         return $response;
         
    }
    
}
