<?php

namespace Elmet\SiteBundle\Controller;

use Elmet\SiteBundle\Entity\Order;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class IPNGeneratorController extends Controller
{
    
    public function generateAction($id)
    {
        $req = $this->getReq($id);
        
        //write id to a file so that it can be picked up by the respond Action
        
        $file = "order_id.txt";
        $fh = fopen($file, 'w');
        fwrite($fh, $id);
        fclose($fh);

        $ch = curl_init("http://localhost/paypal_ipn/process");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        
        $res = curl_exec($ch);
        curl_close($ch);
        
        return new Response("<html><head><title></title></head><body><h1>".$res."</h1></body></html>");
        
    }
    
    public function convertAction()
    {
        $inputFile = "input.txt";
        $inputFh = fopen($inputFile, 'r');
        $unencoded = fread($inputFh, filesize($inputFile));
        fclose($inputFh);
        
        $params = array();
        
        $length = strlen($unencoded);
        $index = 0;
        $processKey = TRUE;
        $key = "";
        $value = "";
        
        while($index < $length) {
            
            $c = substr($unencoded,$index,1);
            
            if ($c == '=') {
                $processKey = FALSE;
            } elseif (($c == '&') or ($index == $length - 1)) {
                $processKey = TRUE;
                $params[$key] = $value;
                $key = "";
                $value = "";
            } else {
                
                if ($processKey == TRUE) {
                    $key = $key.$c;
                } else {
                    $value = $value.$c;
                }
            }
            
            $index = $index + 1;
        }
        
        $req_params = "";
        
        $keys = array_keys($params);
        
        foreach($keys as $key) {
            $param = $params[$key];
            
            if ($keys[0] == $key) {
                $req_params = $key."=".urlencode($param);
            } else {
                $req_params = $req_params."&".$key."=".urlencode($param);
            }
        }
        
        $file = "paypal.txt";
        $fh = fopen($file, 'w');
        fwrite($fh, $req_params);
        fclose($fh);
        
        return new Response($req_params);
        
    }
    
    public function respondAction($type)
    {
        if ($type == 'local') {
            
            $file = "order_id.txt";
            $fh = fopen($file, 'r');
            $id = fread($fh, filesize($file));
            fclose($fh);

            $req = $this->getReq($id);
        } else {
            
            $file = "paypal.txt";
            $fh = fopen($file, 'r');
            $req = fread($fh, filesize($file));
            fclose($fh);
        }
        
        
        $req_params = "";
        
        if ($this->getRequest()->getMethod() == "GET") {
            $params = $this->getRequest()->query->all();
        } else {
            $params = $this->getRequest()->request->all();
        }
        
        
        $keys = array_keys($params);
        
        foreach($keys as $key) {
            $param = $params[$key];
            $req_params = $req_params."&".$key."=".urlencode($param);
           
        }
        
        if (strcmp($req_params,"&cmd=_notify-validate&".$req) == 0) {
            return new Response("VERIFIED");
        } else {
            return new Response("INVALID <br/>".$req."<br/>".$req_params);
        
        }
        
        return new Response($id);
    }
    
    public function getReq($id)
    {
        $repository = $this->getDoctrine()->getRepository('ElmetSiteBundle:Order');
        $order = $repository->findOneById($id);
        
        $receiver_email = $this->container->getParameter('paypal_business');
        $item_name = $this->container->getParameter('paypal_item_name');
        $custom = $id;
        $txn_id = "TXN".$id;
        $payer_email = $this->container->getParameter('paypal_business');
        $first_name = "Ranjiva";
        $last_name = "Prasad";
        $address_city = "Fleet";
        $address_country = "UK";
        $address_name = "Ranjiva Prasad";
        $address_street = "20 Sussex Gardens";
        $address_zip = "GU51 2TL";
        $payment_status = "Completed";
        $mc_gross = $order->getAmountPaid();
        $mc_currency = "GBP";
        
        $req = "receiver_email=".urlencode($receiver_email);
        $req = $req."&item_name=".urlencode($item_name);
        $req = $req."&custom=".urlencode($id);
        $req = $req."&txn_id=".urlencode($txn_id);
        $req = $req."&payer_email=".urlencode($payer_email);
        $req = $req."&first_name=".urlencode($first_name);
        $req = $req."&last_name=".urlencode($last_name);
        $req = $req."&address_city=".urlencode($address_city);
        $req = $req."&address_country=".urlencode($address_country);
        $req = $req."&address_name=".urlencode($address_name);
        $req = $req."&address_street=".urlencode($address_street);
        $req = $req."&address_zip=".urlencode($address_zip);
        $req = $req."&payment_status=".urlencode($payment_status);
        $req = $req."&mc_gross=".urlencode($mc_gross);
        $req = $req."&mc_currency=".urlencode($mc_currency);
        
        return $req;
    }
}
?>
