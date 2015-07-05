<?php

namespace Duff\Nbp\Time;

class RealClock implements Clock
{
    /**
     * @return \DateTime
     */
    public function now()
    {
        return new \DateTime();
    }

} 