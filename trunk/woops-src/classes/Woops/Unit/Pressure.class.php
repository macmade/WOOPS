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

/**
 * Pressure units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Pressure extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_ATMOSPHERES                = 'ATMOSPHERES';
    const UNIT_BARS                       = 'BARS';
    const UNIT_CENTIMETERS_OF_MERCURY     = 'CENTIMETERS_OF_MERCURY';
    const UNIT_INCHES_OF_MERCURY          = 'INCHES_OF_MERCURY';
    const UNIT_KILOGRAMS_PER_SQUARE_METER = 'KILOGRAMS_PER_SQUARE_METER';
    const UNIT_POUNDS_PER_SQUARE_FOOT     = 'POUNDS_PER_SQUARE_FOOT';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'BARS';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'ATMOSPHERES'                => array(),
        'BARS'                       => array(),
        'CENTIMETERS_OF_MERCURY'     => array(),
        'INCHES_OF_MERCURY'          => array(),
        'KILOGRAMS_PER_SQUARE_METER' => array(),
        'POUNDS_PER_SQUARE_FOOT'     => array()
    );
}
