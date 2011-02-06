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
 * Angle units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Angle extends Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The available units
     */
    const UNIT_DEGREE = 'DEGREE';
    const UNIT_MINUTE = 'MINUTE';
    const UNIT_SECOND = 'SECOND';
    const UNIT_RADIAN = 'RADIAN';
    const UNIT_GRAD   = 'GRAD';
    const UNIT_MIL    = 'MIL';
    const UNIT_CIRCLE = 'CIRCLE';
    const UNIT_POINT  = 'POINT';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'DEGREE';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array
    (
        'DEGREE' => array(),
        'MINUTE' => array( array( '/', 60 ) ),
        'SECOND' => array( array( '/', 3600 ) ),
        'RADIAN' => array( array( '*', M_PI ), array( '/', 180 ) ),
        'GRAD'   => array( array( '*', 10 ), array( '/', 9 ) ),
        'MIL'    => array( array( '*', 160 ), array( '/', 9 ) ),
        'CIRCLE' => array( array( '/', 360 ) ),
        'POINT'  => array( array( '*', 16 ), array( '/', 180 ) )
    );
}
