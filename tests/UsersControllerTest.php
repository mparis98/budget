<?php
/**
 * Created by PhpStorm.
 * User: matthieuparis
 * Date: 31/01/2019
 * Time: 09:38
 */

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase{

    public function testGetUsers()   {
        $client = static::createClient();
        $client->request('GET', '/api/users', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        //$arrayContent = \json_decode($content, true);
       // $this->assertCount(11, $arrayContent);
    }

    public function testGetUserErrorToken(){
        $client = static::createClient();
        $client->request('GET', '/api/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'egbibegbrfgegrtgbtbdffgr']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testGetUsersError()   {
        $client = static::createClient();
        $client->request('GET', '/api/users/100', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
        //$arrayContent = \json_decode($content, true);
        //$this->assertCount(11, $arrayContent);
    }

    public function testGetSub()   {
        $client = static::createClient();
        $client->request('GET', '/api/admin/subscription/4', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testGetSubError()   {
        $client = static::createClient();
        $client->request('GET', '/api/admin/subscription/1000', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testGetSubErrorToken(){
        $client = static::createClient();
        $client->request('GET', '/api/admin/subscription/4', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostUsers(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"email": "parismatthieu123@gmail.com","apiKey":"bezhufubzdbzf"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostUserErrorToken(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostUsersErrorEmail(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"email": "parismatthieu123coucou.com","apiKey":"bezhufubzdbzf"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testDeleteSubError(){
        $client = static::createClient();
        $client->request('DELETE', '/api/admin/subscription/1', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testDeleteSubErrorToken(){
        $client = static::createClient();
        $client->request('DELETE', '/api/admin/subscription/1', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostCard(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/card', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"name":"Test", "creditCardType":"MASTERCARD","creditCardNumber":"1234567843218765","currencyCode":"RON","value":"500","user": {"id":"33"}}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostCardErrorToken(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/card', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty'],'{"name":"Test", "creditCardType":"MASTERCARD","creditCardNumber":"1234567843218765","currencyCode":"RON","value":"500","user": {"id":"33"}}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostCardErrorCreditNumber(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/card', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"user": {"id":"33"},"name":"Test", "creditCardType":"MASTERCARD","creditCardNumber":"123456784312123343218765","currencyCode":"RON","value":"500"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostCardErrorValue(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/card', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"user": {"id":"33"},"name":"Test", "creditCardType":"MASTERCARD","creditCardNumber":"1234567843218765","currencyCode":"RON","value":"1000000"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostSub(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/subscription', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"name": "Coucou rge","slogan":"edfgbbzvbhe ezjfgbbregheb zefbvber"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostSubErrorToken(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/subscription', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty'],'{"name": "Coucou rge","slogan":"edfgbbzvbhe ezjfgbbregheb zefbvber"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostSubErrorUrl(){
        $client = static::createClient();
        $client->request('POST', '/api/admin/subscription', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"name": "Coucou rge","slogan":"edfgbbzvbhe ezjfgbbregheb zefbvber", "url":"rghrbgrebrgv/nfrgjorng/rngj"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testDeleteCard(){
        $client = static::createClient();
        $client->request('DELETE', '/api/card/1', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testDeleteCardErrorToken(){
        $client = static::createClient();
        $client->request('DELETE', '/api/card/3', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'egbibegbrfgegrtgbtbdffgr']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testDeleteUser(){
        $client = static::createClient();
        $client->request('DELETE', '/api/admin/users/32', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testDeleteUserErrorToken(){
        $client = static::createClient();
        $client->request('DELETE', '/api/admin/users/32', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testDeleteUserError(){
        $client = static::createClient();
        $client->request('DELETE', '/api/admin/users/34322', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchSub(){
        $client = static::createClient();
        $client->request('PATCH', '/api/admin/subscription/2', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"name": "Coucou rge","url":"https://github.com/mparis98/budget"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchSubErrorToken(){
        $client = static::createClient();
        $client->request('PATCH', '/api/admin/subscription/2', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty'],'{"name": "Coucou rge","url":"https://github.com/mparis98/budget"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchSubErrorUrl(){
        $client = static::createClient();
        $client->request('PATCH', '/api/admin/subscription/32', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'd41d8cd98f00b204e9800998ecf8427e'],'{"name": "Coucou rge","url":"/github.com/mparis98/budget"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchCard(){
        $client = static::createClient();
        $client->request('PATCH', '/api/card/3', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty'],'{"creditCardNumber": "0987654312345678","value":"6987"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchCardErrorToken(){
        $client = static::createClient();
        $client->request('PATCH', '/api/card/3', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'regjnrbgehrgbhtg'],'{"creditCardNumber": "0987654312345678","value":"6987"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchCardErrorCreditNumber(){
        $client = static::createClient();
        $client->request('PATCH', '/api/card/3', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"creditCardNumber": "098765123456784312345678","value":"6987"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchCardErrorValue(){
        $client = static::createClient();
        $client->request('PATCH', '/api/card/3', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"creditCardNumber": "0987654312345678","value":"60000000"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchUsers(){
        $client = static::createClient();
        $client->request('PATCH', '/api/admin/users/6', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'admin'],'{"country": "Japan"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchUsersErrorToken(){
        $client = static::createClient();
        $client->request('PATCH', '/api/admin/users/6', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'azerty'],'{"country": "Japan"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertJson($content);
    }


}