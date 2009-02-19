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
    const BIT       = 'BIT';
    const BYTE      = 'BYTE';
    const KILOBYTE  = 'KILOBYTE';
    const MEGABYTE  = 'MEGABYTE';
    const GIGABYTE  = 'GIGABYTE';
    const TERABYTE  = 'TERABYTE';
    const PETABYTE  = 'PETABYTE';
    const EXABYTE   = 'EXABYTE';
    const ZETTABYTE = 'ZETTABYTE';
    const YOTTABYTE = 'YOTTABYTE';
    
    /**
     * The default unit
     */
    protected $_defaultType = 'BYTE';
    
    /**
     * The convertion operations for each unit from the default type
     */
    protected $_types = array(
        'BIT'       => array( array( '*' => '8' ) ),
        'BYTE'      => array(),
        'KILOBYTE'  => array( array( '/', '1024' ) ),
        'MEGABYTE'  => array( array( '/', '1048576' ) ),
        'GIGABYTE'  => array( array( '/', '1073741824' ) ),
        'TERABYTE'  => array( array( '/', '1099511627776' ) ),
        'PETABYTE'  => array( array( '/', '1125899906842624' ) ),
        'EXABYTE'   => array( array( '/', '1152921504606846976' ) ),
        'ZETTABYTE' => array( array( '/', '1180591620717411303424' ) ),
        'YOTTABYTE' => array( array( '/', '1208925819614629174706176' ) )
    );
}



        
