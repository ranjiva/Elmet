<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainFabric;
use Symfony\Component\HttpFoundation\Response;

class FabricController extends Controller
{ 
    public function viewAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBands = $repository->findAll();        
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainFabric');
        $fabrics = $repository->findAll();
        
        return $this->render('ElmetAdminBundle:Fabric:index.html.twig', array('fabrics' => $fabrics, 'curtainPriceBands' => $curtainPriceBands));
            
    }
    
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainFabric');
        $fabric = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($fabric);
        $em->flush();
        
        return $this->viewAction();
    }
    
    public function updateAction()
    {
        
        if($this->getRequest()->get('save_new') != null){
            
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
            $curtainPriceBand = $repository->findOneById($this->getRequest()->get('priceband_new'));       
            
            $fabric = new CurtainFabric();
            
            $fabric->setCurtainPriceBand($curtainPriceBand);
            $fabric->setPricePerMetre($this->getRequest()->get('price_new'));
                        
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($fabric);
            $em->flush();
            
        } else {
            
            if ($this->getRequest()->get('submit_all') != null) {
                
                $fabricRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainFabric');
                $priceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                
                $fabrics = $fabricRepository->findAll();
                
                foreach($fabrics as $fabric) {
                   
                    $curtainPriceBand = $priceBandRepository->findOneById($this->getRequest()->get('all:priceband_'.$fabric->getId()));

                    $fabric->setCurtainPriceBand($curtainPriceBand);
                    $fabric->setPricePerMetre($this->getRequest()->get('all:price_'.$fabric->getId()));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($fabric);
                }
                
                $em->flush();
                
            } else {
            
                $params = $this->getRequest()->request->all();
                $keys = array_keys($params);
                $fabric_id = 0;

                foreach($keys as $key) {
                    $param = $params[$key];

                    if($param=='Save') {
                       $index = stripos($key,'_');
                       $fabric_id = substr($key,$index+1);
                    }
                }

                if ($fabric_id != 0) {

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainFabric');
                    $fabric = $repository->findOneById($fabric_id);              

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                    $curtainPriceBand = $repository->findOneById($this->getRequest()->get('single:priceband_'.$fabric_id));

                    $fabric->setCurtainPriceBand($curtainPriceBand);
                    $fabric->setPricePerMetre($this->getRequest()->get('single:price_'.$fabric_id));

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($fabric);
                    $em->flush();
                }
            }
            
        }
        
        return $this->viewAction();
    }
}
