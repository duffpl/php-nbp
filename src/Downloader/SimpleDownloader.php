<?php

namespace Duff\Nbp\Downloader;

class SimpleDownloader implements Downloader
{

    public function fetchUrl($url)
    {
        return file_get_contents($url);
    }
}