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
 * Area units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Area extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_ACRE              = 'ACRE';
    const UNIT_HECTARE           = 'HECTARE';
    const UNIT_SQUARE_CENTIMETER = 'SQUARE_CENTIMETER';
    const UNIT_SQUARE_FOOT       = 'SQUARE_FOOT';
    const UNIT_SQUARE_INCH       = 'SQUARE_INCH';
    const UNIT_SQUARE_KILOMETER  = 'SQUARE_KILOMETER';
    const UNIT_SQUARE_METER      = 'SQUARE_METER';
    const UNIT_SQUARE_MILE       = 'SQUARE_MILE';
    const UNIT_SQUARE_MILLIMETER = 'SQUARE_MILLIMETER';
    const UNIT_SQUARE_YARD       = 'SQUARE_YARD';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'SQUARE_METER';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'ACRE'              => array(),
        'HECTARE'           => array(),
        'SQUARE_CENTIMETER' => array(),
        'SQUARE_FOOT'       => array(),
        'SQUARE_INCH'       => array(),
        'SQUARE_KILOMETER'  => array(),
        'SQUARE_METER'      => array(),
        'SQUARE_MILE'       => array(),
        'SQUARE_MILLIMETER' => array(),
        'SQUARE_YARD'       => array()
    );
}
