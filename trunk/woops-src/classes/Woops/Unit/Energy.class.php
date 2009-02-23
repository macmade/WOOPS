<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Energy units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Energy extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_BTUS              = 'BTUS';
    const UNIT_CALORIES          = 'CALORIES';
    const UNIT_ERGS              = 'ERGS';
    const UNIT_FOOT_POUNDS       = 'FOOT_POUNDS';
    const UNIT_JOULES            = 'JOULES';
    const UNIT_KILOGRAM_CALORIES = 'KILOGRAM_CALORIES';
    const UNIT_KILOGRAM_METERS   = 'KILOGRAM_METERS';
    const UNIT_KILOWATT_HOURS    = 'KILOWATT_HOURS';
    const UNIT_NEWTON_METERS     = 'NEWTON_METERS';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'JOULES';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'BTUS'              => array(),
        'CALORIES'          => array(),
        'ERGS'              => array(),
        'FOOT_POUNDS'       => array(),
        'JOULES'            => array(),
        'KILOGRAM_CALORIES' => array(),
        'KILOGRAM_METERS'   => array(),
        'KILOWATT_HOURS'    => array(),
        'NEWTON_METERS'     => array()
    );
}
