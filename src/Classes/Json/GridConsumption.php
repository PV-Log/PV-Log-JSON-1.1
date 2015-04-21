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
 * Grid consumption meter channel
 *
 * @author   Knut Kohl <kohl@top50-solar.de>
 * @license  http://opensource.org/licenses/MIT MIT License (MIT)
 * @version  PVLog JSON 1.1
 * @since    2015-04-02
 * @since    v1.0.0
 */
class GridConsumption extends EnergyMeter {

    // -----------------------------------------------------------------------
    // PUBLIC
    // -----------------------------------------------------------------------

    /**
     * Add the grid consumption to an instance
     *
     * @param  Instance $instance
     * @return self For fluid interface
     */
    public function setToInstance( Instance $instance ) {
        $instance->setGridConsumption($this);
        return $this;
    }


    /*
     * Overloaded
     *
     * Remove trailing 0 values during the day
     */
    public function asArray( $flags=0 ) {
        $result = parent::asArray($flags);

        if ($flags & self::EXPORT_POWER) {
            // Minutes file, round powers
            // Reverse array, easier to delete leading data
            $reversed = array_reverse($result[Properties::POWER], TRUE);
            foreach ($reversed as $timestamp=>$value) {
                // Break loop on 1st non 0 value
                if ($value) break;
                unset($reversed[$timestamp]);
            }
            $result[Properties::POWER] = array_reverse($reversed, TRUE);
        }

        return $result;
    }
}
