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
        $client->request('GET', '/api/users', [], [], ['HTTP_ACCEPT' => 'application/json','HTTP_X-AUTH-TOKEN' => 'aaa']);
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = \json_decode($content, true);
        $this->assertCount(10, $arrayContent);
    }

    public function testPostUsers(){
        $client = static::createClient();
        $client->request('POST', '/api/users', [], [],['HTTP_ACCEPT' => 'application/json','CONTENT_TYPE' => 'application/json','HTTP_X-AUTH-TOKEN' => 'aaa'],'{"apiKey": "adqaaa","email": "test@behat.com"}');
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }
}