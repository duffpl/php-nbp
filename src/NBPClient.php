<?php

namespace Duff;

use GuzzleHttp\ClientInterface as GuzzleClient;

class NBPClient
{
    private $endpointUrl;
    private $directoryPath;

    /** @var GuzzleClient */
    private $guzzle;

    /**
     * @param $guzzle GuzzleClient
     */
    function __construct($guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @return GuzzleClient
     */
    public function getGuzzle()
    {
        return $this->guzzle;
    }

    /**
     * @return mixed
     */
    public function getEndpointUrl()
    {
        return $this->endpointUrl;
    }

    /**
     * @param mixed $endpointUrl
     */
    public function setEndpointUrl($endpointUrl)
    {
        $this->endpointUrl = $endpointUrl;
    }

    /**
     * @return mixed
     */
    public function getDirectoryPath()
    {
        return $this->directoryPath;
    }

    /**
     * @param mixed $directoryPath
     */
    public function setDirectoryPath($directoryPath)
    {
        $this->directoryPath = $directoryPath;
    }
} 