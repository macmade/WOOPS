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
 * Property reflector
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
class PropertyReflector extends \Woops\Core\Reflection
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    protected $_hasDeclaringClass = false;
    
    /**
     * 
     */
    protected $_declaringClass    = NULL;
    
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
    public function getDeclaringClass()
    {
        if( !$this->_hasDeclaringClass ) {
            
            $declaringClass        = $this->_reflector->getDeclaringClass();
            $this->_declaringClass = ClassReflector::getInstance(
                $declaringClass->getName()
            );
            
            $this->_hasDeclaringClass = true;
        }
        
        return $this->_declaringClass;
    }
}
