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
 * Angle units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Angle extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_DEGREE = 'DEGREE';
    const UNIT_RADIAN = 'RADIAN';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'DEGREE';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'DEGREE' => array(),
        'RADIAN' => array()
    );
}
