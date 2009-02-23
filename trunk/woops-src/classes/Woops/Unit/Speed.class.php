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
 * Speed units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Speed extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_FEET_PER_MINUTE       = 'FEET_PER_MINUTE';
    const UNIT_FEET_PER_SECOND       = 'FEET_PER_SECOND';
    const UNIT_KILOMETERS_PER_HOUR   = 'KILOMETERS_PER_HOUR';
    const UNIT_KILOMETERS_PER_MINUTE = 'KILOMETERS_PER_MINUTE';
    const UNIT_KNOTS                 = 'KNOTS';
    const UNIT_METERS_PER_SECOND     = 'METERS_PER_SECOND';
    const UNIT_MILES_PER_HOUR        = 'MILES_PER_HOUR';
    const UNIT_MILES_PER_MINUTE      = 'MILES_PER_MINUTE';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'KILOMETERS_PER_HOUR';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'FEET_PER_MINUTE'       => array(),
        'FEET_PER_SECOND'       => array(),
        'KILOMETERS_PER_HOUR'   => array(),
        'KILOMETERS_PER_MINUTE' => array(),
        'KNOTS'                 => array(),
        'METERS_PER_SECOND'     => array(),
        'MILES_PER_HOUR'        => array(),
        'MILES_PER_MINUTE'      => array()
    );
}
