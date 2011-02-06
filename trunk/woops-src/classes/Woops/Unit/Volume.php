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
 * Volume units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Volume extends Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The available units
     */
    const UNIT_CUBIC_FEET      = 'CUBIC_FEET';
    const UNIT_CUBIC_METER     = 'CUBIC_METER';
    const UNIT_DRAM            = 'DRAM';
    const UNIT_FLUID_OUNCE     = 'FLUID_OUNCE';
    const UNIT_IMPERIAL_GALLON = 'IMPERIAL_GALLON';
    const UNIT_LITER           = 'LITER';
    const UNIT_PINT            = 'PINT';
    const UNIT_QUART           = 'QUART';
    const UNIT_US_GALLON       = 'US_GALLON';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'CUBIC_METER';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array
    (
        'CUBIC_FEET'      => array(),
        'CUBIC_METER'     => array(),
        'DRAM'            => array(),
        'FLUID_OUNCE'     => array(),
        'IMPERIAL_GALLON' => array(),
        'LITER'           => array(),
        'PINT'            => array(),
        'QUART'           => array(),
        'US_GALLON'       => array()
    );
}
