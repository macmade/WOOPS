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
 * Extension reflector
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
class ExtensionReflector extends \Woops\Core\Reflection
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    protected $_hasFunctions = false;
    
    /**
     * 
     */
    protected $_hasClasses   = false;
    
    /**
     * 
     */
    protected $_functions    = array();
    
    /**
     * 
     */
    protected $_classes      = array();
    
    /**
     * 
     */
    public function getFunctions()
    {
        if( !$this->_hasFunctions ) {
            
            $functions = $this->_reflector->getFunctions();
            
            foreach( $functions as $function ) {
                
                $this->_functions[ $function->getName() ] = FunctionReflector::getInstance(
                    $function->getName()
                );
            }
            
            $this->_hasFunctions = true;
        }
        
        return $this->_functions;
    }
    
    /**
     * 
     */
    public function getClasses()
    {
        if( !$this->_hasClasses ) {
            
            $classes = $this->_reflector->getClasses();
            
            foreach( $classes as $class ) {
                
                $this->_classes[ $class->getName() ] = ClassReflector::getInstance(
                    $class->getName()
                );
            }
            
            $this->_hasClasses = true;
        }
        
        return $this->_classes;
    }
}
