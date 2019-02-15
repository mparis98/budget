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
        $client->request('GET', '/users', [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        //$arrayContent = \json_decode($content, true);
        // $this->assertCount(11, $arrayContent);
    }

    public function testGetUsersError()   {
        $client = static::createClient();
        $client->request('GET', '/users/100', [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
        //$arrayContent = \json_decode($content, true);
        //$this->assertCount(11, $arrayContent);
    }

    public function testGetSub()   {
        $client = static::createClient();
        $client->request('GET', '/subscription/1', [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testGetSubError()   {
        $client = static::createClient();
        $client->request('GET', '/subscription/1000', [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostUsers(){
        $client = static::createClient();
        $client->request('POST', '/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json'],'{"email": "parismatthiehbbhvu123@gmail.com","apiKey":"bezhufubhjbjbzdbzf"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostUsersErrorEmail(){
        $client = static::createClient();
        $client->request('POST', '/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json'],'{"email": "parismatthinbhbheu123coucou.com","apiKey":"bezhufuhhuiuhbzdbzf"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($content);
    }


}