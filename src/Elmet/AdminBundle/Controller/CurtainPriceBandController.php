<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainPriceBand;
use Symfony\Component\HttpFoundation\Response;

class CurtainPriceBandController extends Controller
{ 
    public function viewAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBands = $repository->findAll();        
        
        $lastPriceBand = $curtainPriceBands[count($curtainPriceBands)-1];
        $lastId = $lastPriceBand->getId();
        
        $canRemove = array();
        
        foreach($curtainPriceBands as $curtainPriceBand) {
            
            if( (count($curtainPriceBand->getCurtainDesigns()) == 0) &&
                (count($curtainPriceBand->getCushionCovers()) == 0)  &&    
                (count($curtainPriceBand->getCurtainTieBacks()) == 0) &&
                (count($curtainPriceBand->getCurtainPelmets()) == 0) &&
                (count($curtainPriceBand->getCurtainFabrics()) == 0) &&
                (count($curtainPriceBand->getCurtainPrices()) == 0)) {
                
                $canRemove[$curtainPriceBand->getId()] = TRUE;
               
            }
            else {
                $canRemove[$curtainPriceBand->getId()] = FALSE;
            }
        }
        
        return $this->render('ElmetAdminBundle:CurtainPriceBand:index.html.twig', array('curtainPriceBands' => $curtainPriceBands,'lastId' => $lastId, 'canRemove' => $canRemove));
            
    }
 
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBand = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($curtainPriceBand);
        $em->flush();
        
        return $this->viewAction();
    }
    
    public function updateAction()
    {    
         if($this->getRequest()->get('save_new') != null){
               
            $curtainPriceBand = new CurtainPriceBand();
            
            $curtainPriceBand->setName($this->getRequest()->get('name_new'));
                        
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($curtainPriceBand);
            $em->flush();
            
        } else {
            
            if ($this->getRequest()->get('submit_all') != null) {
                
                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                
                $curtainPriceBands = $repository->findAll();
                
                foreach($curtainPriceBands as $curtainPriceBand) {
                   
                    $curtainPriceBand->setName($this->getRequest()->get('all:name_'.$curtainPriceBand->getId()));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($curtainPriceBand);
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

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                    $curtainPriceBand = $repository->findOneById($id);             
                    $curtainPriceBand->setName($this->getRequest()->get('single:name_'.$id));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($curtainPriceBand);
                    $em->flush();
                }
            }
            
        }
                   
        return $this->viewAction();
    }
}
