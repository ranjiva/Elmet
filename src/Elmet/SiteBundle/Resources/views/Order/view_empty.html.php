<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['slots']->set('title', 'Long, wide and bay window curtains, providing hard-to-get curtain sizes') ?>

<div style="float:left;">
  <h1>Basket (0 items)</h1>
</div>

<div style="clear:left;"></div>

<div class="order_headings">
        <div class="order_item"><strong>Item</strong></div>
        <div class="order_dropattraction"><strong>Drop Alteration?<br />
        <span class="heading-highlight">Free Service!</span></strong></div>
        <div class="order_quantity">&nbsp;</div>
		<div class="order_quantity"><strong>Quantity</strong></div>
        <div class="order_price"><strong>Price</strong></div>
        <div class="order_sub_total"><strong>Sub Total</strong></div>
        <div style="clear:both;"></div>
      </div>


<p id="empty-cart-msg">Your cart is currently empty</p>

<div id="order_totals">
  <div class="subtotal">
    <div class="total_title"><strong>Sub Total</strong></div>
    <div class="total_price">&pound;0.00</div>
    <div style="clear:both;"></div>
  </div>
  <div class="subtotal">
  	<div class="total_title"><strong>Delivery</strong></div>
    <div class="total_price">&pound;0.00</div>
    <div style="clear:both;"></div>
	</div>
  <div class="total">
  	<div class="total_title"><strong>Total</strong></div>
    <div class="total_price">&pound;0.00</div>
    <div style="clear:both;"></div>
  </div>
</div>


<div id="basket_bottom">
        <div id="continue_shopping">Continue Shopping<br />
          <br />
          <a href="/curtains" class="go-to-curtains"><img width="208" height="52" border="0" alt="Go to Curtains" src="/img/spacer.gif"/></a>
          <a href="/beddings" class="go-to-bedding"><img width="208" height="52" border="0" alt="Go to Beddings" src="/img/spacer.gif"/></a>
        </div>
        <div style="clear:both;"></div>
</div>
