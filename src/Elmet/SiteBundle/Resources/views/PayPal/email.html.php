
 <html>
    <head>
        <title>Order</title>
    </head>
 <body>
 <div id="email" style="width:723px; margin:0px auto; padding:23px 10px 10px 10px; background-color:#a8b758; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
   <div><img src="<?php echo $view->container->parameters['webroot']?>/img/logo.jpg" alt="" width="280" height="32" /><img src="<?php echo $view->container->parameters['webroot']?>/img/topright.jpg" alt="" width="407" height="32" /></div>
   
  
  <div style="padding-top:34px; text-align:center; font-size:16px; font-weight:bold; height:24px; color:#FFF">::&nbsp;&nbsp;Customer Order&nbsp;&nbsp;::</div>
   <div style="background-color:#FFF; padding:15px;"><font face="Arial, Helvetica, sans-serif" style="font-size:16px; color:#6b7a1f;"><strong><u>Customer Details</u>:</strong></font>
     <div style="padding-top:10px; padding-bottom:10px; line-height:20px;"><strong>First Name</strong>: <?php echo $order->getFirstName()?><br />
       <strong>Last Name</strong>: <?php echo $order->getLastName()?><br />
       <strong>Email</strong>: <?php echo $order->getEmail()?><br />
       <strong>Telephone</strong>: </div>
     <font face="Arial, Helvetica, sans-serif" style="font-size:16px; color:#6b7a1f;"><strong><u>Billing &amp; Delivery</u>:</strong></font>
     <div style="padding-top:10px; padding-bottom:10px; line-height:20px;"><strong>Address</strong>: <?php echo $order->getDeliveryAddress()?>,  <br />
       <strong>Town/City</strong>: <?php echo $order->getDeliveryTown()?><br />
     <strong>Post Code</strong>: <?php echo $order->getDeliveryPostcode()?></div>
   <font face="Arial, Helvetica, sans-serif" style="font-size:16px; color:#6b7a1f;"><strong><u><br>
   Basket</u>:</strong></font>
 
    <div id="item_headings" style="border-bottom:1px solid #e5e5e5; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-top:10px; padding-bottom:3px; margin-bottom:15px;">
     <div id="order_item" style="float:left; width:305px; margin-right:20px;"><strong>Item</strong></div>
     <div id="order_dropattraction" style="float:left; width:100px; margin-right:20px;"><strong>Drop Alteration?<br />
     <span style="color:#6b7a1f; font-size:11px;">Free Service!</span></strong><span class="greenText"></span></div>
     <div id="order_quantity" style="float:left; width:60px; margin-right:15px;"><strong>Quantity</strong></div>
     <div id="order_price" style="float:left; width:50px; margin-right:20px;"><strong>Price</strong></div>
     <div id="order_sub_total" style="float:left; width:90px;"><strong>Sub Total</strong></div>
     <div style="clear:both;"></div>
  </div>
 
   
 <?php
    
    foreach($order->getOrderItems() as $orderItem) {
        
        echo "<div id=\"item_headings\" style=\"border-bottom:1px solid #e5e5e5; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-bottom:3px; margin-bottom:15px;\">\n";
            echo "<div id=\"order_item\" style=\"float:left; width:305px; margin-right:20px;\">";
            echo "<div class=\"item_img\" style=\"float:left; margin-right:10px; padding-bottom:10px;\">";
            echo "<img src=\"".$view->container->parameters['webroot'].$orderItem->getItemFilepath()."\"width=\"47\" height=\"61\" alt=\"\" />";
            echo "</div>";
 
            echo "<div class=\"item_details\" style=\"float:left;\"><strong>".$orderItem->getName()."</strong><br />";
            echo $orderItem->getSize()." ".$orderItem->getDescription()."<br/>";
            
            if ($orderItem->getProductType() == 'Fabric') {
                echo $orderItem->getColour().":".$orderItem->getFabricQuantity()."<br/>";
            } else {
                echo $orderItem->getColour().":".$orderItem->getQuantity()."<br/>";
            }
            
            echo "</div>\n";
            echo "</div>\n";
            
            echo "<div id=\"order_dropattraction\" style=\"float:left; width:100px; margin-right:20px;\">";
            
            if ($orderItem->getDropAlteration() == null) {
                echo "n/a";
            } else {
                echo $orderItem->getDropAlteration(); 
            }
            
            echo "</div>\n";
            
            echo "<div id=\"order_quantity\" style=\"float:left; width:60px; margin-right:15px;\">";
            if ($orderItem->getProductType() == 'Fabric') {
                echo $orderItem->getFabricQuantity()."<br/>";
            } else {
                echo $orderItem->getQuantity()."<br/>";
            }
            echo "</div>\n";
            
            echo "<div id=\"order_price\" style=\"float:left; width:50px; margin-right:20px;\">";
            echo $orderItem->getPrice();
            echo "</div>\n";
     
            echo "<div id=\"order_sub_total\" style=\"float:left; width:90px;\">";
            echo $orderItem->getSubTotal();
            echo "</div>\n";
            echo "<div style=\"clear:both;\"></div>";
    }
 
 ?>
        
   <div id="order_totals" style="float:right; width:210px; font-family:Arial, Helvetica, sans-serif; margin-top:-15px;">
    <div id="subtotal" style="border-bottom:1px solid #e5e5e5; font-size:12px; padding-bottom:8px; padding-top:8px;">
    <div style="float:left; margin-left:30px; width:80px;"><strong>Sub Total</strong></div>
    <div style="float:right; width:95px;">&pound;<?php echo $order->getOrderTotal()?></div>
    <div style="clear:both;"></div>
  </div>
  <div id="delivery_total" style="border-bottom:1px solid #e5e5e5; font-size:12px; padding-bottom:8px; padding-top:8px;">
           <div style="float:left; margin-left:30px; width:80px;"><strong>Delivery</strong></div>
     <div style="float:right; width:95px;">&pound;<?php echo $order->getDeliveryCharge()?></div>
     <div style="clear:both;"></div>
         </div>
   <div id="total" style="padding-top:8px;">
           <div style="float:left; margin-left:30px; width:80px; font-size:16px;"><strong>Total</strong></div>
     <div style="float:right; width:95px; font-size:16px;">&pound;<?php echo $order->getAmountPaid()?></div>
     <div style="clear:both;"></div>
   </div>
 </div>
       <div style="clear:both; padding-top:15px;"><strong>Order status: </strong><?php echo $order->getOrderStatus()?></div>
   </div>
 

</div> 
</body></html>