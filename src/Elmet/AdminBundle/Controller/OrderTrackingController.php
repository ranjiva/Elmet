<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Elmet\AdminBundle\Entity\Batch;

class OrderTrackingController extends Controller
{    
    public function cancelAction($id) {
        
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:OrderTracking');
          
        $orderTracking = $repository->findOneById($id);
        
        $orderTracking->setTrackingStatus("Cancelled");
        $orderTracking->getOrder()->setOrderStatus("Cancelled");
        
        $em->merge($orderTracking);
        
        $em->flush();
        
        $message = \Swift_Message::newInstance()
                        ->setSubject("Notification of Order Cancellation")
                        ->setFrom($this->container->getParameter('orders_address'))
                        ->setTo($orderTracking->getOrder()->getEmail())
                        ->setBody($this->renderView('ElmetAdminBundle:OrderTracking:cancellation_email.html.twig', array('trackingDetail' => $orderTracking)),'text/html');
            
        $this->get('mailer')->send($message);
        
        return $this->viewAction();
        
    }
    
    public function messageAction($id) {
        
        $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:OrderTracking');
        $orderTracking = $repository->findOneById($id);
        
        return $this->render('ElmetAdminBundle:OrderTracking:message.html.twig',array('trackingDetail' => $orderTracking));
        
    }
    
    public function sendAction() {
        
        $repository = $this->getDoctrine()->getRepository('ElmetAdminBundle:OrderTracking');
        $orderTracking = $repository->findOneById($this->getRequest()->get('id'));
        
        $message = \Swift_Message::newInstance()
                        ->setSubject($this->getRequest()->get('subject'))
                        ->setFrom($this->container->getParameter('orders_address'))
                        ->setTo($orderTracking->getOrder()->getEmail())
                        ->setBody($this->renderView('ElmetAdminBundle:OrderTracking:message_email.html.twig', array('message' => $this->getRequest()->get('message'),'subject' => $this->getRequest()->get('subject'))),'text/html');
            
        $this->get('mailer')->send($message);
        return $this->render('ElmetAdminBundle:OrderTracking:email_confirmation.html.twig',array('trackingDetail' => $orderTracking));
    }
    
    public function viewAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT ot FROM ElmetAdminBundle:OrderTracking ot JOIN ot.order o WHERE (ot.tracking_status != \'Dispatched\' and ot.tracking_status != \'Cancelled\') ORDER BY o.created ASC');
        $trackingDetails = $query->getResult();
        
        return $this->render('ElmetAdminBundle:OrderTracking:view.html.twig',array('trackingDetails' => $trackingDetails));
    }
    
    public function detailsAction($id) {
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
        $order = $repository->findOneById($id);
        
        return $this->render('ElmetSiteBundle:PayPal:email.html.php',array('order' => $order));
        
    }
    
    public function emailAction($id) {
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
        $order = $repository->findOneById($id);
        
        $message = \Swift_Message::newInstance()
                        ->setSubject('Elmet Curtains - Online Customer Order')
                        ->setFrom($this->container->getParameter('noreply_address'))
                        ->setTo($this->container->getParameter('orders_address'))
                        ->setBody($this->renderView('ElmetSiteBundle:PayPal:email.html.php', array('order' => $order)),'text/html');

        $this->get('mailer')->send($message);
        
        return $this->viewAction();
        
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
    
    public function moveUnshippedOrders($closedBatches) {
        
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT ot FROM ElmetAdminBundle:OrderTracking ot WHERE ot.batch = :batch and ot.tracking_status != \'Dispatched\' and ot.tracking_status != \'Cancelled\'');
        
        $batchRepository = $batchRepository = $this->getDoctrine()->getRepository('ElmetAdminBundle:Batch');
        
        $createNextBatch = false;
        
        $currentBatch = $batchRepository->findOneBy(array('batch_status' => 'Current'));
        $nextBatch = $batchRepository->findOneBy(array('batch_status' => 'Next'));
        
        if ($nextBatch == null) {
            $createNextBatch = true;
        }
        
        if ($currentBatch == null) {
            
            if ($nextBatch != null) {
                $currentBatch = $nextBatch;
                $createNextBatch = true;
            }
        }
        
        if ($currentBatch == null) {
            
            $currentBatch = new Batch();
            $currentBatch->setBatchStatus('Current');
            $currentBatch->setNextItemId(1);
            $em->persist($currentBatch);     
        } else {
            $currentBatch->setBatchStatus('Current');
        }
        
        $nextItemId = $currentBatch->getNextItemId();
        
        foreach($closedBatches as $closedBatch) {
            
            $query->setParameter('batch',$closedBatch);
            $trackingDetails = $query->getResult();
            
            foreach($trackingDetails as $trackingDetail) {
                
                $trackingDetail->setBatch($currentBatch);
                $trackingDetail->setItemId($nextItemId);
                $nextItemId = $nextItemId + 1;
                $em->merge($trackingDetail);
            }
        }
        
        $currentBatch->setNextItemId($nextItemId);
        $em->merge($currentBatch);
        
        if ($createNextBatch) {
        
            $nextBatch = new Batch();
            $nextBatch->setBatchStatus('Next');
            $nextBatch->setNextItemId(1);
            $em->persist($nextBatch);
        }
        
        $em->flush();
    }
    
    public function generateResponse($selectedDetails,$dispatchDate) {
        
        $content = $this->renderView('ElmetAdminBundle:OrderTracking:dispatch_file.csv.twig',array('trackingDetails' => $selectedDetails,'dispatchDate' => $dispatchDate));
        
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename=dispatch.csv');

        return $response;
    }
    
    public function sendDispatchEmails($dispatchedOrders) {
        
        foreach($dispatchedOrders as $dispatchedOrder) {
        
            $message = \Swift_Message::newInstance()
                        ->setSubject('Elmet Curtains - Notice of Dispatch')
                        ->setFrom($this->container->getParameter('orders_address'))
                        ->setTo($dispatchedOrder->getOrder()->getEmail())
                        ->setBody($this->renderView('ElmetAdminBundle:OrderTracking:dispatch_email.html.twig', array('trackingDetail' => $dispatchedOrder)),'text/html');
            
            $this->get('mailer')->send($message);
        }
        
        
    }
    
    public function worksheetAction($type) {
        
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT ot FROM ElmetAdminBundle:OrderTracking ot JOIN ot.batch b WHERE b.batch_status = :type ORDER BY ot.item_id ASC');
        $query->setParameter('type',$type);
        $trackingDetails = $query->getResult();
        
        return $this->render('ElmetAdminBundle:OrderTracking:worksheet.html.twig',array('trackingDetails' => $trackingDetails, 'type' => $type));
    }
}
