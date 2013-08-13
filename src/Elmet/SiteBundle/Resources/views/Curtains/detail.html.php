<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['slots']->set('title', 'Long, wide and bay window curtains, providing hard-to-get curtain sizes') ?>

<script language="JavaScript" type="text/javascript">

    function swatchCloseup()
    {

        var imageWindow;
        var swatchImagePath;

        imageWindow=window.open(<?php echo "'/curtains/closeup/".$curtainDesign->getUrlName()."/".$curtainColour->getName()."'"?>,'name','height=560,width=500');
        
        if (window.focus) {imageWindow.focus()}
    }

    $(document).ready(function(){
        
	 tooltip(".description","tooltip",15,-15);
    });

</script>

<h1>Curtains</h1>

<a name="product"></a>
<p class="sublink"><a HREF="/curtains">Back to Curtain Range</a></p>
<h2><?php echo $curtainDesign->getName() ?></h2>

<div id="content-top">
	<div id="images">
		<img width="270" SRC="/img/products/<?php echo $curtainColour->getFullFilePath()?>" alt="<?php echo $curtainDesign->getName() ?>"/>
		<a href="javascript:swatchCloseup()">
<img width="270" SRC="/img/products/<?php echo $curtainColour->getSwatchFilePath()?>" alt="<?php echo $curtainDesign->getName() ?>"/>
<p>Click on design pattern for close-up</p>
</a>
	</div>

	<div id="material">

	  
	  <h3>Material</h3>
          <?php
                if ($curtainColour->getInStock() == 0) 
                {
                    echo "<p id=\"out-of-stock\">Currently Out of Stock</p>";
                }
          ?>
	  <p><?php echo $curtainDesign->getMaterials() ?></p>
	</div>
	<div id="curtain-info">
		<h3>Details</h3>
		<ul>
		  <li>
                      <?php
                        if ($curtainDesign->getLined() ==1)
                        {
                            echo "<strong>FULLY LINED</strong> curtains";
                        }
                        else
                        {
                            echo "Curtains";
                        }
                      ?> 
                      with 3" tape
                      <?php
                        if ($curtainDesign->getEyeletsAvailable() ==1)
                        {
                            echo " or with eyelets";
                        }
                      ?>
                  </li>
		  <li>High quality, superb finish</li>
		  <li>Proudly made in the UK</li>
		  <li>Our extra wide sizes make ideal <strong>curtains for bay windows</strong></li>
                  <li><strong>Please note: </strong>All fabrics are dry clean only</li>
		  <li>Sample available on <a href="/customerservices/samples">request</a> (please send S.A.E.)</li>
                  <!--<li>The sizes displayed are for a <strong>single</strong> curtain.</li>
                  <li>The prices displayed are for a <strong>pair</strong> of curtains.</li>-->
                  <li>We offer a free drop alteration service<img src="/img/information.png" class="description" title="<p>To take advantage of our free drop alteration service, please select curtains from the standard sizes below, choosing a standard size with a drop greater than that which you require. Once you add your curtains to your basket, you will have the opportunity to specify the length to which the drop should be shortened.</p><p>Please note that if you select more than one curtain of the same size and you only require some of the curtains to have the drop altered, or require each to have a different drop, then please add the curtains to your basket one at a time. This will allow you to then specify a different drop for each curtain</p><p>Please note that curtains supplied with their drops altered, are viewed by us as being made to measure. We recommend that you read our refund policy on made to measure curtains in our Customer Services section before placing your order</p>"></li>
		</ul>
	</div>
	<div id="colours">
		<h3>Colours</h3>
		<table><tr>
                   <?php
                    foreach ($curtainDesign->getSortedCurtainColoursByIdInStock() as $colour) {
                        
                        echo "<td><p><strong>".$colour->getName()."</strong></p>";
                        echo "<a href=\"/curtains/select/".$curtainDesign->getUrlName()."/".$colour->getName()."\">";
                        
                        if ($colour->getName() == $curtainColour->getName()) {
                            echo "<img height=\"95\" border=\"3\" style=\"background-color: rgb(244, 244, 227);\" alt=\"".$curtainColour->getName()."\" src=\"/img/products/".$colour->getThumbnailFilepath()."\"/></a>";
                        } else {
                            echo "<img height=\"95\" border=\"0\" style=\"background-color: rgb(244, 244, 227);\" alt=\"".$curtainColour->getName()."\" src=\"/img/products/".$colour->getThumbnailFilepath()."\"/></a>";
                        }
                        
                        echo "</td>";                
                    }
                   ?>
		</table>
	</div>


          <!--
                      <a href="/order/" class="ebay"><img width="208" height="52" border="0" alt="order now" src="/img/spacer.gif"/></a>
                    -->

