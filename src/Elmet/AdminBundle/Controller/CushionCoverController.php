<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CushionCover;
use Elmet\AdminBundle\Entity\CushionCoverSize;
use Symfony\Component\HttpFoundation\Response;

class CushioncoverController extends Controller
{ 
    public function viewAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBands = $repository->findAll();        
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CushionCover');
        $cushionCovers = $repository->findAll();
        
        $cushionCoverSizes = array(new CushionCoverSize("18\" X 18\""));

        return $this->render('ElmetAdminBundle:CushionCover:index.html.twig', array('cushionCovers' => $cushionCovers, 'curtainPriceBands' => $curtainPriceBands, 'cushionCoverSizes' => $cushionCoverSizes));
    }
    
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CushionCover');
        $cushionCover = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($cushionCover);
        $em->flush();
        
        return $this->viewAction();
    }
    
    public function updateAction()
    {
        
        if($this->getRequest()->get('save_new') != null){
            
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
            $curtainPriceBand = $repository->findOneById($this->getRequest()->get('priceband_new'));       
            
            $cushionCover = new CushionCover();
            
            $cushionCover->setCurtainPriceBand($curtainPriceBand);
            $cushionCover->setSize($this->getRequest()->get('size_new'));
            $cushionCover->setPrice($this->getRequest()->get('price_new'));
            $cushionCover->setFinish("");
                        
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($cushionCover);
            $em->flush();
            
        } else {
            
            if ($this->getRequest()->get('submit_all') != null) {
                
                $ccRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CushionCover');
                $priceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                
                $cushionCovers = $ccRepository->findAll();
                
                foreach($cushionCovers as $cushionCover) {
                   
                    $curtainPriceBand = $priceBandRepository->findOneById($this->getRequest()->get('all:priceband_'.$cushionCover->getId()));

                    $cushionCover->setCurtainPriceBand($curtainPriceBand);
                    $cushionCover->setSize($this->getRequest()->get('all:size_'.$cushionCover->getId()));
                    $cushionCover->setPrice($this->getRequest()->get('all:price_'.$cushionCover->getId()));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($cushionCover);
                }
                
                $em->flush();
                
            } else {
            
                $params = $this->getRequest()->request->all();
                $keys = array_keys($params);
                $cc_id = 0;

                foreach($keys as $key) {
                    $param = $params[$key];

                    if($param=='Save') {
                       $index = stripos($key,'_');
                       $cc_id = substr($key,$index+1);
                    }
                }

                if ($cc_id != 0) {

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CushionCover');
                    $cushionCover = $repository->findOneById($cc_id);              

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                    $curtainPriceBand = $repository->findOneById($this->getRequest()->get('single:priceband_'.$cc_id));

                    $cushionCover->setCurtainPriceBand($curtainPriceBand);
                    $cushionCover->setSize($this->getRequest()->get('single:size_'.$cc_id));
                    $cushionCover->setPrice($this->getRequest()->get('single:price_'.$cc_id));

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($cushionCover);
                    $em->flush();
                }
            }
            
        }
        
        return $this->viewAction();
    }
}
