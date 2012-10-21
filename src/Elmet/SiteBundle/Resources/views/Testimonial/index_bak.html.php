<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Customer Testimonials</title>
<meta name="description" content="Extra long, extra wide and bay window curtains, supplying hard-to-get sizes for curtains sizes"/><meta name="keywords" content="long curtains, wide curtains, bay window curtains"/>

<link href="../css/elmet.css" rel="stylesheet" type="text/css" />
<link href="../css/nav.css" rel="stylesheet" type="text/css" />
<link href="../css/type.css" rel="stylesheet" type="text/css" />
<link rel="icon" HREF="../img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" HREF="../img/favicon.ico" type="image/x-icon"> 
<script type="text/javascript" src="../js/swfobject.js"></script>


<script type="text/javascript" src="../js/global.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/thickbox.js"></script>
<link href="../css/thickbox.css" rel="stylesheet" type="text/css" />


<!--[if lt IE 7]>
<style type="text/css">

  .curtain-design span { behavior: url(/css/iepngfix.htc); }

</style>
<![endif]-->


</head>

<body>
        <div id="top">
                <div id="top-inner"><div id="cart_mini"><strong>Your Cart</strong>
  <div id="cart-view-items"><a HREF="../order/order_form/index.htm">View Items</a></div><div id="cart-items"><strong>0 Items</strong></div>
</div>                        <img SRC="../img/logo.gif" alt="elmet curtains" class="logo"/>
                        <img SRC="../img/tagline.gif" alt="curtains and bedding" class="tagline" />

                </div>
        </div>
        <div id="nav">
                <div id="nav-inner">
                        <ul class="navigation">
                                <li class="selected"><a HREF="/">Home</a></li>
                                <li><a HREF="/curtains">Curtains</a></li>
                                <li><a HREF="../beddings/index.htm">Bedding</a></li>
                                <li><a HREF="../offers.htm">Special Offers</a></li>
                                <li><a HREF="../pages/customer-services.htm">Customer Services</a></li>
                                <li><a HREF="/contact">Contact Us</a></li>
                        </ul>
                </div>
        </div>
        <div id="container">
                <div id="content">
                        <div id="flashcontent">
                                <img SRC="../img/main-image.jpg" alt="Elmet Curtains" />
                        </div>
                        <script type="text/javascript">
                                var so = new SWFObject("../img/anim3.swf", "animation", "743", "187", "8", "#ffffff");
                                so.write("flashcontent");
                         </script>

      <h1>Customer Testimonials</h1>

<h2>Here's what some of our customers have to say...</h2>


    
<?php 
foreach ($testimonials as $testimonial) {

    echo "<div class=\"testimonial\">\n";
    echo "<p class=\"quote\"><span class=\"quote-mark\">&#8220;</span>";
    echo $testimonial->getTestimonial();
    echo "\n";
    echo "<span class=\"quote-mark\">&#8221;</span></p>\n";
    echo "<p class=\"customer-details\">";
    echo $testimonial->getCustomerDetails();
    echo "</p></div>";
     
    }
  ?>
      </div>
          <div id="right">
                        <div id="feature">
                                <h2>Special Offers</h2>
                                <p>Find a bargain by browsing our products that are currently on special offer.</p>
                                <p class="but"><a HREF="../offers/index.htm">View Special Offers</a></p>
                </div>
                    <a HREF="../beddings.htm"><img SRC="../img/bedding.jpg" alt="bedding" border="0" class="cat-link"/></a>
<!--                    <a href="/testimonials"><img src="/img/testimonial.jpg" alt="customer reviews" border="0" class="cat-link"/></a> -->


		    <div class="featured-testimonial">

    <p class="quote"><span class="quote-mark">&#8220;</span>Just wanted to say thank you for the lovely curtains that arrived today.  It is fantastic to be able to get curtains that actually fit my bay windows properly at a fraction of the cost of having them specially made.  The curtains are lovely, good quality and thank you for altering to the right length free of charge.  What more can I say other than I will be back to buy more for my other rooms.<span class="quote-mark">&#8221;</span></p>
    <p class="customer-details">Miss M Goodson</p>

		    </div>

                    <a HREF=""><img SRC="../img/customer-testimonials.jpg" alt="customer comments" border="0" class="testimonials-link"/></a>


                        <div id="services">
                                <h2>Customer Services</h2>
                                <ul>
                                        <li><a HREF="">customer testimonials</a></li>
                                        <li><a HREF="../pages/customer-services.htm#delivery">delivery information</a></li>
                                        <li><a HREF="../pages/customer-services.htm#returns">returns policy</a></li>
                                        <li><a HREF="../pages/customer-services.htm#privacy">privacy policy</a></li>
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
                                <li class="selected"><a HREF="../index.htm">Home</a></li>
                                <li><a HREF="../curtains/index.htm">Curtains</a></li>
                                <li><a HREF="../beddings/index.htm">Bedding</a></li>
                                <li><a HREF="../offers.htm">Special Offers</a></li>
                                <li><a HREF="../pages/customer-services.htm">Customer Services</a></li>
                                <li><a HREF="../contact.htm">Contact Us</a></li>
                        </ul>
                        <p class="copyright">Copyright 2007-2011 Elmet Curtains</p>
                         <div class="pws">
                                <p>Website Design by</p>
                                <a HREF="../../www.practicalwebsolutions.co.uk/" target="_blank"><img SRC="../img/pws.gif" alt="pws" border="0" /></a> </div>
                         </div>
                </div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "../../www./");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-1418202-17");
pageTracker._initData();
pageTracker._trackPageview();
</script>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "../../www./");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-4166912-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>

</body>
</html>
