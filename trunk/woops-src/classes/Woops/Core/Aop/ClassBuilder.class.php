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
namespace Woops\Core\Aop;

/**
 * AOP class builder
 * 
 * This class will process a class file and add the AOP method suffix
 * (the value of the Woops\Core\Aop\Advisor::JOINPOINT_METHOD_SUFFIX constant)
 * to every public member method, if the class is a subclass of the AOP advisor
 * class.
 * That behaviour will allow an automatic AOP join point, for all the public
 * member methods of the class.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Aop.Class
 */
class ClassBuilder extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The regular expression used to find the public member methods of  class,
     * even the abstract ones.
     */
    const PUBLIC_METHODS_REGEXP = '/([\s\t]*public\s+function\s+)([^_(]+)/';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic  = false;
    
    /**
     * The string utilities
     */
    protected static $_str      = NULL;
    
    /**
     * The PHP code of the class
     */
    protected $_classCode       = '';
    
    /**
     * The AOP version of the class PHP code
     */
    protected $_classAopCode    = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The name of the class for which to build an AOP version
     * @return  void
     */
    public function __construct( $className )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Checks if the class exists
        if( !class_exists( $className ) ) {
            
            // Error - No such class
            throw new ClassBuilder\Exception(
                'The class ' . $className . ' does not exist',
                ClassBuilder\Exception::EXCEPTION_NO_CLASS
            );
        }
        
        // Gets a reflection class object
        $reflection          = \Woops\Core\Reflection::getClassReflector( $className );
        
        // Gets the path to the PHP file
        $filePath            = $reflection->getFileName();
        
        // Gets the PHP code
        $this->_classCode    = file_get_contents( $filePath );
        
        // Checks if the class is a subclass of the AOP advisor class
        if( $reflection->isAopReady() ) {
            
            // Adds the AOP method suffix to all the public methods
            $this->_classAopCode = preg_replace(
                self::PUBLIC_METHODS_REGEXP,
                '\1\2' . \Woops\Core\Aop\Advisor::JOINPOINT_METHOD_SUFFIX,
                $this->_classCode
            );
            
        } else {
            
            // Nothing to do, the class does not have AOP features
            $this->_classAopCode = $this->_classCode;
        }
    }
    
    /**
     * Gets the AOP version of the class
     * 
     * @return  string  The PHP code of the AOP version of the class
     */
    public function __toString()
    {
        return $this->_classAopCode;
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str       = \Woops\Helpers\StringUtilities::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
}