</div>

<form controller="order" method="post" action="/order/add/<?php echo $curtainDesign->getUrlName()."/".$curtainColour->getName()?>"><fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset>
<div id="content-bottom">
  <div class="curtain-pricelist">
	<h3>Home Window Curtains<img src="/img/information.png" title="<p>All our ready made curtains are priced per pair. The sizes stated are for the WIDTH X DROP of EACH curtain and there are two curtains of this size in each pack.</p><p>Our curtains are dispatched ungathered and the sizes stated are for an ungathered curtain.  Depending on the amount of gather required, we recommend that the length of an individual curtain be longer than 3/4 of the length of the track/pole that it is hung from and not more than the length of the track/pole. The length excludes that of any decorative ends or finials.</p><p>Please hover over the sizes displayed to get information on suitable track/pole lengths for a pair of curtains of that size.</p><p>Please see our curtain measuring guide in our Customer Services section, for more information on how to measure your curtain width and drop.</p>" class="description"></h3>	  
        <div class="curtain-accessories">
                  <h2>Home Accessories</h2>
                  <div class="pelmet">
                  <h3>Pelmets</h3>
                  
                  <?php
                    
                    $i=0;
                  
                    foreach($curtainPriceBand->getCurtainPelmets() as $curtainPelmet) {
                        echo "<div class=\"accessory-item\">\n";
                        echo "<p><strong>".$curtainPelmet->getSize()."\"</strong> (".$curtainDesign->getFinish().") £".$curtainPelmet->getPrice()." each</p>\n";
                        echo "<div class=\"input text\">\n";
                        echo "<input name=\"pelmet_".$i."\" type=\"text\" value=\"0\"/>\n";
                        echo "</div>\n";
                        echo "</div>\n";
                        
                        $i = $i+1;
                    
                    }
                        
                  ?>
                  
                  </div>

                  <div class="tiebacks">
                  <h3>Tie-backs</h3>

                  <?php
                    
                    $i=0;
                  
                    foreach($curtainPriceBand->getCurtainTieBacks() as $curtainTieBack) {
                        
                        if ($curtainTieBack->getType() == 'HomeWindow') {
                            
                            echo "<div class=\"accessory-item\">\n";
                            echo "<p><strong>".$curtainTieBack->getSize()."\"</strong> (".$curtainDesign->getFinish().") £".$curtainTieBack->getPrice()." each</p>\n";
                            echo "<div class=\"input text\">\n";
                            echo "<input name=\"home_tieback_".$i."\" type=\"text\" value=\"0\"/>\n";
                            echo "</div>\n";
                            echo "</div>\n";
                            
                            $i = $i+1;
                        }
                        
                    
                    }
                        
                  ?>

                  </div>

                  <div class="cushion-covers">
                  <h3>Cushion Covers</h3>

                  <?php
                    
                    $i=0;
                  
                    foreach($curtainPriceBand->getCushionCovers() as $cushionCover) {
                              
                            echo "<div class=\"accessory-item\">\n";
                            echo "<p><strong>".$cushionCover->getSize()."</strong> (".$curtainDesign->getCushionFinish().") £".$cushionCover->getPrice()." each</p>\n";
                            echo "<div class=\"input text\">\n";
                            echo "<input name=\"cushion_".$i."\" type=\"text\" value=\"0\"/>\n";
                            echo "</div>\n";
                            echo "</div>\n";
                        
                        $i = $i+1;
                    
                    }
                        
                  ?>
                  
		  </div>
                  </div>



	  <div style="clear:right" class="curtain-accessories curtain-fabric">

            <h2>Fabric Only</h2>

              <div class="pelmet">

                  <h3>Now Available By The Metre</h3>

                  
                        <div class="accessory-item fabric-item">

                        <table cols="2">

                          <tr class="row">
                            <td class="accessory-heading">Width</td>
                            <td class="accessory-value"><?php echo $curtainDesign->getFabricWidth()?> cm</td>
                          </tr>
                          <tr class="row">
                            <td class="accessory-heading">Pattern Repeat</td>
                            <td class="accessory-value"><?php echo $curtainDesign->getPatternRepeatLength()?> cm</td>
                          </tr>
                          <tr>
                            <td class="accessory-heading">Price per Metre</td>
                            <td class="accessory-value">£<?php echo $curtainPriceBand->getCurtainFabrics()->first()->getPricePerMetre()?></td>
                          </tr>
                          <tr>
                            <td class="accessory-heading">Total</td>
                            <td class="accessory-value">
                              <div class="input text">
                                  <input name="fabric" type="text" value="0"/></div><p> metres</p>
                            </td>
                          </tr>

                        </table>

