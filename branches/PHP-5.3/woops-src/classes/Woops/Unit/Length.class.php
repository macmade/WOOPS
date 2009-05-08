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
 * Length units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Length extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_CENTIMETER    = 'CENTIMETER';
    const UNIT_FOOT          = 'FOOT';
    const UNIT_INCH          = 'INCH';
    const UNIT_KILOMETER     = 'KILOMETER';
    const UNIT_METER         = 'METER';
    const UNIT_MILE          = 'MILE';
    const UNIT_NAUTICAL_MILE = 'NAUTICAL_MILE';
    const UNIT_YARDS         = 'YARDS';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'METER';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'CENTIMETER'    => array(),
        'FOOT'          => array(),
        'INCH'          => array(),
        'KILOMETER'     => array(),
        'METER'         => array(),
        'MILE'          => array(),
        'NAUTICAL_MILE' => array(),
        'YARDS'         => array()
    );
}
