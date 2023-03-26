<?php

/*
São José do Rio Preto - SP - Brasil

Agradecimentos:
- Família que criamos, minhas meninas, esposa Simone e filha Lais;
- Roseno Matos, https://packagist.org/packages/divulgueregional/api-inter-v2, que foi inspiração para esta.

Hoje, domingo, dia 26 de março de 2023, dia que partiu Juca Chaves, último dia de Lollapalooza em 2023, não fui...


Thanks to:
- Family we created, my girls, wife Simone and daughter Lais;
- Roseno Matos, https://packagist.org/packages/divulgueregional/api-inter-v2, which was the inspiration for this one.

Today, Sunday, March 26, 2023, the day that Juca Chaves passed away (RIP), the last day of Lollapalooza in 2023, I didn't go...
*/

namespace GRaduan\ApiInterV2;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;

class PIX {
     protected $token;
     protected $optionsRequest = [];
 
     private $client;
     private $config = [];


     function __construct(array $config) {
         $this->config = $config;
         $this->client = new Client([
             'base_uri' => 'https://cdpj.partners.bancointer.com.br',
            ]);

         if (isset($config['verify'])){
             if ($config['verify'] == ''){
                 $verify = false;
                }
             elseif ($config['verify'] != '' && $config['verify'] != 1){
                 $verify = $config['verify'];
                }
             else{
                 $verify = $config['certificate'];
                }
            }
         else{
             $verify = $config['certificate'];
            }

         $this->optionsRequest = [
             'headers' => [
                 'Accept' => 'application/json'
                ],
             'cert' => $config['certificate'], 
             'verify' => $verify,
             'ssl_key' => $config['certificateKey'],
            ];
        }
     
