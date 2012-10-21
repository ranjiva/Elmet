<?php $view['form']->setTheme($form, array('ElmetSiteBundle:Form')) ;?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Long and Wide Curtain Offers</title>
<meta name="description" content="Extra long, extra wide and bay window curtains, supplying hard-to-get sizes for curtains sizes"/><meta name="keywords" content="long curtains, wide curtains, bay window curtains"/>
<link href="/css/elmet.css" rel="stylesheet" type="text/css" />
<link href="/css/nav.css" rel="stylesheet" type="text/css" />
<link href="/css/type.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon"> 
<script type="text/javascript" src="/js/swfobject.js"></script>

<!--[if lt IE 7]>
<style type="text/css">

  .curtain-design span { behavior: url(/css/iepngfix.htc); }

</style>
<![endif]-->

</head>

<body>

        <div id="top">
                <div id="top-inner">
                        <img src="/img/logo.gif" alt="elmet curtains" class="logo"/>
                        <img src="/img/tagline.gif" alt="curtains and bedding" class="tagline" />
                </div>
        </div>
        <div id="container">
                <div id="homecontent">

<!--  REMOVE FLASH HEADER
                        <div id="homeflashcontent">
                                <img src="/img/main-image.jpg" alt="Elmet Curtains" />
                        </div>
                        <script type="text/javascript">
                                var so = new SWFObject("/img/anim3.swf", "animation", "743", "187", "8", "#ffffff");
                                so.write("homeflashcontent");
                         </script>
						 <div id="homefeature">
                                <h2>FREE DELIVERY </h2>
                                <p><br />Order now and receive free delivery when you spend &pound;150 or more.<br /></p>
                                <p class="but"><a href="/curtains/">View Curtains</a></p>
                		</div>
-->

<h1>Elmet Curtains &ndash; Famous for Extra Long & Extra Wide Ready Made Curtains</h1>
<div style="width:875px; margin:0 0 0 55px;">
	<p class="spec1">Are you looking for curtains to fit an extra long or an extra wide window?</p>
	<p  class="spec2">Elmet Curtains manufacture and supply extra wide and extra long curtains, available to order from a ready-made stock.</p>
	<div class="spec3">
	  <p class="spec4">In stock and available<br />
	  to order now:</p>
		<div class="spec5">
			<p class="spec6">Curtain Widths</p>
			<p class="spec7"> from 46&rdquo; upto 152&rdquo; ! </p>
		</div>
		<div class="spec8">
		  <p class="spec9">Curtain Drops</p>
			<p class="spec10">from 54&rdquo; upto 108&rdquo; !</p>
		</div>
  	  <p class="spec11">
            <strong><br/><br/>Made-to-measure service available<br/><br/>Free drop alterations service on all our ready made curtains
            </strong>
          </p>

  	  <p id="bedding-range-link"><a href="/beddings"><strong>View our bedding range which includes a wide selection of hard-to-get sizes</strong></a></p>

	</div>
	<div class="spec12">
		<a href="/curtains/select/geneva/Natural"><img src="/img/geneva.jpg" alt="geneva" border="0" /></a>
		<p class="spec13">Geneva</p>
  </div>
	<div class="spec14">
		<div class="spec12"><a href="/curtains/select/toledo/Black"><img src="/img/toledo.jpg" alt="toledo" width="105" height="207" border="0" /></a>
			<p class="spec15">Toledo</p>
      </div>
		<div class="spec12"><a href="/curtains/select/liberty/Red"><img src="/img/liberty.jpg" alt="liberty" width="105" height="207" border="0" /></a>
			<p class="spec15">Liberty</p>
	    </div>
		<div class="spec12"><a href="/curtains/select/chantelle/Lt.Brown"><img src="/img/chantelle.jpg" alt="chantelle" width="105" height="207" border="0" /></a>
			<p class="spec15">Chantelle</p>
      </div>
		<div class="spec16">
			<a href="/curtains/select/nicole/Pink"><img src="/img/nicole.jpg" alt="nicole" width="105" height="207" border="0" /></a>
			<p class="spec15">Nicole</p>
      </div>
		<div class="spec17">
			<p class="spec18"><a href="/curtains">View full collection</a></p>
		</div>
	</div>
	<div class="spec19">
		<a href="/curtains/select/concerto/Silk"><img src="/img/concerto.jpg" alt="concerto" width="146" height="286" border="0" /></a>
		<p style="font:14pt Arial, Helvetica, sans-serif; color:#fff; text-align:center; margin:10px;">Concerto</p>
  </div>
  
	<div class="spec20 clearfix">
	  <p class="spec21">Each design is available in a range of colours</p>
		<p class="spec22">Matching pelmets, tie-backs and cushion-covers</p>
		<p class="spec23">Made-to-measure service also available</p>
	</div>

		<div id="spec-off">
		<p class="spec24"><strong>Updates &amp; Special Offers</strong></p>
    	<div class="left">
        <p>Elmet Curtains introduce new designs and special offers throughout the year.  Be sure to bookmark our site <a href="http://www.elmetcurtains.co.uk">www.elmetcurtains.co.uk</a> and visit us again to view our latest offers. </p>
    	<p>If you would like us to inform you of special offers and new products register your email address here:</p>
        </div>
        <div class="right">
          <a name="subscription-form"></a>

          <form method="post" action="/p">      
              <div class="input text">
                <?php echo $view['form']->label($form['name']) ?>
                <?php echo $view['form']->widget($form['name']) ?>
                <?php echo $view['form']->errors($form['name']) ?>
              </div>
              <div class="input text required">
                 <?php echo $view['form']->label($form['email']) ?>
                 <?php echo $view['form']->widget($form['email']) ?>
                 <?php echo $view['form']->errors($form['email']) ?>
              </div>
              <p id="confirmation"></p>
              <div>
                  <div class="submit">
                      <input type="submit" value="Subscribe" />
                  </div>
              </div>
          </form>
          <p style="float:right; font-size:0.6em;">
              <a href="/customerservices#privacy" target="_blank">View our privacy policy</a>
          </p>
        
             
     	</div>
    </div>
</div>

                <div id="base">
                        <ul id="bottom-nav">
                                <li class="selected"><a href="/">Home</a></li>

                                <li><a href="/curtains">Curtains</a></li>
                                <li><a href="/beddings">Bedding</a></li>
                                <li><a href="/offers">Special Offers</a></li>
                                <li><a href="/order">Order Now</a></li>
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

</body>
</html>

