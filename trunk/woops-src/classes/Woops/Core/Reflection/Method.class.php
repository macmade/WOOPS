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
 * WOOPS PHP error exception class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
final class Woops_Core_Reflection_Method extends Woops_Core_Reflection_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    const IS_ABSTRACT  = ReflectionMethod::IS_ABSTRACT;
    const IS_FINAL     = ReflectionMethod::IS_FINAL;
    const IS_PRIVATE   = ReflectionMethod::IS_PRIVATE;
    const IS_PROTECTED = ReflectionMethod::IS_PROTECTED;
    const IS_PUBLIC    = ReflectionMethod::IS_PUBLIC;
    const IS_STATIC    = ReflectionMethod::IS_STATIC;
    
    /**
     * 
     */
    public static function getInstance( $class, $name )
    {
        return self::_getInstance(
            __CLASS__,
            'ReflectionMethod',
            array( $class, $name )
        );
    }
}
