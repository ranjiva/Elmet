<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Elmet\SiteBundle\Entity\CurtainDesign;
use Elmet\SiteBundle\Entity\CurtainColour;
use Elmet\SiteBundle\Entity\CurtainPriceBand;

class CurtainsController extends BaseController
{
    var $curtains_on_a_page = 8;
    
    /** * @codeCoverageIgnore */
    public function createAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBand = $repository->findOneByName('A');
        
        $curtainDesign = new CurtainDesign();
        
        $curtainDesign->setUrlName("elevetham");
        $curtainDesign->setName("elevetham");
        $curtainDesign->setMaterials("100% Silk");
        $curtainDesign->setTapeSize("3");
        $curtainDesign->setLined('1');
        $curtainDesign->setEyeletsAvailable(1);
        $curtainDesign->setFabricWidth(140);
        $curtainDesign->setPatternRepeatLength(8.00);
        $curtainDesign->setFinish("Fringed");
        $curtainDesign->setCushionFinish("Corded");
        $curtainDesign->setNew('1');
        $curtainDesign->setCurtainPriceBand($curtainPriceBand);
        
        $curtainColour = new CurtainColour();
        $curtainColour->setName("Black");
        $curtainColour->setCurtainDesign($curtainDesign);
        $curtainColour->setFullFilepath("/fullpath");
        $curtainColour->setSwatchFilepath("/swatchpath");
        $curtainColour->setThumbnailFilepath("/thumbnailpath");
        $curtainColour->setInStock(1);
        $curtainColour->setBuynow(1);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($curtainDesign);
        $em->persist($curtainColour);
        $em->flush();
        
        return new Response('Created curtain design id '.$curtainDesign->getId().' Created curtain colour id '.$curtainColour->getId());                       
    }
    
    /** * @codeCoverageIgnore */
    public function fetchAction($name)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainPriceBand');
        $curtainPriceBand = $repository->findOneByname($name);
        $designs = "";
        $cushionPrices = "";
        $tieBackPrices = "";
        $pelmetPrices = "";
        $fabricPrices = "";
        $curtainPrices = "";
        
        foreach ($curtainPriceBand->getCurtainDesigns() as $curtainDesign)
        {
            $designs = $designs." ".$curtainDesign->getName(); 
        }
        
        foreach ($curtainPriceBand->getCushionCovers() as $cushionCover)
        {
            $cushionPrices = $cushionPrices." ".$cushionCover->getPrice(); 
        }
        
        foreach ($curtainPriceBand->getCurtainTieBacks() as $curtainTieBack)
        {
            $tieBackPrices = $tieBackPrices." ".$curtainTieBack->getPrice(); 
        }
        
        foreach ($curtainPriceBand->getCurtainPelmets() as $curtainPelmet)
        {
            $pelmetPrices = $pelmetPrices." ".$curtainPelmet->getPrice(); 
        }
        
        foreach ($curtainPriceBand->getCurtainFabrics() as $curtainFabric)
        {
            $fabricPrices = $fabricPrices." ".$curtainFabric->getPricePerMetre(); 
        }
        
        foreach ($curtainPriceBand->getCurtainPrices() as $curtainPrice)
        {
            $curtainPrices = $curtainPrices." ".$curtainPrice->getPrice(); 
        }
        
        $response = 'Fetched curtain price band id '.$curtainPriceBand->getId();
        $response = $response.'<br/> Number of Designs '.count($curtainPriceBand->getCurtainDesigns()).' Designs: '.$designs;
        $response = $response.'<br/> Number of Cushion Covers '.count($curtainPriceBand->getCushionCovers()).' Prices: '.$cushionPrices;
        $response = $response.'<br/> Number of Curtain Tiebacks '.count($curtainPriceBand->getCurtainTieBacks()).' Prices: '.$tieBackPrices;
        $response = $response.'<br/> Number of Curtain Pelmets '.count($curtainPriceBand->getCurtainPelmets()).' Prices: '.$pelmetPrices;
        $response = $response.'<br/> Number of Curtain Fabrics '.count($curtainPriceBand->getCurtainFabrics()).' Prices: '.$fabricPrices;
        $response = $response.'<br/> Number of Curtain Prices '.count($curtainPriceBand->getCurtainPrices()).' Prices: '.$curtainPrices;
        
        return new Response($response);      
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
        
        return $this->render('ElmetSiteBundle:Curtains:detail.html.php',array('curtainDesign' => $curtainDesign, 'curtainPrices' => $curtainPrices , 'curtainPriceBand' => $curtainPriceBand, 'curtainColour' => $curtainColour, 'caravanWindowAvailable' => $caravanCurtainExists, 'caravanDoorAvailable' => $caravanDoorCurtainExists, 'featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
    
    public function closeupAction($urlName, $colour)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:CurtainColour');
        $curtainColour = $repository->findOneByNameJoinedToDesignByUrlName($colour,$urlName);
        $curtainDesign = $curtainColour->getCurtainDesign();
        $curtainPriceBand = $curtainDesign->getCurtainPriceBand();
            
        return $this->render('ElmetSiteBundle:Curtains:closeup.html.php',array('curtainDesign' => $curtainDesign, 'curtainPriceBand' => $curtainPriceBand, 'curtainColour' => $curtainColour, 'featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems())); 
    }
    
    public function indexAction()
    {
         return $this->changeAction(1);
    }
    
    public function changeAction($number)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT c FROM ElmetSiteBundle:CurtainDesign c ORDER BY c.new DESC');
        $curtaindesigns = $query->getResult(); 
          
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
        
        return $this->render('ElmetSiteBundle:Curtains:index.html.php',array('pageNum' => $number, 'numPages' => $num_pages,'designs' => $designs,'featured' => $this->getFeaturedTestimonials(),'numBasketItems' => $this->getNumBasketItems()));
    }
}

?>