<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;

class OffersController extends BaseController
{
    var $curtains_on_a_page = 8;
    
    public function indexAction()
    {
         return $this->changeAction(1);
    }
    
    public function changeAction($number)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT DISTINCT cd FROM ElmetSiteBundle:CurtainDesign cd JOIN cd.curtain_colours cc WHERE (cc.on_offer = 1 and cc.in_stock = 1 and cc.display = 1) or cd.special_purchase = 1 ORDER BY cd.new DESC');
        $curtaindesigns = $query->getResult(); 
        
        if (count($curtaindesigns) == 0) {
            return $this->render('ElmetSiteBundle:Offers:index_no_offer.html.php',array('featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
        } else {
            
            $startIndex = ($number - 1) * $this->curtains_on_a_page;
            $endIndex = $number * $this->curtains_on_a_page -1;

            if ($endIndex > count($curtaindesigns)-1)
                $endIndex = count($curtaindesigns)-1;

            $designs = array();

            for ($i=$startIndex;$i <= $endIndex;$i++)
            {
                $designs[] = $curtaindesigns[$i];
            }

            $num_pages = floor(count($curtaindesigns) / $this->curtains_on_a_page);

            if (count($curtaindesigns) % $this->curtains_on_a_page > 0)
                $num_pages = $num_pages + 1;

            return $this->render('ElmetSiteBundle:Offers:index.html.php',array('pageNum' => $number, 'numPages' => $num_pages,'designs' => $designs,'featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
        }
    }
    
    public function selectAction($urlName, $colour)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneByNameJoinedToDesignByUrlName($colour,$urlName);
        $curtainDesign = $curtainColour->getCurtainDesign();
        $curtainPriceBand = $curtainDesign->getCurtainPriceBand();
        $curtainFabrics = $curtainPriceBand->getCurtainFabrics();
        $cushionCovers = $curtainPriceBand->getCushionCovers();
        $curtainPelmets = $curtainPriceBand->getCurtainPelmets();
        $curtainTieBacks = $curtainPriceBand->getCurtainTieBacks();
    
        $caravanCurtainExists = false; 

        foreach($curtainPriceBand->getCurtainPrices() as $curtainPrice) {
            if ($curtainPrice->getType() == "CaravanWindow") {
                $caravanCurtainExists  = true;
            }
        }
   
        $caravanDoorCurtainExists = false; 

        foreach($curtainPriceBand->getCurtainPrices() as $curtainPrice) {
            if ($curtainPrice->getType() == "CaravanDoor") {
                $caravanDoorCurtainExists  = true;
            } 
        }
    
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT cp FROM ElmetSiteBundle:CurtainPrice cp JOIN cp.curtain_price_band cpd WHERE cpd.id = :id ORDER BY cp.width ASC, cp.height asc');
        $query->setParameter('id',$curtainPriceBand->getId());
        $curtainPrices = $query->getResult(); 
        
        if ($curtainDesign->getSpecialPurchase() == 1) {
            $allColours = $curtainDesign->getOnDisplayCurtainColoursSortedByInStockAndPosition();
        } else {
            $allColours = $curtainDesign->getCurtainColoursOnOffer();
        }
        
        $origin = "OFFER_DISPLAY";
        
        if (($curtainColour->getOnOffer() == 0) || ($curtainColour->getInStock() == 0)) {
            return $this->render('ElmetSiteBundle:Curtains:detail.html.php',array('curtainDesign' => $curtainDesign, 'allColours' => $allColours, 'curtainPrices' => $curtainPrices , 'curtainPriceBand' => $curtainPriceBand, 'curtainColour' => $curtainColour, 'caravanWindowAvailable' => $caravanCurtainExists, 'caravanDoorAvailable' => $caravanDoorCurtainExists, 'featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems(),'origin' => $origin));
        } else {
            return $this->render('ElmetSiteBundle:Curtains:detail_offer.html.php',array('curtainDesign' => $curtainDesign, 'allColours' => $allColours, 'curtainPrices' => $curtainPrices , 'curtainPriceBand' => $curtainPriceBand, 'curtainColour' => $curtainColour, 'caravanWindowAvailable' => $caravanCurtainExists, 'caravanDoorAvailable' => $caravanDoorCurtainExists, 'featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems(), 'origin' => $origin));
        }  
    }
}

?>