<?php $view->extend('ElmetSiteBundle::layout.html.php') ?>
<?php $view['form']->setTheme($form, array('ElmetSiteBundle:Form')) ;?>
<?php $view['slots']->set('title', 'Contact Elmet Curtains') ?>

<h1>Contact Us</h1>

<h2>Contact Elmet Directly:</h2>

<div id="address">
  <p><b>Address:</b><br />
  Swan Lane No. 3 Mill<br />
  Higher Swan Lane Mill<br />
  Bolton<br />
  BL3 3BJ</p>
  <!--<p><b>Tel:</b> 01204 657 000</p>-->
  <p><b>Email:</b> <a href="mailto:enquiries@elmetcurtains.co.uk">enquiries@elmetcurtains.co.uk</a></p>
</div>

<div id="contact-form">
  <h2>Contact Form</h2>

  
  <form action="/contact" method="post">
    <div class="input text">
        <?php echo $view['form']->label($form['name']) ?>
        <?php echo $view['form']->widget($form['name']) ?>
        <?php echo $view['form']->errors($form['name']) ?>
    </div>
    <div class="input text">
        <?php echo $view['form']->label($form['tel']) ?>
        <?php echo $view['form']->widget($form['tel']) ?>
        <?php echo $view['form']->errors($form['tel']) ?>
    </div>  
    <div class="input text">
        <?php echo $view['form']->label($form['email']) ?>
        <?php echo $view['form']->widget($form['email']) ?>
        <?php echo $view['form']->errors($form['email']) ?>
    </div>  
    <div class="input textarea">
        <?php echo $view['form']->label($form['enquiry']) ?>
        <?php echo $view['form']->widget($form['enquiry']) ?>
        <?php echo $view['form']->errors($form['enquiry']) ?>
    </div>
    <div class="submit">
        <input type="submit" name="Send" value="Send" />
    </div>  
  </form>
</div>