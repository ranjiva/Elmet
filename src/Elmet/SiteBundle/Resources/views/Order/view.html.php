<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['slots']->set('title', 'Long, wide and bay window curtains, providing hard-to-get curtain sizes') ?>

<div style="float:left;">
  <h1>Basket (<?php echo count($order->getOrderItems()); ?> items)</h1>
</div>

<div id="empty_cart">
    <a HREF="/order/empty" id="empty_cart" class="common_link" onclick="return confirm(&#039;Are you sure you would like to empty the cart?&#039;);">Empty Cart</a>
</div>

<div style="clear:left;"></div>

<div class="order_headings">
    <div class="order_item">
        <strong>Item</strong>
    </div>
    <div class="order_dropattraction">
        <strong>
            Drop Alteration?<br/>
            <span class="heading-highlight">Free Service!</span>
        </strong>
    </div>
    <div class="order_quantity">&nbsp;</div>
    <div class="order_quantity"><strong>Quantity</strong></div>
    <div class="order_price"><strong>Price</strong></div>
    <div class="order_sub_total"><strong>Sub Total</strong></div>
    <div style="clear:both;"></div>
</div>


<form id="CurtainOrderFormForm" method="post" action="/order/submit">
    <?php 
    
        $i = 0;
        
        foreach ($order->getOrderItems() as $orderItem) {
                   
            echo "<div class=\"order_headings\">\n";
            echo "<div class=\"order_item\">\n";
            echo "<div class=\"item_img\"><img src=\"".$orderItem->getItemFilepath()."\"></div>\n";   
            echo "<div class=\"item_details\"><strong>".$orderItem->getName()."</strong><br/>\n";
            echo $orderItem->getSize()." ".$orderItem->getDescription()."<br/>\n";
            echo $orderItem->getColour().":".$orderItem->getQuantity()." <br/>\n";
            if ($orderItem->getCurtainColour()->getInStock() == 1)
                echo "<strong><span class=\"cart-item-in-stock\">In Stock</span></strong>\n";
            else
                echo "<strong><span class=\"cart-item-out-stock\">Out of Stock</span></strong>\n";
            echo "</div>\n</div>\n";
            echo "<div class=\"order_dropattraction\">\n";
            
            if ($orderItem->getProductType() == "Curtain")
            {
                echo "<input type=\"text\" name=\"item_".$i."\" value=\"".$orderItem->getDropAlteration()."\">\n";
                echo "<div>&nbsp;&nbsp;Inches</div>\n";
            }
            else
                echo "<div class=\"blank-drop-alteration\">&nbsp;</div>\n";
            
            echo "</div>\n";
            echo "<div class=\"order_price1\">\n";
            echo "<input type=\"image\" src=\"/img/update.jpg\"/>\n";
            echo "</div>\n";
           
            echo "<div class=\"order_quantity\">".$orderItem->getQuantity()."<br/></div>\n"; 
            echo "<div class=\"order_price1\">".$orderItem->getPrice()."</div>\n";
            echo "<div class=\"order_sub_total1\">".$orderItem->getSubTotal()."</div>\n";
            echo "<div class=\"order_price1\" style=\"margin-right:0px;\">\n";
            echo "<a href=\"/order/remove/".$i."_".$orderItem->getColour().":".$orderItem->getQuantity().":".$orderItem->getSize()."\" class=\"common_link\" onclick=\"return confirm(&#039;Are you sure you would like to remove this item?&#039;);\">Remove</a>\n"; 
            echo "</div>\n";
            echo "<div style=\"clear:both;\"></div>\n";
            echo "</div>\n";
            
            $i = $i+1;
        }
     ?>   
      
     <div id="order_totals">
         <div class="subtotal">
            <div class="total_title">
                <strong>Sub Total</strong>
            </div>
            <div class="total_price">
                 &pound;<?php echo $order->getOrderTotal();?>
            </div>
            <div style="clear:both;"></div>
        </div>
        <div class="subtotal">
            <div class="total_title"><strong>Delivery</strong></div>
                <div class="total_price">
                    &pound;<?php echo $order->getDeliveryCharge(); ?>
                </div>
            <div style="clear:both;"></div>
	</div>
        <div class="total">
            <div class="total_title">
                <strong>Total</strong>
            </div>
            <div class="total_price">
                &pound;<?php echo $order->getAmountPaid(); ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>


<div id="basket_bottom">
        <div id="continue_shopping">Continue Shopping<br />
          <br />
          <a href="/curtains" class="go-to-curtains"><img width="208" height="52" border="0" alt="Go to Curtains" src="/img/spacer.gif"/></a>
          <a href="/beddings" class="go-to-bedding"><img width="208" height="52" border="0" alt="Go to Beddings" src="/img/spacer.gif"/></a>
        </div>
        <div id="complete_order">Complete Order<br />
          <br />
        <input type="submit" label="complete" name="confirm" value="confirm" class="confirm-and-pay" />
        </div>
        <div style="clear:both;"></div></div>
</form>
















