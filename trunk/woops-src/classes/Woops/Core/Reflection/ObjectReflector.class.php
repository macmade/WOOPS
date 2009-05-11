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
 * Object reflector
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
class ObjectReflector extends ClassReflector
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    const IS_EXPLICIT_ABSTRACT = \ReflectionObject::IS_EXPLICIT_ABSTRACT;
    const IS_FINAL             = \ReflectionObject::IS_FINAL;
    const IS_IMPLICIT_ABSTRACT = \ReflectionObject::IS_IMPLICIT_ABSTRACT;
}
