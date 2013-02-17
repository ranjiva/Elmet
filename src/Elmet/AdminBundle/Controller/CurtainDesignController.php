<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainDesign;
use Elmet\AdminBundle\Entity\CurtainFinish;
use Elmet\AdminBundle\Entity\CushionFinish;
use Elmet\AdminBundle\Entity\TapeSize;
use Symfony\Component\HttpFoundation\Response;

class CurtainDesignController extends Controller
{ 
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT cd FROM ElmetSiteBundle:CurtainDesign cd JOIN cd.curtain_price_band cpb ORDER BY cpb.id, cd.url_name ASC');
        
        $curtainDesigns = $query->getResult();                                
        
        return $this->render('ElmetAdminBundle:CurtainDesign:index.html.twig', array('curtainDesigns' => $curtainDesigns));
    }
    
    public function viewAction($id)
    {
         $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainDesign');
         $curtainDesign = $repository->findOneById($id);
         $curtainDesigns = $repository->findAll();
         
         $urlNames = array();
                  
         foreach($curtainDesigns as $design) {
             
             $urlNames[] = $design->getUrlName();
         }
         
         $cushionFinishes = array(new CushionFinish("Corded"), new CushionFinish("Self-piped"));
         $curtainFinishes = array(new CurtainFinish("Fringed"), new CurtainFinish("Straight"));
         $tapeSizes = array(new TapeSize("3\""));
         
         $curtainPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
         $curtainPriceBands = $curtainPriceBandRepository->findAll();
         
         return $this->render('ElmetAdminBundle:CurtainDesign:view.html.twig', array('curtainDesign' => $curtainDesign,  'cushionFinishes' => $cushionFinishes, 'curtainFinishes' => $curtainFinishes, 'tapeSizes' => $tapeSizes, 'curtainPriceBands' => $curtainPriceBands, 'urlNames' => $urlNames));
    }
    
    public function newAction()
    {
         $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainDesign');
         $curtainDesigns = $repository->findAll();
         
         $urlNames = array();
                  
         foreach($curtainDesigns as $design) {
             
             $urlNames[] = $design->getUrlName();
         }
         
         $cushionFinishes = array(new CushionFinish("Corded"), new CushionFinish("Self-piped"));
         $curtainFinishes = array(new CurtainFinish("Fringed"), new CurtainFinish("Straight"));
         $tapeSizes = array(new TapeSize("3\""));
         
         $curtainPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
         $curtainPriceBands = $curtainPriceBandRepository->findAll();
         
         return $this->render('ElmetAdminBundle:CurtainDesign:new.html.twig',array('cushionFinishes' => $cushionFinishes, 'curtainFinishes' => $curtainFinishes, 'tapeSizes' => $tapeSizes, 'curtainPriceBands' => $curtainPriceBands, 'urlNames' => $urlNames));
    }
    
    public function updateAction()
    {
        $id = $this->getRequest()->get('id');
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainDesign');
        $curtainDesign = $repository->findOneById($id);
        
        $curtainPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBand = $curtainPriceBandRepository->findOneById($this->getRequest()->get('priceband'));
        
        $curtainDesign->setCurtainPriceBand($curtainPriceBand);
        $curtainDesign->setCushionFinish($this->getRequest()->get('cushionfinish'));
        $curtainDesign->setEyeletsAvailable($this->getRequest()->get('eyelets'));
        $curtainDesign->setFabricWidth($this->getRequest()->get('fabricwidth'));
        $curtainDesign->setFinish($this->getRequest()->get('curtainfinish'));
        $curtainDesign->setLined($this->getRequest()->get('lined'));
        $curtainDesign->setMaterials($this->getRequest()->get('materials'));
        $curtainDesign->setName($this->getRequest()->get('name'));
        $curtainDesign->setNew($this->getRequest()->get('new'));
        $curtainDesign->setPatternRepeatLength($this->getRequest()->get('patternrepeatlength'));
        $curtainDesign->setTapeSize($this->getRequest()->get('tapesize'));
        $curtainDesign->setUrlName($this->getRequest()->get('shortname'));
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->merge($curtainDesign);
        $em->flush();
        
        return $this->viewAction($id);   
    }
    
    public function createAction()
    {
        $curtainPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBand = $curtainPriceBandRepository->findOneById($this->getRequest()->get('priceband'));
            
        $curtainDesign = new CurtainDesign();

        $curtainDesign->setCurtainPriceBand($curtainPriceBand);
        $curtainDesign->setCushionFinish($this->getRequest()->get('cushionfinish'));
        $curtainDesign->setEyeletsAvailable($this->getRequest()->get('eyelets'));
        $curtainDesign->setFabricWidth($this->getRequest()->get('fabricwidth'));
        $curtainDesign->setFinish($this->getRequest()->get('curtainfinish'));
        $curtainDesign->setLined($this->getRequest()->get('lined'));
        $curtainDesign->setMaterials($this->getRequest()->get('materials'));
        $curtainDesign->setName($this->getRequest()->get('name'));
        $curtainDesign->setNew($this->getRequest()->get('new'));
        $curtainDesign->setPatternRepeatLength($this->getRequest()->get('patternrepeatlength'));
        $curtainDesign->setTapeSize($this->getRequest()->get('tapesize'));
        $curtainDesign->setUrlName($this->getRequest()->get('shortname'));

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($curtainDesign);
        $em->flush();

        return $this->viewAction($curtainDesign->getId());
    }
    
    
    
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainDesign');
        $curtainDesign = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($curtainDesign);
        $em->flush();
        
        return $this->indexAction();
    }
    
    
    
    
}
