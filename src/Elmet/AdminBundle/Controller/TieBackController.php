<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainTieBack;
use Elmet\AdminBundle\Entity\TieBackSize;
use Elmet\AdminBundle\Entity\TieBackType;
use Symfony\Component\HttpFoundation\Response;

class TieBackController extends Controller
{ 
    public function viewAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBands = $repository->findAll();        
        
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainTieBack');
        $tiebacks = $repository->findAll();
        
        $tiebackSizes = array(new TieBackSize(19),new TieBackSize(26),new TieBackSize(36));
        $tiebackTypes = array(new TieBackType("HomeWindow"),new TieBackType("CaravanWindow"));

        return $this->render('ElmetAdminBundle:TieBack:index.html.twig', array('tiebacks' => $tiebacks, 'curtainPriceBands' => $curtainPriceBands, 'tiebackSizes' => $tiebackSizes, 'tiebackTypes' => $tiebackTypes));
        
        
        }
    
    public function removeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainTieBack');
        $tieback = $repository->findOneById($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($tieback);
        $em->flush();
        
        return $this->viewAction();
    }
    
    public function updateAction()
    {
        
        if($this->getRequest()->get('save_new') != null){
            
            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
            $curtainPriceBand = $repository->findOneById($this->getRequest()->get('priceband_new'));       
            
            $tieback = new CurtainTieBack();
            
            $tieback->setCurtainPriceBand($curtainPriceBand);
            $tieback->setSize($this->getRequest()->get('size_new'));
            $tieback->setPrice($this->getRequest()->get('price_new'));
            $tieback->setType($this->getRequest()->get('type_new'));
                        
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($tieback);
            $em->flush();
            
        } else {
            
            if ($this->getRequest()->get('submit_all') != null) {
                
                $tiebackRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainTieBack');
                $priceBandRepository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                
                $tiebacks = $tiebackRepository->findAll();
                
                foreach($tiebacks as $tieback) {
                   
                    $curtainPriceBand = $priceBandRepository->findOneById($this->getRequest()->get('all:priceband_'.$tieback->getId()));

                    $tieback->setCurtainPriceBand($curtainPriceBand);
                    $tieback->setSize($this->getRequest()->get('all:size_'.$tieback->getId()));
                    $tieback->setPrice($this->getRequest()->get('all:price_'.$tieback->getId()));
                    $tieback->setType($this->getRequest()->get('all:type_'.$tieback->getId()));
                    
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($tieback);
                }
                
                $em->flush();
                
            } else {
            
                $params = $this->getRequest()->request->all();
                $keys = array_keys($params);
                $tieback_id = 0;

                foreach($keys as $key) {
                    $param = $params[$key];

                    if($param=='Save') {
                       $index = stripos($key,'_');
                       $tieback_id = substr($key,$index+1);
                    }
                }

                if ($tieback_id != 0) {

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainTieBack');
                    $tieback = $repository->findOneById($tieback_id);              

                    $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
                    $curtainPriceBand = $repository->findOneById($this->getRequest()->get('single:priceband_'.$tieback_id));

                    $tieback->setCurtainPriceBand($curtainPriceBand);
                    $tieback->setSize($this->getRequest()->get('single:size_'.$tieback_id));
                    $tieback->setPrice($this->getRequest()->get('single:price_'.$tieback_id));
                    $tieback->setType($this->getRequest()->get('single:type_'.$tieback_id));

                    $em = $this->getDoctrine()->getEntityManager();
                    $em->merge($tieback);
                    $em->flush();
                }
            }
            
        }
        
        return $this->viewAction();
    }
}
