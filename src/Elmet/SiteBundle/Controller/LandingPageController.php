<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;
use Elmet\SiteBundle\Entity\Registration;

class LandingPageController extends BaseController
{
    public function indexAction()
    {   
        $registration = new Registration();
        
        $form = $this->createFormBuilder($registration)
                ->add('name', 'text')
                ->add('email', 'email')
                ->getForm();
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') 
        {
            $form->bindRequest($request);
        
            $validator = $this->get('validator');
            $errors = $validator->validate($registration);
            
            if (count($errors) ==0) 
            {
                $this->sendEmail($registration);
            
                $registration = new Registration();
        
                $form = $this->createFormBuilder($registration)
                    ->add('name', 'text')
                    ->add('email', 'email')
                    ->getForm();
            }
        }
        
        return $this->render('ElmetSiteBundle:LandingPage:index.html.php',array('form' => $form->createView()));
        
    }
    
    public function bayAction()
    {    
        return $this->render('ElmetSiteBundle:LandingPage:bay.html.php');
    }
    
    public function sendEmail($registration)
    {
        $customerName = $registration->getName();
        $customerEmail = $registration->getEmail();
        
        $message = \Swift_Message::newInstance()
                        ->setSubject('Registrati​on Request from www.elmetc​urtains.co​.uk')
                        ->setFrom($this->container->getParameter('noreply_address'))
                        ->setTo($this->container->getParameter('enquiries_address'))
                        ->setBody($this->renderView('ElmetSiteBundle:LandingPage:email.txt.php', array('name' => $customerName,'email' => $customerEmail)));

        $this->get('mailer')->send($message);
    }
}

?>