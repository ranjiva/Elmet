<?php if ($errors): $error = $errors[0];?>
    
    <div class="error-message">
        <?php echo $view['translator']->trans(
                $error->getMessageTemplate(),
                $error->getMessageParameters(),
                'validators') ?>
    </div>
<?php endif ?>
