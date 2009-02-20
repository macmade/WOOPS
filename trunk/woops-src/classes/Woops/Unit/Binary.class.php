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
 * Binary units
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
class Woops_Unit_Binary extends Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available units
     */
    const UNIT_BIT       = 'BIT';
    const UNIT_BYTE      = 'BYTE';
    const UNIT_KILOBYTE  = 'KILOBYTE';
    const UNIT_MEGABYTE  = 'MEGABYTE';
    const UNIT_GIGABYTE  = 'GIGABYTE';
    const UNIT_TERABYTE  = 'TERABYTE';
    const UNIT_PETABYTE  = 'PETABYTE';
    const UNIT_EXABYTE   = 'EXABYTE';
    const UNIT_ZETTABYTE = 'ZETTABYTE';
    const UNIT_YOTTABYTE = 'YOTTABYTE';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'BYTE';
    
    /**
     * The conversion operations for each unit from the default type
     */
    protected $_types = array(
        'BIT'       => array( array( '*' => 8 ) ),
        'BYTE'      => array(),
        'KILOBYTE'  => array( array( '/', 1024 ) ),
        'MEGABYTE'  => array( array( '/', 1048576 ) ),
        'GIGABYTE'  => array( array( '/', 1073741824 ) ),
        'TERABYTE'  => array( array( '/', 1099511627776 ) ),
        'PETABYTE'  => array( array( '/', 1125899906842624 ) ),
        'EXABYTE'   => array( array( '/', 1152921504606846976 ) ),
        'ZETTABYTE' => array( array( '/', 1180591620717411303424 ) ),
        'YOTTABYTE' => array( array( '/', 1208925819614629174706176 ) )
    );
}



        
