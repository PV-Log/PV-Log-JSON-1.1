<?php
/**
 * Copyright (c) 2015 PV-Log.com, Top50-Solar
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */
namespace PVLog\Classes\Json;

/**
 * Self consumption channel
 *
 * @author   Knut Kohl <kohl@top50-solar.de>
 * @license  http://opensource.org/licenses/MIT MIT License (MIT)
 * @version  PVLog JSON 1.1
 * @since    2015-04-13
 * @since    v1.0.0
 */
class SelfConsumption extends EnergyMeter {

    // -----------------------------------------------------------------------
    // PUBLIC
    // -----------------------------------------------------------------------

    /**
     * Add the self consumption to an instance
     *
     * @param  Instance $instance
     * @return self For fluid interface
     */
    public function setToInstance( Instance $instance ) {
        $instance->setSelfConsumption($this);
        return $this;
    }

    /*
     * Overloaded
     *
     * Remove leading 0 values in front of day
     */
    public function asArray( $flags=0 ) {
        $result = parent::asArray($flags);

        if ($flags & self::EXPORT_POWER) {
            // Minutes file, round powers
            foreach ($result[Properties::POWER] as $timestamp=>$value) {
                // Break loop on 1st non 0 value
                if ($value) break;
                unset($result[Properties::POWER][$timestamp]);
            }
        }

        return $result;
    }
}
