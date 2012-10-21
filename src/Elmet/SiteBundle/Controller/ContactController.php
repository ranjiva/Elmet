<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Controller\BaseController;
use Elmet\SiteBundle\Entity\Contact;

class ContactController extends BaseController
{
    public function indexAction()
    {
        $contact = new Contact();
        
        $form = $this->createFormBuilder($contact)
                ->add('name', 'text')
                ->add('tel', 'text')
                ->add('email', 'email')
                ->add('enquiry', 'textarea')
                ->getForm();
        
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') 
        {
            $form->bindRequest($request);
        
            $validator = $this->get('validator');
            $errors = $validator->validate($contact);
            
            if (count($errors) ==0) 
            {
                $this->sendEmail($contact);
            
                $contact = new Contact();
        
                $form = $this->createFormBuilder($contact)
                    ->add('name', 'text')
                    ->add('tel', 'number')
                    ->add('email', 'email')
                    ->add('enquiry', 'textarea')
                    ->getForm();
            }
        }
        
        return $this->render('ElmetSiteBundle:Contact:index.html.php',array('featured' => $this->getFeaturedTestimonial(),'numBasketItems' => $this->getNumBasketItems(),'form' => $form->createView()));
        
    }
    
    public function sendEmail($contact)
    {
        $customerName = $contact->getName();
        $customerTel = $contact->getTel();
        $customerEmail = $contact->getEmail();
        $enquiry = $contact->getEnquiry();
        
        $message = \Swift_Message::newInstance()
                        ->setSubject('Enquiry from www.elmetc​urtains.co​.uk')
                        ->setFrom($this->container->getParameter('noreply_address'))
                        ->setTo($this->container->getParameter('enquiries_address'))
                        ->setBody($this->renderView('ElmetSiteBundle:Contact:email.txt.php', array('name' => $customerName, 'tel' => $customerTel, 'email' => $customerEmail, 'enquiry' => $enquiry)));

        $this->get('mailer')->send($message);
    }
}

?>