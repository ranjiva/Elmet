<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['slots']->set('title', 'Long, wide and bay window curtains, providing hard-to-get curtain sizes') ?>


<h1>Curtain Collections</h1>

<h2>Elmet Curtains Now Offer A Free Drop Alterations Service On All Our Ready Made Curtains</h2>
<p>All of our curtains are made in England and are available in widths up to 152" and lengths up to 108" drop. This makes it possible to use our curtains to dress most types of window, including bay windows.</p>

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
      
      $curtainColours = $design->getOnDisplayCurtainColoursSortedByInStockAndPosition();

      $firstColour = reset($curtainColours);
      
      if (!$design->getCurtainColoursOnOffer()) {
          $designOnOffer = false;
      } else {
          $designOnOffer = true;
      }
      
      
      if ($designOnOffer) {
            echo "<span class=\"sale-curtain\"></span>";
      } else {
            if ($design->getSpecialPurchase() == 1) {
                echo "<span class=\"special-curtain\"></span>";
            } else {
                if ($design->getNew() == '1')
                    echo "<span class=\"new-curtain\"></span>";
            }   
      }
      
      echo "<img class=\"window-dressing-img\" SRC=\"/img/products/".$firstColour->getThumbnailFilepath()."\"></img>";
      echo "<img class=\"swatch-img\" SRC=\"/img/products/".$firstColour->getSwatchFilepath()."\"></img>";
      
      echo "<h5 class=\"curtain-design-title\">";
      echo "<a HREF=\"/curtains/select/".$design->getUrlName()."/".$firstColour->getName()."\">".$design->getName()."</a>";
      
      if ($designOnOffer)
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
