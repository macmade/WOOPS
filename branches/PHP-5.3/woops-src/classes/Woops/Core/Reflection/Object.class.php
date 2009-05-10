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
final class Object extends Base
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
    
    /**
     * 
     */
    public static function getInstance( $object )
    {
        return self::_getInstance(
            __CLASS__,
            '\ReflectionObject',
            array( $object )
        );
    }
    
    /**
     * 
     */
    public function isSingleton()
    {
        return $this->_reflector->implementsInterface( '\Woops\Core\Singleton\ObjectInterface' );
    }
    
    /**
     * 
     */
    public function isMultiSingleton()
    {
        return $this->_reflector->implementsInterface( '\Woops\Core\MultiSingleton\ObjectInterface' );
    }
    
    /**
     * 
     */
    public function isAopReady()
    {
        return $this->_reflector->isSubclassOf( '\Woops\Core\Aop\Advisor' );
    }
}
