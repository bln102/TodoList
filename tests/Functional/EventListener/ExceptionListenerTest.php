<?php 

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExceptionListenerTest  extends WebTestCase {
    public function test_unknown_route(){
        $client = static::createClient();
        $client->request('GET', '/unknown');
        self::assertResponseRedirects('/page404');
    }
}