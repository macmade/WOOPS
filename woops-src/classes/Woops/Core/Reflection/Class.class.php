<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * WOOPS PHP error exception class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
final class Woops_Core_Reflection_Class extends Woops_Core_Reflection_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    const IS_EXPLICIT_ABSTRACT = ReflectionClass::IS_EXPLICIT_ABSTRACT;
    const IS_FINAL             = ReflectionClass::IS_FINAL;
    const IS_IMPLICIT_ABSTRACT = ReflectionClass::IS_IMPLICIT_ABSTRACT;
    
    /**
     * 
     */
    public static function getInstance( $className )
    {
        return self::_getInstance(
            __CLASS__,
            'ReflectionClass',
            array( $className )
        );
    }
    
    /**
     * 
     */
    public function isSingleton()
    {
        return $this->_reflector->implementsInterface( 'Woops_Core_Singleton_Interface' );
    }
    
    /**
     * 
     */
    public function isMultiSingleton()
    {
        return $this->_reflector->implementsInterface( 'Woops_Core_MultiSingleton_Interface' );
    }
    
    /**
     * 
     */
    public function isAopReady()
    {
        return $this->_reflector->isSubclassOf( 'Woops_Core_Aop_Advisor' );
    }
}
