<?php

namespace Duff\Nbp\Time;

interface Clock
{
    /**
     * @return \DateTime
     */
    public function now();
} 