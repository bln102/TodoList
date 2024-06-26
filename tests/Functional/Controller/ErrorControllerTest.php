<?php 

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorControllerTest  extends WebTestCase {
    public function test_error_404(){
        $client = static::createClient();
        $client->request('GET', '/page404');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function test_error_role(){
        $client = static::createClient();
        $client->request('GET', '/error_role');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}