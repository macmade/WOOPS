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
 * Temperature units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Temperature extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
