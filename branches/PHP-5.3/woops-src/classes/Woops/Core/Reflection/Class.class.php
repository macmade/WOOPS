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
final class Class extends Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    const IS_EXPLICIT_ABSTRACT = \ReflectionClass::IS_EXPLICIT_ABSTRACT;
    const IS_FINAL             = \ReflectionClass::IS_FINAL;
    const IS_IMPLICIT_ABSTRACT = \ReflectionClass::IS_IMPLICIT_ABSTRACT;
    
    /**
     * 
     */
    public static function getInstance( $className )
    {
        return self::_getInstance(
            __CLASS__,
            '\ReflectionClass',
            array( $className )
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
