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
 * Parameter reflector
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
class ParameterReflector extends \Woops\Core\Reflection
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    protected $_hasDeclaringClass    = false;
    
    /**
     * 
     */
    protected $_hasDeclaringFunction = false;
    
    /**
     * 
     */
    protected $_hasClass             = false;
    
    /**
     * 
     */
    protected $_declaringClass       = NULL;
    
    /**
     * 
     */
    protected $_declaringFunction    = NULL;
    
    /**
     * 
     */
    protected $_class                = NULL;
    
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
    
    /**
     * 
     */
    public function getDeclaringFunction()
    {
        if( !$this->_hasDeclaringFunction ) {
            
            $declaringFunction        = $this->_reflector->getDeclaringFunction();
            
            if( get_class( $declaringFunction ) === 'ReflectionMethod' ) {
                
                $this->_declaringFunction = ClassReflector::getInstance(
                    $declaringFunction->getDeclaringClass()->getName(),
                    $declaringFunction->getName()
                );
                
            } else {
                
                $this->_declaringFunction = FunctionReflector::getInstance(
                    $declaringFunction->getName()
                );
            }
            
            $this->_hasDeclaringFunction = true;
        }
        
        return $this->_declaringFunction;
    }
    
    /**
     * 
     */
    public function getClass()
    {
        if( !$this->_hasClass ) {
            
            $class = $this->_reflector->getClass();
            
            if( $class ) {
                
                $this->_class = ClassReflector::getInstance(
                    $class->getName()
                );
                
            } else {
                
                $this->_class = $class;
            }
            
            $this->_hasClass = true;
        }
        
        return $this->_class;
    }
}
