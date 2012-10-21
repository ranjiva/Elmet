<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainPelmet;
use Elmet\AdminBundle\Entity\PelmetSize;
use Symfony\Component\HttpFoundation\Response;

class PelmetController extends Controller
{ 
    public function viewAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBands = $repository->findAll();        
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPelmet');
        $pelmets = $repository->findAll();
        
        $pelmetSizes = array(new PelmetSize(138), new PelmetSize(230), new PelmetSize(322));

        return $this->render('ElmetAdminBundle:Pelmet:index.html.twig', array('pelmets' => $pelmets, 'curtainPriceBands' => $curtainPriceBands, 'pelmetSizes' => $pelmetSizes));
    }
    
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPelmet');
        $pelmet = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($pelmet);
        $em->flush();
        
        return $this->viewAction();
    }
    
    public function updateAction()
    {
        
        if($this->getRequest()->get('save_new') != null){
            
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
            $curtainPriceBand = $repository->findOneById($this->getRequest()->get('priceband_new'));       
            
            $pelmet = new CurtainPelmet();
            
            $pelmet->setCurtainPriceBand($curtainPriceBand);
            $pelmet->setSize($this->getRequest()->get('size_new'));
            $pelmet->setPrice($this->getRequest()->get('price_new'));
                        
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($pelmet);
            $em->flush();
            
        } else {
            
            if ($this->getRequest()->get('submit_all') != null) {
                
                $pelmetRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPelmet');
                $priceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                
                $pelmets = $pelmetRepository->findAll();
                
                foreach($pelmets as $pelmet) {
                   
                    $curtainPriceBand = $priceBandRepository->findOneById($this->getRequest()->get('all:priceband_'.$pelmet->getId()));

                    $pelmet->setCurtainPriceBand($curtainPriceBand);
                    $pelmet->setSize($this->getRequest()->get('all:size_'.$pelmet->getId()));
                    $pelmet->setPrice($this->getRequest()->get('all:price_'.$pelmet->getId()));

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($pelmet);
                }
                
                $em->flush();
                
            } else {
            
                $params = $this->getRequest()->request->all();
                $keys = array_keys($params);
                $pelmet_id = 0;

                foreach($keys as $key) {
                    $param = $params[$key];

                    if($param=='Save') {
                       $index = stripos($key,'_');
                       $pelmet_id = substr($key,$index+1);
                    }
                }

                if ($pelmet_id != 0) {

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPelmet');
                    $pelmet = $repository->findOneById($pelmet_id);              

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                    $curtainPriceBand = $repository->findOneById($this->getRequest()->get('single:priceband_'.$pelmet_id));

                    $pelmet->setCurtainPriceBand($curtainPriceBand);
                    $pelmet->setSize($this->getRequest()->get('single:size_'.$pelmet_id));
                    $pelmet->setPrice($this->getRequest()->get('single:price_'.$pelmet_id));

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($pelmet);
                    $em->flush();
                }
            }
            
        }
        
        return $this->viewAction();
        
        
    }
}
