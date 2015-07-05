<?php

use Duff\Nbp\Downloader\Downloader;
use Duff\Nbp\TableFinder;
use Duff\Nbp\Time\Clock;

class TableFinderTest extends PHPUnit_Framework_TestCase {

    /**
     * @var TableFinder;
     */
    private $sut;
    /** @var Downloader */
    private $downloader;
    /** @var Clock */
    private $clockService;
    /** @var \DateTime */
    private $now;

    public function setUp()
    {
        $this->now = \DateTime::createFromFormat('U', mktime(23, 00, 00, 7, 15, 2015));
        $this->downloader = $this->prophesize('Duff\Nbp\Downloader\Downloader');
        $this->clockService = $this->prophesize('Duff\Nbp\Time\Clock');
        $this->sut = new TableFinder($this->downloader->reveal(), $this->clockService->reveal(), 'http://prefix');
    }

    public function testCreatingDirectoryUrlForCurrentYear()
    {
        $this->clockService->now()->shouldBeCalled()->willReturn($this->now);
        $this->downloader->fetchUrl('http://prefix/dir.txt')->shouldBeCalled()->willReturn('some data');
        $date = new DateTime('2015-07-28');
        $this->sut->findTableForDate($date);
    }

    public function testCreatingDirectoryUrlForAnyPreviousYear()
    {
        $this->clockService->now()->shouldBeCalled()->willReturn($this->now);
        $this->downloader->fetchUrl('http://prefix/dir2014.txt')->shouldBeCalled()->willReturn('some data');
        $date = new DateTime('2014-07-28');
        $this->sut->findTableForDate($date);
        $this->downloader->fetchUrl('http://prefix/dir2011.txt')->shouldBeCalled()->willReturn('some data');
        $date = new DateTime('2011-01-11');
        $this->sut->findTableForDate($date);
    }

    public function testFindingClosestDateInTable()
    {
        $this->clockService->now()->willReturn($this->now);
        $this->downloader->fetchUrl(\Prophecy\Argument::any())->willReturn($this->loadDirFixture(2014));
        $date = new DateTime('2014-07-28');
        $this->assertEquals('c144z140728', $this->sut->findTableForDate($date));

        $date = new DateTime('2014-07-28');
        $this->assertEquals('a144z140728', $this->sut->findTableForDate($date, 'a'));

        $date = new DateTime('2014-07-25');
        $this->assertEquals('c143z140725', $this->sut->findTableForDate($date, 'c'));

        $date = new DateTime('2014-07-26');
        $this->assertEquals('c143z140725', $this->sut->findTableForDate($date, 'c'));

        $date = new DateTime('2014-07-27');
        $this->assertEquals('c143z140725', $this->sut->findTableForDate($date, 'c'));

        $date = new DateTime('2014-07-30');
        $this->assertEquals('b030z140730', $this->sut->findTableForDate($date, 'b'));

        $date = new DateTime('2014-07-31');
        $this->assertEquals('b030z140730', $this->sut->findTableForDate($date, 'b'));

        $date = new DateTime('2014-08-04');
        $this->assertEquals('b030z140730', $this->sut->findTableForDate($date, 'b'));


    }

    private function loadDirFixture($year = 2014)
    {
        return file_get_contents(__DIR__ . '/Fixture/dir' . $year . '.txt');
    }
}