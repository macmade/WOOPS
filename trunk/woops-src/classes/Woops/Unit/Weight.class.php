<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Weight units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Weight extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_GRAM         = 'GRAM';
    const UNIT_KILOGRAM     = 'KILOGRAM';
    const UNIT_LONG_TON     = 'LONG_TON';
    const UNIT_OUNCE        = 'OUNCE';
    const UNIT_SHORT_TON_US = 'SHORT_TON_US';
    const UNIT_TONNE        = 'TONNE';
    const UNIT_US_POUND     = 'US_POUND';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'KILOGRAM';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'GRAM'         => array(),
        'KILOGRAM'     => array(),
        'LONG_TON'     => array(),
        'OUNCE'        => array(),
        'SHORT_TON_US' => array(),
        'TONNE'        => array(),
        'US_POUND'     => array()
    );
}
