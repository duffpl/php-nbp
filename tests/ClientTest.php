<?php

use Duff\Nbp\Client;
use Duff\Nbp\Downloader\Downloader;
use Duff\Nbp\Parser\Parser;

class ClientTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Client;
     */
    private $client;
    /** @var Downloader */
    private $downloader;
    /** @var Parser */
    private $parser;

    public function setUp()
    {
        $this->downloader = $this->prophesize('Duff\Nbp\Downloader\Downloader');
        $this->parser = $this->prophesize('Duff\Nbp\Parser\Parser');
        $this->client = new Client($this->parser->reveal(), $this->downloader->reveal());
    }

    public function testFetchingDataForSpecifiedDate()
    {
        $date = new DateTime('2015-01-04');
        $this->downloader->fetchUrl('http://www.nbp.pl/kursy/xml/dir.txt')->shouldBeCalled();
        $this->downloader->fetchUrl(\Prophecy\Argument::that(function($argument) {
            return preg_match('#http://www.nbp.pl/kursy/xml/a\d{3}z150104.xml#', $argument);
        }))->shouldBeCalled();
        $this->client->getRatesForDate($date);

        $date = new DateTime('2015-03-07');
        $this->downloader->fetchUrl(\Prophecy\Argument::that(function($argument) {
            return preg_match('#http://www.nbp.pl/kursy/xml/a\d{3}z150307.xml#', $argument);
        }))->shouldBeCalled();
        $this->client->getRatesForDate($date);
    }

    public function testFetchingListOfAvailableResultsForGivenTableType()
    {
        $listFixture = join("\n", ['a146z060728', 'c147z060731', 'a147z060731', 'h149z060802']);
        $this->downloader->fetchUrl('http://www.nbp.pl/kursy/xml/dir.txt')->shouldBeCalled()->willReturn($listFixture);
        $expectedResult = [
            '2006-07-28' => 146,
            '2006-07-31' => 147
        ];
        $this->assertEquals($expectedResult, $this->client->getListOfAvailableDatesForTableType('a'));
    }

    /**
     * @expectedException        Duff\Nbp\Exception\InvalidListData
     * @expectedExceptionMessage No data fetched
     */
    public function testNoDataExceptionThrownWhenDirectoryFetchResultIsEmpty()
    {
        $this->downloader->fetchUrl('http://www.nbp.pl/kursy/xml/dir.txt')->shouldBeCalled()->willReturn('');
        $this->client->getListOfAvailableDatesForTableType('a');
    }

    /**
     * @expectedException        Duff\Nbp\Exception\InvalidListData
     * @expectedExceptionMessage No results
     */
    public function testNoResultsExceptionThrownWhenDirectoryFetchResultYieldsNoResults()
    {
        $this->downloader->fetchUrl('http://www.nbp.pl/kursy/xml/dir.txt')->shouldBeCalled()->willReturn('some bogus data');
        $this->client->getListOfAvailableDatesForTableType('a');
    }
} 