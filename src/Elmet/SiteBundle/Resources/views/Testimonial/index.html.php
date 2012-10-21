<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['slots']->set('title', 'Customer Testimonials') ?>
      
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
