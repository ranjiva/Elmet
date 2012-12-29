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
         $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainDesign');
         $curtainDesign = $repository->findOneById($id);
        
         return $this->render('ElmetAdminBundle:CurtainColour:new.html.twig',array('curtainDesign' => $curtainDesign));
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
