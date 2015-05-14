<?php

namespace Duff\Nbp;

use Duff\Nbp\Downloader\Downloader;
use Duff\Nbp\Exception\InvalidListData;
use Duff\Nbp\Parser\Parser;

class Client
{
    const TABLE_TYPE_AVERAGE = 'a';
    const TABLE_TYPE_BUY_SELL = 'c';

    /** @var Downloader */
    private $downloader;
    /** @var Parser */
    private $parser;

    private $endPointUrl = 'http://www.nbp.pl/kursy/xml';
    private $listUrl =  'http://www.nbp.pl/kursy/xml/dir.txt';

    function __construct(Parser $parser, Downloader $downloader)
    {
        $this->parser = $parser;
        $this->downloader = $downloader;
    }

    public function getRatesForDate(\DateTime $date, $tableType = self::TABLE_TYPE_AVERAGE)
    {
        $this->fetchDirectoryList();
        $filename = $this->createFilename($date, 100, $tableType);
        $url = $this->endPointUrl . '/' . $filename;
        $this->downloader->fetchUrl($url);
        return ['usd' => 1.33, 'eur' => 500];
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    private function dateIsOnDirectoryList(\DateTime $date)
    {
       return true;
    }

    public function getListOfAvailableDatesForTableType($tableType)
    {
        $directoryList = $this->fetchDirectoryList();
        return $this->findDatesForTableType($directoryList, $tableType);
    }

    private function fetchDirectoryList()
    {
        return $this->downloader->fetchUrl($this->listUrl);
    }

    private function findDatesForTableType($list, $tableType)
    {
        $result = [];
        if ($list == '') throw new InvalidListData('No data fetched');
        foreach (explode("\n", $list) as $item) {
            $matches = [];
            preg_match("/^{$tableType}(\\d{3})z(\\d{6})$/", trim($item), $matches);
            if (empty($matches)) {
                continue;
            }
            $result[\DateTime::createFromFormat('ymd', $matches[2])->format('Y-m-d')] = $matches[1];
        }
        if (empty($result)) throw new InvalidListData('No results');
        return $result;
    }

    private function createFilename(\DateTime $date, $tableNumber, $tableType)
    {
        $formattedDate = $date->format('ymd');
        return join('', [$tableType, $tableNumber, 'z', $formattedDate, '.xml']);
    }
} 