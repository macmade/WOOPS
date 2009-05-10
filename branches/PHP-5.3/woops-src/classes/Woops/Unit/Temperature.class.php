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
 * Temperature units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Temperature extends Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The available units
     */
    const UNIT_CELSIUS    = 'CELSIUS';
    const UNIT_FAHRENHEIT = 'FAHRENHEIT';
    const UNIT_KELVIN     = 'KELVIN';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'CELSIUS';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'CELSIUS'    => array(),
        'FAHRENHEIT' => array( array( '*' , 9 ), array( '/', 5 ), array( '+', 32 ) ),
        'KELVIN'     => array( array( '-', 273.16 ) )
    );
}
