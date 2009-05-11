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
 * Time units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Time extends Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
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
