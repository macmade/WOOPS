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
 * Length units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Length extends Woops_Unit_Base
{
    /**
     * 
     */
    const MILLIMETER  = 'MILLIMETER';
    const CENTIMETER  = 'CENTIMETER';
    const DECIMETER   = 'DECIMETER';
    const METER       = 'METER';
    const KILOMETER   = 'KILOMETER';
    
    /**
     * 
     */
    protected $_defaultType = 'METER';
    
    /**
     * 
     */
    protected $_types = array(
        'MILLIMETER' => array( array( '*', 1000 ) ),
        'CENTIMETER' => array( array( '*', 100 ) ),
        'DECIMETER'  => array( array( '*', 10 ) ),
        'METER'      => array( array( '*', 1 ) ),
        'KILOMETER'  => array( array( '/', 1000 ) )
    );
}
