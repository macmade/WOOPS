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
namespace Woops\Core\Reflection;

/**
 * WOOPS PHP error exception class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
final class PropertyReflector extends Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    const IS_PRIVATE   = \ReflectionProperty::IS_PRIVATE;
    const IS_PROTECTED = \ReflectionProperty::IS_PROTECTED;
    const IS_PUBLIC    = \ReflectionProperty::IS_PUBLIC;
    const IS_STATIC    = \ReflectionProperty::IS_STATIC;
    
    /**
     * 
     */
    public static function getInstance( $class, $name )
    {
        return self::_getInstance(
            __CLASS__,
            '\ReflectionProperty',
            array( $class, $name )
        );
    }
}