<!--
                        <p>
                        Width <strong></strong><br/>
                        Pattern Repeat 0.00 cm<br/>
                        £8.50 per metre
                        </p>

                        <input type="hidden" name="data[5][Product][type]" value="Fabric" id="5ProductType" /><input type="hidden" name="data[5][Product][id]" value="1" id="5ProductId" /><div class="input text"><label for="5ProductQuantity"></label><input name="data[5][Product][quantity]" type="text" value="0" id="5ProductQuantity" /></div>-->

                        </div>

                        
          </div>

        </div>



        <div id="fabric-addtobasket" class="addtobasket" style="float:right;">
          <div class="submit"><input type="submit" class="add-to-basket" value="Add to Basket" /></div>        </div>



	

	<table cellpadding="0" class="sizes" cellspacing="0">
	<tbody>

		<tr bgcolor="#f3f3f3">
		  <th class="table_hd1"><strong>Width&nbsp;x&nbsp;Drop</strong></th>
		  <th class="table_hd2"><strong>3"&nbsp;Tape (pair)</strong></th>
		  <th class="table_hd3"><strong>Quantity</strong></th>
                  
                  <?php
                    if ($curtainDesign->getEyeletsAvailable() == 1) {
                        echo "<th class=\"table_hd2\"><strong>Eyelets (pair)<strong></th>\n";
                        echo "<th class=\"table_hd3\"><strong>Quantity<strong></th>\n";
                    }
                  ?>   
                </tr>
                
                <?php
                
                    $i=0;
                    
                    $trackLengthDescriptions = array(
                                    "52.0" => "Suitable for a track/pole that has a length between 52&quot and 72&quot (132cm - 184cm). This excludes the length of any decorative ends/finials.",
                                    "76.0" => "Suitable for a track/pole that has a length between 73&quot and 102&quot (185cm - 260cm). This excludes the length of any decorative ends/finials.",
                                    "104.0" => "Suitable for a track/pole that has a length between 103&quot and 139&quot (261cm - 353cm). This excludes the length of any decorative ends/finials.",
                                    "132.0" => "Suitable for a track/pole that has a length between 132&quot and 176&quot (335cm - 447cm). This excludes the length of any decorative ends/finials.",
                                    "152.0" => "Suitable for a track/pole that has a length between 152&quot and 203&quot (386cm - 515cm). This excludes the length of any decorative ends/finials.");
                    
                    foreach($curtainPrices as $curtainPrice) {
                            
                        if ($curtainPrice->getType() == 'HomeWindow') {
                            
                            $description = $trackLengthDescriptions["".$curtainPrice->getWidth()];
                            
                            echo "<tr>\n";
                            echo "<td title=\"".$description."\" class=\"description\"><strong>".$curtainPrice->getSize()."</strong></td>\n";
                            echo "<td>£".$curtainPrice->getPrice()." per pair</td>\n";
                            echo "<td align=\"center\"><div class=\"input text\"><input name=\"home_tape_curtain_".$i."\" type=\"text\" value=\"0\"/></div></td>\n";
                            
                            if ($curtainDesign->getEyeletsAvailable() == 1) {
                            
                                $price = $curtainPrice->getPrice() + $curtainPrice->getCurtainEyeletPriceBand()->getPrice();
                                
                                echo "<td>£".$price." per pair</td>\n";
                                echo "<td align=\"center\"><div class=\"input text\"><input name=\"home_eyelet_curtain_".$i."\" type=\"text\" value=\"0\"/></div></td>\n";
                                
                            }
                            
                            echo "</tr>\n";
                            
                            $i = $i + 1;
                        }
                        
                    }
                
                ?>	
	</tbody>
	</table>       
  </div>
