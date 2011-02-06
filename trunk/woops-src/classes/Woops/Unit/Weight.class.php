<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
# All rights reserved                                                          #
################################################################################

# $Id$

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Unit;

/**
 * Weight units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Weight extends Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
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
    protected $_types = array
    (
        'GRAM'         => array(),
        'KILOGRAM'     => array(),
        'LONG_TON'     => array(),
        'OUNCE'        => array(),
        'SHORT_TON_US' => array(),
        'TONNE'        => array(),
        'US_POUND'     => array()
    );
}
