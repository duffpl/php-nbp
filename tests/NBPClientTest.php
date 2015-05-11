<?php

use Duff\NBPClient;

class NBPClientTest extends PHPUnit_Framework_TestCase {

    private $client;

    public function setUp()
    {
        $this->client = new NBPClient();
    }


    public function testCanBeConigured()
    {
        $endpoint = 'http://this.is.endpoint.com/onetwothree/';
        $this->client->setEndpoint($endpoint);
        $this->assertEquals($endpoint, $this->client->getEndpoint());
    }
} 