<br/><br/>

<?php
    if ($caravanWindowAvailable == true) {
        
        echo "<div class=\"curtain-pricelist\">\n";
        echo "<h3>Caravan Window Curtains</h3>\n";
        echo "<div class=\"curtain-accessories\">\n";
        echo "<div class=\"tiebacks\">\n";
        echo "<h3>Tie-backs</h3>\n";
        
        $i=0;
                  
        foreach($curtainPriceBand->getCurtainTieBacks() as $curtainTieBack) {
                        
           if ($curtainTieBack->getType() == 'CaravanWindow') {
                            
               echo "<div class=\"accessory-item\">\n";
               echo "<p><strong>".$curtainTieBack->getSize()."\"</strong> (Plain) £".$curtainTieBack->getPrice()." each</p>\n";
               echo "<div class=\"input text\">\n";
               echo "<input name=\"caravan_tieback_".$i."\" type=\"text\" value=\"0\"/>\n";
               echo "</div>\n";
               echo "</div>\n";
               
               $i = $i+1;
           }
           
           
                    
        }
        
        echo "</div>\n";
        echo "</div>\n";
        
        echo "<table width=\"255\" cellpadding=\"0\" class=\"sizes\" celspacing=\"0\">";
        echo "<tbody>";
        echo "<tr bgcolor=\"#f3f3f3\">";
        echo "<th class=\"table_hd1\"><strong>Width x Drop</strong></th>";          
        echo "<th class=\"table_hd2\"><strong>Per Pair</strong></th>";
        echo "<th class=\"table_hd3\"><strong>Quantity</strong></th>";
        echo "</tr>";                  
        
        $i=0;
                    
        foreach($curtainPrices as $curtainPrice) {
        
            if ($curtainPrice->getType() == 'CaravanWindow') {
                
                echo "<tr>\n";
                echo "<td><strong>".$curtainPrice->getSize()."</strong></td>\n";
                echo "<td>£".$curtainPrice->getPrice()." per pair</td>\n";
                echo "<td align=\"center\"><div class=\"input text\"><input name=\"caravan_window_curtain_".$i."\" type=\"text\" value=\"0\"/></div></td>\n";
                echo "</tr>\n";
                
                $i = $i+1;
            }
            
            
        }             
        
        echo "</tbody>\n";
        echo "</table>\n";
        echo "</div>\n";
        echo "<br/><br/>";
    }
   
?>

<?php
    if ($caravanDoorAvailable == true) {
        
        echo "<div class=\"curtain-pricelist\">\n";
        echo "<h3>Caravan Door Curtains</h3>\n";
        echo "<table width=\"255\" cellpadding=\"0\" class=\"sizes\" celspacing=\"0\">\n";
        echo "<tbody>\n";
        echo "<tr bgcolor=\"#f3f3f3\">\n";
        echo "<th class=\"table_hd1\"><strong>Width x Drop</strong></th>";
        echo "<th class=\"table_hd2\"><strong>Each</strong></th>";
        echo "<th class=\"table_hd3\"><strong>Quantity</strong></th>";
        
        $i = 0;
        
        foreach($curtainPrices as $curtainPrice) {
        
            if ($curtainPrice->getType() == 'CaravanDoor') {
                
                echo "<tr>\n";
                echo "<td><strong>".$curtainPrice->getSize()."</strong></td>\n";
                echo "<td>£".$curtainPrice->getPrice()." per pair</td>\n";
                echo "<td align=\"center\"><div class=\"input text\"><input name=\"caravan_door_curtain_".$i."\" type=\"text\" value=\"0\"/></div></td>\n";
                echo "</tr>\n";
                
                $i = $i+1;
            }
            
            
        }
        
        echo "</tbody>\n";
        echo "</table>\n";
        echo "</div>\n";
        echo "<br/>";
    }

?>

<div class="addtobasket">
  <div class="submit"><input type="submit" class="add-to-basket" value="Add to Basket" /></div></form></div>

<br /><br /><br />
		<p><strong>Please Note:</strong> All errors and omissions exempt</p>

		<br/>

		<p><a HREF="/curtains">Back to Curtain Range</a></p>

</div>        