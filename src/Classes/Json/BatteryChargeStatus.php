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
 * Battery charge status channel
 *
 * PV-Log requires charge status in %
 *
 * @author   Andreas Buck <buck@top50-solar.de>
 * @license  http://opensource.org/licenses/MIT MIT License (MIT)
 * @version  PVLog JSON 1.1
 * @since    2015-10-12
 * @since    v1.2.0
 */
class BatteryChargeStatus extends Set
{

    // -----------------------------------------------------------------------
    // PUBLIC
    // -----------------------------------------------------------------------

    /**
     * Add the battery charge status to an instance
     *
     * @param  Instance $instance
     * @return self For fluid interface
     */
    public function setToInstance(Instance $instance)
    {
        $instance->setBatteryChargeStatus($this);
        return $this;
    }
}
