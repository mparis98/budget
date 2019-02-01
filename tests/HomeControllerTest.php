<?php
/**
 * Created by PhpStorm.
 * User: matthieuparis
 * Date: 31/01/2019
 * Time: 09:38
 */

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase{

    public function testGetUsers()   {
        $client = static::createClient();
        $client->request('GET', '/users', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => '01862729c7d75074bb6114ded389b172']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        //$arrayContent = \json_decode($content, true);
        // $this->assertCount(11, $arrayContent);
    }

    public function testGetUsersError()   {
        $client = static::createClient();
        $client->request('GET', '/users/100', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => '01862729c7d75074bb6114ded389b172']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
        //$arrayContent = \json_decode($content, true);
        //$this->assertCount(11, $arrayContent);
    }

    public function testGetSub()   {
        $client = static::createClient();
        $client->request('GET', '/subscription/41', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => 'd41d8cd98f00b204e9800998ecf8427e']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testGetSubError()   {
        $client = static::createClient();
        $client->request('GET', '/subscription/1000', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => 'd41d8cd98f00b204e9800998ecf8427e']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostUsers(){
        $client = static::createClient();
        $client->request('POST', '/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'd41d8cd98f00b204e9800998ecf8427e'],'{"email": "parismatthieu123@gmail.com","apiKey":"bezhufubzdbzf"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostUsersErrorEmail(){
        $client = static::createClient();
        $client->request('POST', '/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'd41d8cd98f00b204e9800998ecf8427e'],'{"email": "parismatthieu123@coucou.com","apiKey":"bezhufubzdbzf"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertJson($content);
    }


}