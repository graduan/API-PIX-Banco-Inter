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

$token = '12345678-1234-12345-1234-12345678901'; //seu token

try {
     $pix = new PIX($config);
     $pix->setToken($token);
     echo "<pre>";

     $retorno = $pix->pix_consultar_recebidos('2023-03-25T00:00:00Z','2023-03-27T00:00:00Z');
     
     print_r($retorno);
    } 
catch (\Exception $e) {
     echo $e->getMessage();
    }
