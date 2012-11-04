<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainPriceBand;
use Elmet\SiteBundle\Entity\CurtainEyeletPriceBand;
use Elmet\SiteBundle\Entity\CurtainPrice;
use Elmet\AdminBundle\Entity\CurtainSize;
use Elmet\AdminBundle\Entity\CurtainType;
use Symfony\Component\HttpFoundation\Response;

class CurtainPriceController extends Controller {
        
    public function viewAction($id)
    {
        if ($id == -1) {
            
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPrice');
            $curtainPrices = $repository->findAll();
            
        } else {
           
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery('SELECT cp FROM ElmetSiteBundle:CurtainPrice cp
                                        JOIN cp.curtain_price_band cpb
                                        WHERE cpb.id = :id');
        
            $query->setParameter('id',$id);
            
            $curtainPrices = $query->getResult(); 
        }
        
        $curtainTypes = array(new CurtainType("HomeWindow"),new CurtainType("CaravanWindow"),new CurtainType("CaravanDoor"));
        
        $curtainPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBands = $curtainPriceBandRepository->findAll();        
        
        $curtainEyeletPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
        $curtainEyeletPriceBands = $curtainEyeletPriceBandRepository->findAll();
    
        $prevId = -1;
        $nextId = -1;
        
        foreach($curtainPriceBands as $curtainPriceBand) {
            
            if (($curtainPriceBand->getId() >= $id+1) and ($nextId == -1)) {
                $nextId = $curtainPriceBand->getId();
            }
            
            if ($curtainPriceBand->getId() < $id) {
                $prevId = $curtainPriceBand->getId();
            }
        }
        
        return $this->render('ElmetAdminBundle:CurtainPrice:index.html.twig', array('curtainPrices' => $curtainPrices, 'curtainSizes' => $this->getCurtainSizes(), 'curtainPriceBands' => $curtainPriceBands, 'curtainEyeletPriceBands' => $curtainEyeletPriceBands, 'curtainTypes' => $curtainTypes, 'prevId' => $prevId, 'nextId' => $nextId, 'id' => $id));
    } 
    
    public function updateAction()
    {    
         if($this->getRequest()->get('save_new') != null){
                 
            $curtainPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
            $curtainPriceBand = $curtainPriceBandRepository->findOneById($this->getRequest()->get('curtainpriceband_current'));
            
            $curtainPrice = new CurtainPrice();
            
            $curtainPrice->setCurtainPriceBand($curtainPriceBand);
            $curtainPrice->setSize($this->getRequest()->get('size_new'));
            $curtainPrice->setType($this->getRequest()->get('type_new'));
            $curtainPrice->setPrice($this->getRequest()->get('price_new')); 
            
            if ($this->getRequest()->get('curtaineyeletpriceband_new') != "empty") {
              
                $curtainEyeletPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainEyeletPriceBand');
                $curtainEyeletPriceBand = $curtainEyeletPriceBandRepository->findOneById($this->getRequest()->get('curtaineyeletpriceband_new'));
                $curtainPrice->setCurtainEyeletPriceBand($curtainEyeletPriceBand);
            }
            
            $curtainSizes = $this->getCurtainSizes();
            
            $size = $this->getRequest()->get('size_new');
            
            $curtainPrice->setHeight($curtainSizes[$size]->getHeight());
            $curtainPrice->setWidth($curtainSizes[$size]->getWidth());
            
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($curtainPrice);
            $em->flush();
            
        } else {
            
            if ($this->getRequest()->get('submit_all') != null) {
                
                $em = $this->getDoctrine()->getEntityManager();
                $query = $em->createQuery('SELECT cp FROM ElmetSiteBundle:CurtainPrice cp
                                        JOIN cp.curtain_price_band cpb
                                        WHERE cpb.id = :id');
        
                $query->setParameter('id',$this->getRequest()->get('curtainpriceband_current'));
            
                $curtainPrices = $query->getResult(); 
               
                foreach($curtainPrices as $curtainPrice) {
                   
                    $curtainPrice->setPrice($this->getRequest()->get('all:price_'.$curtainPrice->getId()));
                   
                    $em->merge($curtainPrice);
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
                    
                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPrice');
                    $curtainPrice = $repository->findOneById($id);  
                    $curtainPrice->setPrice($this->getRequest()->get('single:price_'.$id));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($curtainPrice);
                    $em->flush();
                }
            }
            
        }
                
        return $this->viewAction($this->getRequest()->get('curtainpriceband_current'));
    }
    
    public function indexAction()
    {
        return $this->viewAction(1);
    }
    
    public function removeAction($id,$priceBandId)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPrice');
        $curtainPrice = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($curtainPrice);
        $em->flush();
        
        return $this->viewAction($priceBandId);

    }
    
    
    public function getCurtainSizes() {
         
        $curtainSizes = array("2ft 6\" x 6ft 6\"" => new CurtainSize("2ft 6\" x 6ft 6\"",2.6,6.6),
                              "3ft x 3ft" => new CurtainSize("3ft x 3ft",3.0,3.0),
                              "6ft x 3ft" => new CurtainSize("6ft x 3ft",6.0,3.0),
                              "46\" x 54\"" => new CurtainSize("46\" x 54\"",46.0,54.0),
                              "46\" x 72\"" => new CurtainSize("46\" x 72\"",46.0,72.0),
                              "46\" x 90\"" => new CurtainSize("46\" x 90\"",46.0,90.0),
                              "46\" x 108\"" => new CurtainSize("46\" x 108\"",46.0,108.0),
                              "52\" x 54\"" => new CurtainSize("52\" x 54\"",52.0,54.0),
                              "52\" x 72\"" => new CurtainSize("52\" x 72\"",52.0,72.0),
                              "52\" x 90\"" => new CurtainSize("52\" x 90\"",52.0,90.0),
                              "52\" x 108\"" => new CurtainSize("52\" x 108\"",52.0,108.0),
                              "66\" x 54\"" => new CurtainSize("66\" x 54\"",66.0,54.0),
                              "66\" x 72\"" => new CurtainSize("66\" x 72\"",66.0,72.0),
                              "66\" x 90\"" => new CurtainSize("66\" x 90\"",66.0,90.0),
                              "66\" x 108\"" => new CurtainSize("66\" x 108\"",66.0,108.0),
                              "76\" x 54\"" => new CurtainSize("76\" x 54\"",76.0,54.0),
                              "76\" x 72\"" => new CurtainSize("76\" x 72\"",76.0,72.0),
                              "76\" x 90\"" => new CurtainSize("76\" x 90\"",76.0,90.0),
                              "76\" x 108\"" => new CurtainSize("76\" x 108\"",76.0,108.0),
                              "90\" x 54\"" => new CurtainSize("90\" x 54\"",90.0,54.0),
                              "90\" x 72\"" => new CurtainSize("90\" x 72\"",90.0,72.0),
                              "90\" x 90\"" => new CurtainSize("90\" x 90\"",90.0,90.0),
                              "90\" x 108\"" => new CurtainSize("90\" x 108\"",90.0,108.0),
                              "104\" x 54\"" => new CurtainSize("104\" x 54\"",104.0,54.0),
                              "104\" x 72\"" => new CurtainSize("104\" x 72\"",104.0,72.0),
                              "104\" x 90\"" => new CurtainSize("104\" x 90\"",104.0,90.0),
                              "104\" x 108\"" => new CurtainSize("104\" x 108\"",104.0,108.0),
                              "132\" x 54\"" => new CurtainSize("132\" x 54\"",132.0,54.0),
                              "132\" x 72\"" => new CurtainSize("132\" x 72\"",132.0,72.0),
                              "132\" x 90\"" => new CurtainSize("132\" x 90\"",132.0,90.0),
                              "132\" x 108\"" => new CurtainSize("132\" x 108\"",132.0,108.0),
                              "152\" x 54\"" => new CurtainSize("152\" x 54\"",152.0,54.0),
                              "152\" x 72\"" => new CurtainSize("152\" x 72\"",152.0,72.0),
                              "152\" x 90\"" => new CurtainSize("152\" x 90\"",152.0,90.0),
                              "152\" x 108\"" => new CurtainSize("152\" x 108\"",152.0,108.0));
        
        return $curtainSizes;
    }
}

?>
