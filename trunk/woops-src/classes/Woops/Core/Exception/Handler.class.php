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
 * WOOPS exception handler class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Exception
 */
final class Woops_Core_Exception_Handler
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Class constructor
     * 
     * The class constructor is private as all methods from this class are
     * static.
     * 
     * @return  NULL
     */
    private function __construct()
    {}
    
    /**
     * 
     */
    public static function handleException( Exception $e )
    {
        if( is_subclass_of( $e, 'Woops_Core_Exception_Base' ) ) {
            
            print $e;
            
        } else {
            
            $e = new Woops_Core_Php_Exception(
                'Exception of type ' . get_class( $e ) . ': ' . $e->getMessage(),
                $e->getCode(), $e->getTrace()
            );
            print $e;
        }
    }
}
