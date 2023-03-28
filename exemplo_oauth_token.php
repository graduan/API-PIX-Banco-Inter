<?php

require 'vendor/autoload.php';

use GRaduan\ApiInterV2\PIX;
 

$config = [
     'certificate'    => __DIR__ . '/certificado.crt',
     'certificateKey' => __DIR__ . '/chave.key',
     "chavepix"       => "xxxxxx", // sua chave PIX 
     "client_id"      => "xxxxxx", // API client_id 
     "client_secret"  => "xxxxxx", // API client_secret 
    ];
 
try {
     $pix = new pix($config);
       
     $token = $pix->getToken($config['client_id'], $config['client_secret'], 'boleto-cobranca.read boleto-cobranca.write cob.write cob.read pix.write pix.read webhook.write webhook.read payloadlocation.write payloadlocation.read');
     print_r($token);
    } 
catch (\Exception $e) {
     echo $e->getMessage();
    }    

// retorno esperado   Array ( [access_token] => 12345678-1234-12345-1234-12345678901 [token_type] => Bearer [expires_in] => 3600 [scope] => boleto-cobranca.read boleto-cobranca.write cob.write cob.read pix.write pix.read webhook.write webhook.read payloadlocation.write payloadlocation.read )