     /*
     Obter token oAuth
     - A autenticação OAuth é a mais recente forma de autenticar os novos serviços disponibilizados pelo Inter. 
     - O Token gerado será necessário para consumir as APIs do Inter.
     
     POST https://cdpj.partners.bancointer.com.br/oauth/v2/token
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/token-1
     */
     public function getToken(string $client_id, string $client_secret, $scope = 'extrato.read boleto-cobranca.read boleto-cobranca.write pagamento-boleto.read pagamento-boleto.write') {
         $options = $this->optionsRequest;
         $options['form_params'] = [
             'client_id' => $client_id,
             'client_secret' => $client_secret,
             'grant_type' => 'client_credentials',
             'scope' => $scope
            ];
         
         try {
             $response = $this->client->request(
                 'POST',
                 '/oauth/v2/token',
                 $options
                );

             return (array) json_decode($response->getBody()->getContents());
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => $response];
            }
        }

     public function setToken(string $token) {
         $this->token = $token;
        }
     

     /*
     Consultar pix recebidos
     - Endpoint para consultar um pix por um período específico, de acordo com os parâmetros informados.
     
     GET https://cdpj.partners.bancointer.com.br/pix/v2/pix
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/get_pix-1
     */
     public function pix_consultar_recebidos(string $_dt_inicio, string $_dt_fim, array $_options = array()) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";
         $options['headers']['Content-Type'] = 'application/json';
         
         $_options['inicio'] = $_dt_inicio;
         $_options['fim']    = $_dt_fim;
         
         try {
             $response = $this->client->request(
                 'GET',
                 "/pix/v2/pix?" . http_build_query($_options),
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao consultar recebidos PIX: {$response}"];
            }
        }
     

     /*
     Consultar pix
     - Endpoint para consultar um pix através de um determinado EndToEndId.
     
     GET https://cdpj.partners.bancointer.com.br/pix/v2/pix/{e2eId}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/get_pix-e2eid-1
     */
     public function pix_consultar(string $_e2eId) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";
         $options['headers']['Content-Type'] = 'application/json';
         
         try {
             $response = $this->client->request(
                 'GET',
                 "/pix/v2/pix/{$_e2eId}",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao obter Webhook Cadastrado: {$response}"];
            }
        }


     /*
     Consultar lista de cobranças imediatas
     - Endpoint para consultar cobranças imediatas através de parâmetros como início, fim, cpf, cnpj e status.

     GET https://cdpj.partners.bancointer.com.br/pix/v2/cob
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/get_cob-1
     */
     public function pix_cobranca_listar(string $_dt_inicio, string $_dt_fim, array $_options = array()) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";
         $options['headers']['Content-Type'] = 'application/json';
         
         $_options['inicio'] = $_dt_inicio;
         $_options['fim']    = $_dt_fim;
         
         try {
             $response = $this->client->request(
                 'GET',
                 "/pix/v2/cob?" . http_build_query($_options),
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao listar cobrança PIX: {$response}"];
            }
        }


     /*
     Revisar cobrança imediata
     - Endpoint para revisar cobrança imediata.
     
     PATCH https://cdpj.partners.bancointer.com.br/pix/v2/cob/{txid}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/patch_cob-txid-1
     */
     public function pix_cobranca_revisar($_txid, $_descricao = NULL, $_expiracao = 180) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";

         $options['body'] = json_encode([
             'calendario' => ['expiracao' => $_expiracao],
             'valor'      => ['original' => number_format($_valor, 2, '.','')],
             'chave'      => $this->config['chavepix'],
             'solicitacaoPagador' => $_descricao]);
         $options['headers']['Content-Type'] = 'application/json';

         try {
             $response = $this->client->request(
                 'PATCH',
                 "/pix/v2/cob/{$_txid}",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao revisar cobrança PIX: {$response}"];
            }
        }

       
     /*
     Criar cobrança imediata
     - Endpoint para criar uma cobrança imediata.
     
     PUT https://cdpj.partners.bancointer.com.br/pix/v2/cob/{txid}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/put_cob-txid-1
     */
     public function pix_cobranca_criar($_valor, $_txid, $_descricao = NULL, $_expiracao = 180) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";

         //if ($_txid == NULL) $_txid = $this->genTXID();

         $options['body'] = json_encode([
            'calendario' => ['expiracao' => $_expiracao],
            'valor'      => ['original' => number_format($_valor, 2, '.','')],
            'chave'      => $this->config['chavepix'],
            'solicitacaoPagador' => $_descricao]);
         $options['headers']['Content-Type'] = 'application/json';

         try {
             $response = $this->client->request(
                 'PUT',
                 "/pix/v2/cob/{$_txid}",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao criar cobrança PIX: {$response}"];
            }
        }
     
     
     /*
     Criar cobrança imediata
     - Endpoint para criar uma cobrança imediata, neste caso, o txid é definido pelo PSP.
     
     POST https://cdpj.partners.bancointer.com.br/pix/v2/cob
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/post_cob-1
     */
     public function pix_cobranca_criar_txidPSP($_valor, $_descricao = NULL, $_expiracao = 180) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";

         $options['body'] = json_encode([
            'calendario' => ['expiracao' => $_expiracao],
            'valor'      => ['original' => number_format($_valor, 2, '.','')],
            'chave'      => $this->config['chavepix'],
            'solicitacaoPagador' => $_descricao]);
         $options['headers']['Content-Type'] = 'application/json';

         try {
             $response = $this->client->request(
                 'POST',
                 "/pix/v2/cob/",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao criar cobrança (txidPSP) PIX: {$response}"];
            }
        }
     
     
     /*
     Solicitar devolução
     - Endpoint para solicitar uma devolução através de um E2EID do Pix e do ID da devolução.
     
     PUT https://cdpj.partners.bancointer.com.br/pix/v2/pix/{e2eId}/devolucao/{id}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/put_pix-e2eid-devolucao-id-1
     */
     public function pix_devolucao($_txid, $_e2eId, $_valor, $_descricao = NULL) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";
         
         $options['body'] = json_encode([
             'valor'      => number_format($_valor, 2, '.',''),
             'descricao'  => $_descricao,
            ]);
         $options['headers']['Content-Type'] = 'application/json';

         //print_r($options); exit;

         try {
             $response = $this->client->request(
                 'PUT',
                 "pix/v2/pix/{$_e2eId}/devolucao/{$_txid}",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao devolver PIX cobrado: {$response}"];
            }
        }
     
     
     /*
     Consultar devolução
     - Endpoint para consultar uma devolução através de um E2EID do Pix e do ID da devolução.
     
     GET https://cdpj.partners.bancointer.com.br/pix/v2/pix/{e2eId}/devolucao/{id}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/get_pix-e2eid-devolucao-id-1
     */
     public function pix_consultar_devolucao($_txid, $_e2eId) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";
         $options['headers']['Content-Type'] = 'application/json';

         try {
             $response = $this->client->request(
                 'GET',
                 "pix/v2/pix/{$_e2eId}/devolucao/{$_txid}",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao consultar devolução PIX: {$response}"];
            }
        }
     
     
     /*
     Consultar cobrança imediata
     - Endpoint para consultar uma cobrança através de um determinado txid.

     GET https://cdpj.partners.bancointer.com.br/pix/v2/cob/{txid}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/get_cob-txid-1
     */
     public function pix_consultar_cobranca($_txid) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";
         $options['headers']['Content-Type'] = 'application/json';

         try {
             $response = $this->client->request(
                 'GET',
                 "pix/v2/cob/{$_txid}",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao consultar cobrança PIX: {$response}"];
            }
        }
     
     
     /*
     Criar webhook
     - Método destinado a criar um webhook para receber notificações de cobranças Pix recebidas (callbacks).
     
     PUT https://cdpj.partners.bancointer.com.br/pix/v2/webhook/{chave}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/webhookput-2
     */
     public function pix_webhook_criar($_chave, $_webhook_url) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";

         $options['body'] = json_encode([
            'webhookUrl' => $_webhook_url,
           ]);
        $options['headers']['Content-Type'] = 'application/json';

         try {
             $response = $this->client->request(
                 'PUT',
                 "/pix/v2/webhook/{$_chave}",
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao criar webhook PIX: {$response}"];
            }
        }
     
     
     /*
     Obter webhook cadastrado
     - Obtém o webhook cadastrado, caso exista.
     
     GET https://cdpj.partners.bancointer.com.br/pix/v2/webhook/{chave}
         
     Documentação Oficial: https://developers.bancointer.com.br/reference/webhookget-2
     */
     public function pix_webhook_consultar($_chave) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";

         try {
             $response = $this->client->request(
                 'GET',
                 "/pix/v2/webhook/" . $_chave,
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao obter webhook PIX: {$response}"];
            }
        }
     
     
     /*
     Excluir webhook
     - Exclui o webhook.
     
     DELETE https://cdpj.partners.bancointer.com.br/pix/v2/webhook/{chave}
     
     Documentação Oficial: https://developers.bancointer.com.br/reference/webhookdelete-2
     */
     public function pix_webhook_excluir($_chave) {
         $options = $this->optionsRequest;
         $options['headers']['Authorization'] = "Bearer {$this->token}";

         try {
             $response = $this->client->request(
                 'DELETE',
                 "/pix/v2/webhook/" . $_chave,
                 $options
                );

             $statusCode = $response->getStatusCode();
             $result = json_decode($response->getBody()->getContents());
             return array('status' => $statusCode, 'response' => $result);
            }
         catch (ClientException $e) {
             return $this->parseResultClient($e);
            }
         catch (\Exception $e) {
             $response = $e->getMessage();
             return ['error' => "Falha ao excluir webhook PIX: {$response}"];
            }
        }
     
     
     
     // ferramentas

     public function genTXID(int $length = 30){ // 64 = 32
         $length = ($length < 4) ? 4 : $length;
         return bin2hex(random_bytes(($length-($length%2))/2));
        }
     
     private function parseResultClient($result) {
         $statusCode = $result->getResponse()->getStatusCode();
         $response = $result->getResponse()->getReasonPhrase();
         $body = $result->getResponse()->getBody()->getContents();
 
         return ['error' => $body, 'response' => $response, 'statusCode' => $statusCode];
        }
    }
