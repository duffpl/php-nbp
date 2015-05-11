<?php

use Duff\NBPClient;

class NBPClientTest extends PHPUnit_Framework_TestCase {

    /**
     * @var NBPClient
     */
    private $client;
    private $guzzle;

    public function setUp()
    {
        $this->guzzle = $this->prophesize('GuzzleHttp\Client');
        $this->client = new NBPClient($this->guzzle->reveal());
    }


    public function testEndpointUrlCanBeConfigured()
    {
        $client = $this->client;
        $endpoint = 'http://this.is.endpoint.com/onetwothree/';
        $client->setEndpointUrl($endpoint);
        $this->assertEquals($endpoint, $client->getEndpointUrl());

        $endpoint = 'http://other.endpoint.com/';
        $client->setEndpointUrl($endpoint);
        $this->assertEquals($endpoint, $client->getEndpointUrl());
    }

    public function testDirectoryPathBeConfigured()
    {
        $client = $this->client;
        $directoryPath = 'directory.txt';
        $client->setDirectoryPath($directoryPath);
        $this->assertEquals($directoryPath, $client->getDirectoryPath());
    }

    public function testGuzzleClientCanBeInjectedInConstructor()
    {
        $guzzleMock = $this->getMock('GuzzleHttp\ClientInterface');
        $client = new NBPClient($guzzleMock);
        $this->assertEquals($guzzleMock, $client->getGuzzle());
    }

    public function testFetchingDirectoryFile()
    {
        $this->guzzle->
    }
} 