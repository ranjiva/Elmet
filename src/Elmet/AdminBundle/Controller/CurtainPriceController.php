<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainPriceBand;
use Elmet\SiteBundle\Entity\CurtainEyeletPriceBand;
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
    
    public function indexAction()
    {
        return $this->viewAction(1);
    }
    
    
    
    
    public function getCurtainSizes() {
         
        $curtainSizes = array();
        $curtainSizes[] = new CurtainSize("2ft 6\" x 6ft 6\"",2.6,6.6);
        $curtainSizes[] = new CurtainSize("3ft x 3ft",3.0,3.0);
        $curtainSizes[] = new CurtainSize("6ft x 3ft",6.0,3.0);
        $curtainSizes[] = new CurtainSize("46\" x 54\"",46.0,54.0);
        $curtainSizes[] = new CurtainSize("46\" x 72\"",46.0,72.0);
        $curtainSizes[] = new CurtainSize("46\" x 90\"",46.0,90.0);
        $curtainSizes[] = new CurtainSize("46\" x 108\"",46.0,108.0);
        $curtainSizes[] = new CurtainSize("52\" x 54\"",52.0,54.0);
        $curtainSizes[] = new CurtainSize("52\" x 72\"",52.0,72.0);
        $curtainSizes[] = new CurtainSize("52\" x 90\"",52.0,90.0);
        $curtainSizes[] = new CurtainSize("52\" x 108\"",52.0,108.0);
        $curtainSizes[] = new CurtainSize("66\" x 54\"",66.0,54.0);
        $curtainSizes[] = new CurtainSize("66\" x 72\"",66.0,72.0);
        $curtainSizes[] = new CurtainSize("66\" x 90\"",66.0,90.0);
        $curtainSizes[] = new CurtainSize("66\" x 108\"",66.0,108.0);
        $curtainSizes[] = new CurtainSize("76\" x 54\"",76.0,54.0);
        $curtainSizes[] = new CurtainSize("76\" x 72\"",76.0,72.0);
        $curtainSizes[] = new CurtainSize("76\" x 90\"",76.0,90.0);
        $curtainSizes[] = new CurtainSize("76\" x 108\"",76.0,108.0);
        $curtainSizes[] = new CurtainSize("90\" x 54\"",90.0,54.0);
        $curtainSizes[] = new CurtainSize("90\" x 72\"",90.0,72.0);
        $curtainSizes[] = new CurtainSize("90\" x 90\"",90.0,90.0);
        $curtainSizes[] = new CurtainSize("90\" x 108\"",90.0,108.0);
        $curtainSizes[] = new CurtainSize("104\" x 54\"",104.0,54.0);
        $curtainSizes[] = new CurtainSize("104\" x 72\"",104.0,72.0);
        $curtainSizes[] = new CurtainSize("104\" x 90\"",104.0,90.0);
        $curtainSizes[] = new CurtainSize("104\" x 108\"",104.0,108.0);
        $curtainSizes[] = new CurtainSize("132\" x 54\"",132.0,54.0);
        $curtainSizes[] = new CurtainSize("132\" x 72\"",132.0,72.0);
        $curtainSizes[] = new CurtainSize("132\" x 90\"",132.0,90.0);
        $curtainSizes[] = new CurtainSize("132\" x 108\"",132.0,108.0);
        $curtainSizes[] = new CurtainSize("152\" x 54\"",152.0,54.0);
        $curtainSizes[] = new CurtainSize("152\" x 72\"",152.0,72.0);
        $curtainSizes[] = new CurtainSize("152\" x 90\"",152.0,90.0);
        $curtainSizes[] = new CurtainSize("152\" x 108\"",152.0,108.0);
        
        return $curtainSizes;
    }
}

?>
