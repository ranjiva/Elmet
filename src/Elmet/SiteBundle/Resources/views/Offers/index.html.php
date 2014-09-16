<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['slots']->set('title', 'Long, wide and bay window curtains, providing hard-to-get curtain sizes') ?>


<h1>Curtain Collection Offers</h1>

<h2>Elmet Curtains Now Offer A Free Drop Alterations Service On All Our Ready Made Curtains</h2>

<div class="curtain-nav">

<?php
    if ($pageNum > 1)
    {
        $previousPage = $pageNum -1;
        echo "<a HREF=\"/curtains/page/".$previousPage."\">&lt;</a>";
        echo "&nbsp";
    }
?>
    
<?php
    if ($numPages > 1) {
        for($i=1;$i <= $numPages;$i++)
        {   
            if ($i==$pageNum)
            {
                echo "<span class=\"current\">$i</span>";
            }
            else
            {
                echo "<span><a HREF=\"/curtains/page/".$i."\">$i</a></span>";
            }

            if ($i < $numPages)
                echo " | ";
        }
    }
?>

<?php
    if ($pageNum < $numPages)
    {
        $nextPage = $pageNum + 1;
        echo "&nbsp";
        echo "<a HREF=\"/curtains/page/".$nextPage."\">&gt;</a>";    
    }
?>
    
</div>

<div id="curtain-collection">

<?php

  foreach ($designs as $design)
  {
      echo "<div class=\"curtain-design\">";
      
      $curtainColours = $design->getCurtainColoursOnOffer();

      $firstColour = reset($curtainColours);
       
      echo "<span class=\"sale-curtain\"></span>";
      
      echo "<img class=\"window-dressing-img\" SRC=\"/img/products/".$firstColour->getThumbnailFilepath()."\"></img>";
      echo "<img class=\"swatch-img\" SRC=\"/img/products/".$firstColour->getSwatchFilepath()."\"></img>";
      
      echo "<h5 class=\"curtain-design-title\">";
      echo "<a HREF=\"/offers/select/".$design->getUrlName()."/".$firstColour->getName()."\">".$design->getName()."</a>";
      echo "<p id=\"out-of-stock\">Up To ".$design->getMaxDiscount()."% Off</p>"; 
      echo "</h5>";
      echo "</div>";
  }
  
?>

</div>

<div class="curtain-nav">

<?php
    if ($pageNum > 1)
    {
        $previousPage = $pageNum -1;
        echo "<a HREF=\"/curtains/page/".$previousPage."\">&lt;</a>";
        echo "&nbsp";
    }
?>
    
<?php
    
    if ($numPages > 1) {
        for($i=1;$i <= $numPages;$i++)
        {   
            if ($i==$pageNum)
            {
                echo "<span class=\"current\">$i</span>";
            }
            else
            {
                echo "<span><a HREF=\"/curtains/page/".$i."\">$i</a></span>";
            }

            if ($i < $numPages)
                echo " | ";
        }
    }
?>

<?php
    if ($pageNum < $numPages)
    {
        $nextPage = $pageNum + 1;
        echo "&nbsp";
        echo "<a HREF=\"/curtains/page/".$nextPage."\">&gt;</a>";    
    }
?>
    
</div>
