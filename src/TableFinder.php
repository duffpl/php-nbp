<?php

namespace Duff\Nbp;

use Duff\Nbp\Downloader\Downloader;
use Duff\Nbp\Time\Clock;

class TableFinder
{
    /** @var Downloader */
    private $downloader;
    /** @var string */
    private $indexUrlPrefix;
    /** @var Clock */
    private $clockService;

    function __construct(Downloader $downloader, Clock $clock, $indexUrlPrefix)
    {
        $this->clockService = $clock;
        $this->indexUrlPrefix = $indexUrlPrefix;
        $this->downloader = $downloader;
    }

    public function findTableForDate(\DateTime $date, $tableType = 'c')
    {
        $fullDirectoryPath = $this->indexUrlPrefix . '/' . $this->getDirectoryFilenameForDate($date);
        $directoryData = $this->downloader->fetchUrl($fullDirectoryPath);
        return $this->findClosestDateForTableType($directoryData, $tableType, $date);
    }

    private function findClosestDateForTableType($directoryData, $type, \DateTime $date)
    {
        $tables = explode("\n", $directoryData);
        $formattedInputDate = $date->format('ymd');
        $daysPerTableType = [];
        $dateDifference = 9999;
        $lastClosestTable = '';
        foreach ($tables as $table) {
            preg_match("/^(\\w)\\d+z(.*)$/", $table, $matches);
            $parsedType = $matches[1];
            $parsedDate = $matches[2];
            if ($parsedType != $type) continue;
            $dateDifference = $parsedDate-$formattedInputDate;
            if ($dateDifference > 0) {
                return $lastClosestTable;
            }
            $lastClosestTable = $table;
        }
        return false;
    }
    private function getDirectoryFilenameForDate(\DateTime $date)
    {
        $now = $this->clockService->now();
        $directoryFileName = 'dir';
        if ($now->format('Y') != ($year = $date->format('Y'))) {
            $directoryFileName .= $year;
        }
        $directoryFileName .= '.txt';
        return $directoryFileName;
    }

} 