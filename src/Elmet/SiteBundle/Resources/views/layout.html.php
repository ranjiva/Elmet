<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title><?php $view['slots']->output('title', 'Home')?></title>
<?php echo "<meta name=\"description\" content=\""?>
<?php $view['slots']->output('description','Extra long, extra wide and bay window curtains, supplying hard-to-get sizes for curtains sizes')?>
<?php echo "\"/>"?>
<?php echo "<meta name=\"keywords\" content=\""?>
<?php $view['slots']->output('keywords','long curtains, wide curtains, bay window curtains')?>
<?php echo "\"/>"?>

<link href="/css/elmet.css" rel="stylesheet" type="text/css" />
<link href="/css/nav.css" rel="stylesheet" type="text/css" />
<link href="/css/type.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon"> 
<script type="text/javascript" src="/js/swfobject.js"></script>

<script type="text/javascript" src="/js/global.js"></script>
<script type="text/javascript" src="/js/jquery.js"></script> 
<script type="text/javascript" src="/js/thickbox.js"></script>
<link href="/css/thickbox.css" rel="stylesheet" type="text/css" />


<!--[if lt IE 7]>
<style type="text/css">

  .curtain-design span { behavior: url(/css/iepngfix.htc); }

</style>
<![endif]-->


</head>

<body>
        <div id="top">
                <div id="top-inner">
                    <div id="cart_mini">
                       <strong>Your Cart</strong>
                        <div id="cart-view-items">
                            <a HREF="/order/view">View Items</a>
                        </div>
                        <div id="cart-items">
                            <strong>
                                <?php echo $numBasketItems?> Items
                            </strong>
                        </div>
                    </div>
                    <img src="/img/logo.gif" alt="elmet curtains" class="logo"/>
                    <img src="/img/tagline.gif" alt="curtains and bedding" class="tagline" />
                </div>
        </div>
        <div id="nav">
                <div id="nav-inner">
                        <ul class="navigation">
                                <li class="selected"><a href="/">Home</a></li>
                                <li><a href="/curtains">Curtains</a></li>
                                <li><a href="/beddings">Bedding</a></li>
                                <li><a href="/offers">Special Offers</a></li>
                                <li><a href="/customerservices">Customer Services</a></li>
                                <li><a href="/contact">Contact Us</a></li>
                        </ul>
                </div>
        </div>
        <div id="container">
                <div id="content">
                        <div id="flashcontent">
                                <img src="/img/main-image.jpg" alt="Elmet Curtains" />
                        </div>
                        <script type="text/javascript">
                                var so = new SWFObject("/img/anim3.swf", "animation", "743", "187", "8", "#ffffff");
                                so.write("flashcontent");
                         </script>

      <?php $view['slots']->output('_content') ?>

      </div>
          <div id="right">
                        <div id="feature">
                                <h2>Special Offers</h2>
                                <p>Find a bargain by browsing our products that are currently on special offer.</p>
                                <p class="but"><a href="/offers">View Special Offers</a></p>
                </div>
                    <a href="/beddings"><img src="/img/bedding.jpg" alt="bedding" border="0" class="cat-link"/></a>
<!--                    <a href="/testimonials"><img src="/img/testimonial.jpg" alt="customer reviews" border="0" class="cat-link"/></a> -->


                    
		    <div class="featured-testimonial">

                    <?php
                    
                    echo "<p class=\"quote\"><span class=\"quote-mark\">&#8220;</span>";
                    echo $featured->getTestimonial();
                    echo "\n";
                    echo "<span class=\"quote-mark\">&#8221;</span></p>\n";
                    echo "<p class=\"customer-details\">";
                    echo $featured->getCustomerDetails();
                    echo "</p>";
                    
                    ?>
                    
		    </div>

                    <a href="/testimonial"><img src="/img/customer-testimonials.jpg" alt="customer comments" border="0" class="testimonials-link"/></a>


                        <div id="services">
                                <h2>Customer Services</h2>
                                <ul>
                                        <li><a href="/testimonial">customer testimonials</a></li>
                                        <li><a href="/customerservices#delivery">delivery information</a></li>
                                        <li><a href="/customerservices#returns">returns policy</a></li>
                                        <li><a href="/customerservices#privacy">privacy policy</a></li>
                                </ul>
                        </div>
			<!--
                        <div class="ebayshop">
                                <a href="/pages/shop"><img src="/img/ebay-shop.gif" alt="visit our rbay shop" border="0" /></a>
                        </div>
			-->
                </div>
                <div id="base">
                        <ul id="bottom-nav">
                                <li class="selected"><a href="/">Home</a></li>
                                <li><a href="/curtains">Curtains</a></li>
                                <li><a href="/beddings">Bedding</a></li>
                                <li><a href="/offers">Special Offers</a></li>
                                <li><a href="/customerservices">Customer Services</a></li>
                                <li><a href="/contact">Contact Us</a></li>
                        </ul>
                        <p class="copyright">Copyright <?php echo "2007-".date("Y"); ?> Elmet Curtains</p>
                         
                         </div>
                </div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-1418202-17");
pageTracker._initData();
pageTracker._trackPageview();
</script>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4166912-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>

</body>
</html>