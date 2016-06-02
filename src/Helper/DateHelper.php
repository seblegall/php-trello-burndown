<?php

namespace TrelloBurndown\Helper;

/**
 * Trait DateHelper.
 */
trait DateHelper
{
    /**
     * @param \DateTime $date
     *
     * @return bool
     */
    public function isWeekend(\DateTime $date)
    {
        if ($date instanceof \DateTime && ($date->format('N') == 6 || $date->format('N') == 7)) {
            return true;
        }

        return false;
    }
}
