<?php 
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $postData = [
            'numberAccount' => '5151451'
        ];

        $client->request(
            'POST',
            '/account/addAccount',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($postData)
        );
                // check response 
                $this->assertEquals(200, $client->getResponse()->getStatusCode());
                $this->assertJson($client->getResponse()->getContent());
                $data = json_decode($client->getResponse()->getContent(), true);
                $this->assertEquals('Sukces', $data['data']['message']);

    }
}