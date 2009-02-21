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
 * Time units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Time extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_DAYS    = 'DAYS';
    const UNIT_HOURS   = 'HOURS';
    const UNIT_MINUTES = 'MINUTES';
    const UNIT_SECONDS = 'SECONDS';
    const UNIT_WEEKS   = 'WEEKS';
    const UNIT_YEARS   = 'YEARS';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'HOURS';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'DAYS'    => array(),
        'HOURS'   => array(),
        'MINUTES' => array(),
        'SECONDS' => array(),
        'WEEKS'   => array(),
        'YEARS'   => array()
    );
}
