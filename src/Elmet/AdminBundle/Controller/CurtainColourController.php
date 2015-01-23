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
                 
         $curtainDesign = $curtainColour->getCurtainDesign();
         
         $onlyColourOnDisplay = FALSE;
         
         if ($curtainColour->getDisplay() == 1) {
             if (count($curtainDesign->getCurtainColoursOnDisplay()) == 1) {
                 $onlyColourOnDisplay = TRUE;
             }
         }
         
         return $this->render('ElmetAdminBundle:CurtainColour:view.html.twig', array('curtainColour' => $curtainColour, 'onlyColourOnDisplay' => $onlyColourOnDisplay));
    }
    
    public function newAction($id)
    {
         $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainDesign');
         $curtainDesign = $repository->findOneById($id);
        
         if (count($curtainDesign->getCurtainColours()) == 0) {
             $firstColour = TRUE;
         } else {
             $firstColour = FALSE;
         }
         
         return $this->render('ElmetAdminBundle:CurtainColour:new.html.twig',array('curtainDesign' => $curtainDesign, 'firstColour' => $firstColour));
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
       
        $curtainColour->setName($this->getRequest()->get('name'));
        $curtainColour->setBuynow($this->getRequest()->get('buynow'));
        $curtainColour->setInStock($this->getRequest()->get('instock'));
        
        $availableStock = $this->getRequest()->get('stock');
        
        if ($availableStock == null) {
            $curtainColour->setAvailableStock(0.00);
        } else {
            $curtainColour->setAvailableStock($availableStock);
        }
        
        if ($this->getRequest()->get('onDisplay') == null) {
            $curtainColour->setDisplay(1);
        } else {   
            $curtainColour->setDisplay($this->getRequest()->get('onDisplay'));
        }
        
        $curtainColour->setOnOffer($this->getRequest()->get('onoffer'));
        
        if ($this->getRequest()->get('onoffer') == 1)
            $curtainColour->setDiscountPercentage($this->getRequest()->get('discount'));
        else
            $curtainColour->setDiscountPercentage(0);
        
        $curtainColour->setPosition($this->getRequest()->get('position'));
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->merge($curtainColour);
        $em->flush();
        
        return $this->forward('ElmetAdminBundle:CurtainDesign:view',array('id' => $curtainDesign->getId()));  
    }
    
    public function createAction()
    {
        $id = $this->getRequest()->get('id');
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainDesign');
        $curtainDesign = $repository->findOneById($id);
        $curtainColour = new CurtainColour();
        
        $urlName = $curtainDesign->getUrlName();
        
        $displayFile = $this->getRequest()->files->get('display');
        $swatchFile = $this->getRequest()->files->get('swatch');
        $thumbFile = $this->getRequest()->files->get('thumbnail');
        $fileRoot = $this->container->getParameter('fileroot');
        $noImageFile = $this->container->getParameter('no_image_filename');
        
        if ($displayFile != null) {
        
            $curtainColour->setFullFilepath($urlName."/".$displayFile->getClientOriginalName());
            $displayFile->move($fileRoot.$urlName,$displayFile->getClientOriginalName());
        } else {
            $curtainColour->setFullFilepath($noImageFile);
        }
        
        if ($swatchFile != null) {
        
            $curtainColour->setSwatchFilepath($urlName."/".$swatchFile->getClientOriginalName());
            $swatchFile->move($fileRoot.$urlName,$swatchFile->getClientOriginalName());
        } else {
            $curtainColour->setSwatchFilepath($noImageFile);
        }
        
        if ($thumbFile != null) {
        
            $curtainColour->setThumbnailFilepath($urlName."/".$thumbFile->getClientOriginalName());
            $thumbFile->move($fileRoot.$urlName,$thumbFile->getClientOriginalName());
        } else {
            $curtainColour->setThumbnailFilepath($noImageFile);
        }
        
        $curtainColour->setName($this->getRequest()->get('name'));
        $curtainColour->setBuynow($this->getRequest()->get('buynow'));
        $curtainColour->setInStock($this->getRequest()->get('instock'));
        $curtainColour->setCurtainDesign($curtainDesign);
        
        $availableStock = $this->getRequest()->get('stock');
        
        if ($availableStock == null) {
            $curtainColour->setAvailableStock(0.00);
        } else {
            $curtainColour->setAvailableStock($availableStock);
        }
        
        if ($this->getRequest()->get('onDisplay') == null) {
            $curtainColour->setDisplay(1);
        } else {   
            $curtainColour->setDisplay($this->getRequest()->get('onDisplay'));
        }
        
        $curtainColour->setOnOffer($this->getRequest()->get('onoffer'));
        
        if ($this->getRequest()->get('onoffer') == 1)
            $curtainColour->setDiscountPercentage($this->getRequest()->get('discount'));
        else
            $curtainColour->setDiscountPercentage(0);
        
        $curtainColour->setPosition($this->getRequest()->get('position'));
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($curtainColour);
        $em->flush();
        
        return $this->forward('ElmetAdminBundle:CurtainDesign:view',array('id' => $id));  
    }
 
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneById($id);
        $curtainDesign = $curtainColour->getCurtainDesign();
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($curtainColour);
        $em->flush();
        
        return $this->forward('ElmetAdminBundle:CurtainDesign:view',array('id' => $curtainDesign->getId()));
    }
    
    
    
    
}
