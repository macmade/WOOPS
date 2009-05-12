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
 * PHP namespace reflector
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
     * The PHP namespace
     */
    protected $_nameSpace  = '';
    
    /**
     * The classes belonging to the namespace
     */
    protected $_classes    = array();
    
    /**
     * The interfaces belonging to the namespace
     */
    protected $_interfaces = array();
    
    /**
     * The functions belonging to the namespace
     */
    protected $_functions  = array();
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops\Core\Singleton\ObjectInterface    The unique instance of the class
     * @see     __construct
     */
    public static function getInstance( $name )
    {
        // Gets the instance, and the instance name
        $instance     = parent::getInstance( $name );
        $instanceName = $instance->getInstanceName();
        
        // Sets the namespace
        $instance->_nameSpace = ( substr( $instanceName, 0, 1 ) === '\\' ) ? substr( $instanceName, 1 ) : $instanceName;
        $instance->_nameSpace = ( substr( $instanceName, -1 )   !== '\\' ) ? $instanceName . '\\'       : $instanceName;
        
        // Returns the instance
        return $instance;
    }
    
    /**
     * Gets all loaded classes belonging to the namespace
     * 
     * @return  array   An array with instances of Woops\Core\Reflection\ClassReflector
     */
    public function getClasses()
    {
        // Gets the declared classes
        $classes = get_declared_classes();
        
        // Process each class
        foreach( $classes as $className ) {
            
            // Checks if the class belongs to the namespace
            if( strpos( $className, $this->_nameSpace ) === 0 ) {
                
                // Checks if we already have the class
                if( !isset( $this->_classes[ $className ] ) ) {
                    
                    // Gets the class reflector
                    $this->_classes[ $className ] = new ClassReflector( $className );
                }
            }
        }
        
        // Returns the namespace classes
        return $this->_classes;
    }
    
    /**
     * Gets all loaded interfaces belonging to the namespace
     * 
     * @return  array   An array with instances of Woops\Core\Reflection\ClassReflector
     */
    public function getInterfaces()
    {
        // Gets the declared interfaces
        $interfaces = get_declared_interfaces();
        
        // Process each interface
        foreach( $interfaces as $interfaceName ) {
            
            // Checks if the interface belongs to the namespace
            if( strpos( $interfaceName, $this->_nameSpace ) === 0 ) {
                
                // Checks if we already have the interface
                if( !isset( $this->_interfaces[ $interfaceName ] ) ) {
                    
                    // Gets the interface reflector
                    $this->_interfaces[ $interfaceName ] = new ClassReflector( $interfaceName );
                }
            }
        }
        
        // Returns the namespace interfaces
        return $this->_interfaces;
    }
    
    /**
     * Gets all loaded functions belonging to the namespace
     * 
     * @return  array   An array with instances of Woops\Core\Reflection\FunctionReflector
     */
    public function getFunctions()
    {
        // Gets the declared interfaces
        $functions = get_defined_functions();
        
        // Checks for user-defined functions
        if( isset( $functions[ 'user' ] ) ) {
            
            // Process each interface
            foreach( $functions[ 'user' ] as $functionName ) {
                
                // Checks if the interface belongs to the namespace
                if( stripos( $functionName, $this->_nameSpace ) === 0 ) {
                    
                    // Checks if we already have the interface
                    if( !isset( $this->_functions[ $functionName ] ) ) {
                        
                        // Gets the function reflector
                        $this->_functions[ $functionName ] = new FunctionReflector( $functionName );
                    }
                }
            }
        }
        
        // Returns the namespace functions
        return $this->_functions;
    }
    
    /**
     * Checks if a class exists in the namespace
     * 
     * @return  boolean True if the class exists in the namespace, otherwise false
     */
    public function classExists( $name )
    {
        // Ensures we have a string value
        $name = ( string )$name;
        
        // Checks for a leading backslash
        if( substr( $name, 0, 1 ) === '\\' ) {
            
            // Removes the leading backslash
            $name = substr( $name, 1 );
        }
        
        // Updates the namespace classes
        $this->getClasses();
        
        // Returns true if the class exists in the namespace
        return isset( $this->_classes[ $name ] );
    }
    
    /**
     * Checks if a class exists in the namespace
     * 
     * @return  boolean True if the class exists in the namespace, otherwise false
     */
    public function interfaceExists( $name )
    {
        // Ensures we have a string value
        $name = ( string )$name;
        
        // Checks for a leading backslash
        if( substr( $name, 0, 1 ) === '\\' ) {
            
            // Removes the leading backslash
            $name = substr( $name, 1 );
        }
        
        // Updates the namespace interfaces
        $this->getInterfaces();
        
        // Returns true if the interface exists in the namespace
        return isset( $this->_classes[ $name ] );
    }
    
    /**
     * Checks if a class exists in the namespace
     * 
     * @return  boolean True if the class exists in the namespace, otherwise false
     */
    public function functionExists( $name )
    {
        // Ensures we have a string value
        $name = ( string )$name;
        
        // Checks for a leading backslash
        if( substr( $name, 0, 1 ) === '\\' ) {
            
            // Removes the leading backslash
            $name = substr( $name, 1 );
        }
        
        // Updates the namespace functions
        $this->getFunctions();
        
        // Returns true if the function exists in the namespace
        return isset( $this->_functions[ $name ] );
    }
    
    /**
     * Gets the namespace name
     * 
     * @return  string  The namespace name
     */
    public function getName()
    {
        return $this->_nameSpace;
    }
}
