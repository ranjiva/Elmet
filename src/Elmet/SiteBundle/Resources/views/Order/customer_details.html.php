<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['slots']->set('title', 'Long, wide and bay window curtains, providing hard-to-get curtain sizes') ?>

     <h1>Your Details</h1>

<div id="order-form">
  <h2>Contact Details</h2>
  <div id="order-form-left-column">

  <form method="post" action="<?php echo $view->container->parameters['paypal_url']?>" method="post"><fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset>
  <input type="hidden" name="business" value="<?php echo $view->container->parameters['paypal_business']?>" />
  <input type="hidden" name="cmd" value="_xclick" />
  <input type="hidden" name="type" value="paynow" />
  <input type="hidden" value="<?php echo $order->getAmountPaid()?>" name="amount"/>
  <input type="hidden" value="<?php echo $view->container->parameters['paypal_item_name']?>" name="item_name"/>
  <input type="hidden" value="GBP" name="currency_code"/>
  <input type="hidden" value="GB" name="lc"/>
  <input type="hidden" value="44" name="night_phone_a"/>
  <input type="hidden" value="<?php echo $order->getId()?>" name="custom"/>
  <input type="hidden" name="notify_url" value="<?php echo $view->container->parameters['paypal_notify_url']?>" />
  <input type="hidden" name="return" value="<?php echo $view->container->parameters['paypal_return']?>" />

  <div class="input text"><label for="first_name">First Name</label><input name="first_name" type="text" value="" id="first_name" /></div>  <div class="input text"><label for="last_name">Last Name</label><input name="last_name" type="text" value="" id="last_name" /></div>  <div class="input text"><label for="email">Email</label><input name="email" type="text" value="" id="email" /></div>  <div class="input text"><label for="telephone">Telephone</label><input name="night_phone_b" type="text" value="" id="telephone" /></div><div class="clr"></div>
<br/><br/>

  <h2>Billing & Delivery</h2>

  <div class="input text"><label for="address1">Address</label><input name="address1" type="text" value="" id="address1" /></div>  <div class="input text"><label for="address2"></label><input name="address2" type="text" value="" id="address2" /></div>  <div class="input text"><label for="city">Town/City</label><input name="city" type="text" value="" id="city" /></div>  <div class="input text"><label for="zip">Post Code</label><input name="zip" type="text" value="" id="zip" /></div><div class="clr"></div>
<br/><br/>

<div id="card-box">
	<div id="card-boxHD1">How To Pay</div>
    <div>
      <div class="btn-using-owncard"><a href="/order/card?height=510&width=720" class="thickbox" title="Using Your Card"><img alt="" src="/img/btn-using-owncard.png" border="0" onmouseover="rollOverImage(this, '/img/btn-using-owncard_ov.png', event);" onmouseout="rollOverImage(this, '/img/btn-using-owncard.png', event);" /></a></div>
      <div class="btn-or">OR</div>
      <div class="btn-using-paypal"><a href="/order/paypal?height=510&width=720" class="thickbox" title="Using Your PayPal Account"><img alt="" src="/img/btn-using-paypal.png" border="0" onmouseover="rollOverImage(this, '/img/btn-using-paypal_ov.png', event);" onmouseout="rollOverImage(this, '/img/btn-using-paypal.png', event);" /></a></div>
      <div class="clr"></div>
    </div>
    <div id="card-box-left">
      <div><img alt="" src="/img/card1.png" border="0" /></div>
    </div>
    <div id="card-box-right">
    <div><img alt="" src="/img/paypal1.png" border="0" /></div>
    </div>
    <div class="clr"></div>
	<div id="card-boxHD">All Major Credit and Debit Cards Accepted</div>
  </div>

</div>

<!-- Cart Summary-->

  <div id="order-form-right-column">
  <div id="cartsummary">
  <div><strong>Your Cart Summary</strong></div>
  <div class="summ_total" style="border-bottom:1px solid #e5e5e5;">
  	<div style="padding-left:15px; padding-bottom:3px;"><strong><?php echo $numBasketItems;?> Items</strong></div>
    <div style="clear:both;"></div>
  </div>
  <div class="summ_subtotal">
    <div class="summ_total_title"><strong>Sub Total</strong></div>
    <div class="summ_total_price">&pound;<?php echo $order->getOrderTotal(); ?></div>
    <div style="clear:both;"></div>
  </div>
  <div class="summ_subtotal">
  	<div class="summ_total_title"><strong>Delivery</strong></div>
    <div class="summ_total_price">&pound;<?php echo $order->getDeliveryCharge(); ?></div>
    <div style="clear:both;"></div>
	</div>
  <div class="summ_total">
  	<div class="summ_total_title"><strong>Total</strong></div>
    <div class="summ_total_price">&pound;<?php echo $order->getAmountPaid(); ?></div>
    <div style="clear:both;"></div>
  </div>
  <div style="padding-top:10px;"><a href="/order/view" class="view-cart"><img width="114" height="36" border="0" alt="Go to Curtains" src="/img/spacer.gif"/></a></div>
</div>
<div class="submit"><input type="submit" class="pay-button-new" value="Place Order" /></div><div class="clr"></div>
<!--<a class="btn-needhelp" href="/order/help/"><img width="245" height="72" border="0" alt="" src="/img/spacer.gif"/></a>-->
  </div>

<!-- Cart Summary END-->

  <!--<div id="order-form-right-column">
  <div class="input textarea"><label for="OrderNotes">Additional Information (optional)</label><textarea name="data[Order][notes]" cols="50" rows="10" id="OrderNotes" ></textarea></div>  </div>-->

  <a name="confirmation"></a>


  <!--<div class="submit"><input type="submit" class="pay-button" value="Place Order" /></div>-->

  
  </form>
</div>
