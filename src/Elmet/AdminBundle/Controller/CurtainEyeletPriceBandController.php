<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainEyeletPriceBand;
use Elmet\AdminBundle\Entity\CurtainWidth;
use Symfony\Component\HttpFoundation\Response;

class CurtainEyeletPriceBandController extends Controller
{ 
    public function viewAction()
    {
       $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
        $curtainEyeletPriceBands = $repository->findAll();        
        
        $lastPriceBand = $curtainEyeletPriceBands[count($curtainEyeletPriceBands)-1];
        $lastId = $lastPriceBand->getId();
        
        $canRemove = array();
        
        $curtainPriceRepository =  $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPrice');
        $curtainPrices = $curtainPriceRepository->findAll();      
                                    
        $ids = array();
        
        foreach($curtainPrices as $curtainPrice) {
            
            if (($curtainPrice->getType() != "CaravanWindow") and ($curtainPrice->getType() != "CaravanDoor")) {
            
                $ids[] = $curtainPrice->getCurtainEyeletPriceBand()->getId();
            }
        }
        
        
        foreach($curtainEyeletPriceBands as $curtainEyeletPriceBand) {
            
            if (in_array($curtainEyeletPriceBand->getId(),$ids)) {
           
                $canRemove[$curtainEyeletPriceBand->getId()] = FALSE;    
            }
            else {
                $canRemove[$curtainEyeletPriceBand->getId()] = TRUE;
            }
        }
        
        $curtainWidths = array(new CurtainWidth("46\""), new CurtainWidth("52\""), new CurtainWidth("66\""), new CurtainWidth("76\""),new CurtainWidth("90\""), new CurtainWidth("104\""), new CurtainWidth("132\""), new CurtainWidth("152\""));
         
        return $this->render('ElmetAdminBundle:CurtainEyeletPriceBand:index.html.twig', array('curtainEyeletPriceBands' => $curtainEyeletPriceBands,'lastId' => $lastId, 'canRemove' => $canRemove, 'curtainWidths' => $curtainWidths));
            
    }
 
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
        $cepb = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($cepb);
        $em->flush();
        
        return $this->viewAction();
    }
    
    public function updateAction()
    {    
         if($this->getRequest()->get('save_new') != null){
               
            $cepb = new CurtainEyeletPriceBand();
            
            $cepb->setCurtainSize($this->getRequest()->get('size_new'));
            $cepb->setPrice($this->getRequest()->get('price_new'));
                        
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($cepb);
            $em->flush();
            
        } else {
            
            if ($this->getRequest()->get('submit_all') != null) {
                
                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
                
                $cepbs = $repository->findAll();
                
                foreach($cepbs as $cepb) {
                   
                    $cepb->setCurtainSize($this->getRequest()->get('all:size_'.$cepb->getId()));
                    $cepb->setPrice($this->getRequest()->get('all:price_'.$cepb->getId()));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($cepb);
                }
                
                $em->flush();
                
            } else {
            
                $params = $this->getRequest()->request->all();
                $keys = array_keys($params);
                $id = 0;

                foreach($keys as $key) {
                    $param = $params[$key];

                    if($param=='Save') {
                       $index = stripos($key,'_');
                       $id = substr($key,$index+1);
                    }
                }

                if ($id != 0) {

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
                    $cepb = $repository->findOneById($id);             
                    $cepb->setCurtainSize($this->getRequest()->get('single:size_'.$id));
                    $cepb->setPrice($this->getRequest()->get('single:price_'.$id));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($cepb);
                    $em->flush();
                }
            }
            
        }
                   
        return $this->viewAction();
    }
}
