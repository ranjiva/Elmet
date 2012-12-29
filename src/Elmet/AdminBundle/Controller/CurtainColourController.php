<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainColour;
use Symfony\Component\HttpFoundation\Response;

class CurtainColourController extends Controller
{    
    public function viewAction($id)
    {
         $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
         $curtainColour = $repository->findOneById($id);
         
         return $this->render('ElmetAdminBundle:CurtainColour:view.html.twig', array('curtainColour' => $curtainColour));
    }
    
    public function newAction($id)
    {
         return $this->render('ElmetAdminBundle:CurtainColour:new.html.twig',array('id' => $id));
    }
    
    public function updateAction()
    {
        $id = $this->getRequest()->get('id');
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneById($id);
       
        $curtainDesign = $curtainColour->getCurtainDesign();
        $urlName = $curtainDesign->getUrlName();
        
        $displayFile = $this->getRequest()->files->get('display');
        $swatchFile = $this->getRequest()->files->get('swatch');
        $thumbFile = $this->getRequest()->files->get('thumbnail');
        $fileRoot = $this->container->getParameter('fileroot');
        
        if ($displayFile != null) {
        
            $curtainColour->setFullFilepath($urlName."/".$displayFile->getClientOriginalName());
            $displayFile->move($fileRoot.$urlName,$displayFile->getClientOriginalName());
        }
        
        if ($swatchFile != null) {
        
            $curtainColour->setSwatchFilepath($urlName."/".$swatchFile->getClientOriginalName());
            $swatchFile->move($fileRoot.$urlName,$swatchFile->getClientOriginalName());
        }
        
        if ($thumbFile != null) {
        
            $curtainColour->setThumbnailFilepath($urlName."/".$thumbFile->getClientOriginalName());
            $thumbFile->move($fileRoot.$urlName,$thumbFile->getClientOriginalName());
        }
        
        $curtainColour->setBuynow($this->getRequest()->get('buynow'));
        $curtainColour->setInStock($this->getRequest()->get('instock'));
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->merge($curtainColour);
        $em->flush();
        
        return $this->forward('ElmetAdminBundle:CurtainDesign:view',array('id' => $curtainDesign->getId()));  
    }
    
    public function createAction()
    {
        $curtainPriceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBand = $curtainPriceBandRepository->findOneById($this->getRequest()->get('priceband'));
            
        $curtainDesign = new CurtainDesign();

        $curtainDesign->setCurtainPriceBand($curtainPriceBand);
        $curtainDesign->setCushionFinish($this->getRequest()->get('curtainfinish'));
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
