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

# $Id: ClassReflector.class.php 883 2009-05-12 02:52:00Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Core\Reflection;

/**
 * Namespace reflector
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
class NameSpaceReflector extends \Woops\Core\Reflection
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    protected $_nameSpace  = '';
    
    /**
     * 
     */
    protected $_classes    = array();
    
    /**
     * 
     */
    protected $_interfaces = array();
    
    /**
     * 
     */
    protected $_functions  = array();
    
    /**
     * 
     */
    public static function getInstance( $name )
    {
        $instance = parent::getInstance( $name );
        $instanceName     = $instance->getInstanceName();
        
        $instance->_nameSpace = ( substr( $instanceName, 0, 1 ) === '\\' ) ? substr( $instanceName, 1 ) : $instanceName;
        $instance->_nameSpace = ( substr( $instanceName, -1 )   !== '\\' ) ? $instanceName . '\\'       : $instanceName;
        
        return $instance;
    }
    
    /**
     * 
     */
    public function getClasses()
    {
        $classes = get_declared_classes();
        
        foreach( $classes as $className ) {
            
            if( strpos( $className, $this->_nameSpace ) === 0 ) {
                
                if( !isset( $this->_classes[ $className ] ) ) {
                    
                    $this->_classes[ $className ] = new ClassReflector( $className );
                }
            }
        }
        
        return $this->_classes;
    }
    
    /**
     * 
     */
    public function getInterfaces()
    {
        $interfaces = get_declared_interfaces();
        
        foreach( $interfaces as $interfaceName ) {
            
            if( strpos( $interfaceName, $this->_nameSpace ) === 0 ) {
                
                if( !isset( $this->_interfaces[ $interfaceName ] ) ) {
                    
                    $this->_interfaces[ $interfaceName ] = new ClassReflector( $interfaceName );
                }
            }
        }
        
        return $this->_interfaces;
    }
    
    /**
     * 
     */
    public function getFunctions()
    {
        $functions = get_defined_functions();
        
        if( isset( $functions[ 'user' ] ) ) {
            
            foreach( $functions[ 'user' ] as $functionName ) {
                
                if( stripos( $functionName, $this->_nameSpace ) === 0 ) {
                    
                    if( !isset( $this->_functions[ $functionName ] ) ) {
                        
                        $this->_functions[ $functionName ] = new FunctionReflector( $functionName );
                    }
                }
            }
        }
        
        return $this->_functions;
    }
    
    /**
     * 
     */
    public function classExists( $name )
    {
        $this->getClasses();
        return isset( $this->_classes[ ( string )$name ] );
    }
    
    /**
     * 
     */
    public function interfaceExists( $name )
    {
        $this->getInterfaces();
        return isset( $this->_interfaces[ ( string )$name ] );
    }
    
    /**
     * 
     */
    public function functionExists( $name )
    {
        $this->getFunctions();
        return isset( $this->_functions[ ( string )$name ] );
    }
}
