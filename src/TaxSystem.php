<?php

/**
 * This file is part of the initpro/kassa-sdk library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Initpro\KassaSdk;

class TaxSystem
{
    /**
     * Common tax system
     */
    const COMMON = 0;

    /**
    * Simplified tax system: Income
    */
    const SIMPLIFIED_IN = 1;

    /**
     * Simplified tax system: Income - Outgo
     */
    const SIMPLIFIED_IN_OUT = 2;

    /**
     * An unified tax on imputed income
     */
    const UTOII = 3;

    /**
     * Unified social tax
     */
    const UST = 4;

    /**
     * Patent
     */
    const PATENT = 5;
}
