<?php

namespace Elmet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elmet\SiteBundle\Entity\CurtainDesign;
use Symfony\Component\HttpFoundation\Response;

class CurtainStockController extends Controller
{ 
    public function viewAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT cc FROM ElmetSiteBundle:CurtainColour cc JOIN cc.curtain_design cd ORDER BY cd.url_name ASC');
        
        $curtainColours = $query->getResult();      
         
        return $this->render('ElmetAdminBundle:CurtainStock:view.html.twig', array('curtainColours' => $curtainColours));
    }
    
    public function updateAction()
    {
        if ($this->getRequest()->get('submit_all') != null) {

            $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');

            $curtainColours = $repository->findAll();

            foreach($curtainColours as $curtainColour) {
                
                $stock = $this->getRequest()->get('all:stock_'.$curtainColour->getId());
                
                if ($stock == null) {
                    $curtainColour->setAvailableStock(0.0);
                } else { 
                    $curtainColour->setAvailableStock($stock);
                }
                
                $curtainColour->setInStock($this->getRequest()->get('all:instock_'.$curtainColour->getId()));
                
                $em = $this->getDoctrine()->getEntityManager();
                $em->merge($curtainColour);
            }

            $em->flush();

        } else {

            $params = $this->getRequest()->request->all();
            $keys = array_keys($params);
            $curtainColour_id = 0;

            foreach($keys as $key) {
                $param = $params[$key];

                if($param=='Save') {
                   $index = stripos($key,'_');
                   $curtainColour_id = substr($key,$index+1);
                }
            }

            if ($curtainColour_id != 0) {

                $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
                $curtainColour = $repository->findOneById($curtainColour_id);              
                
                $stock = $this->getRequest()->get('single:stock_'.$curtainColour->getId());
                
                if ($stock == null) {
                    $curtainColour->setAvailableStock(0.0);
                } else { 
                    $curtainColour->setAvailableStock($stock);
                }
                
                $curtainColour->setInStock($this->getRequest()->get('single:instock_'.$curtainColour->getId()));
                
                $em = $this->getDoctrine()->getEntityManager();
                $em->merge($curtainColour);
                $em->flush();
            }
        }
        
        return $this->viewAction();
    }
}
