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
 * Function reflector
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
class FunctionReflector extends \Woops\Core\Reflection
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    protected $_hasParameters = false;
    
    /**
     * 
     */
    protected $_hasExtension  = false;
    
    /**
     * 
     */
    protected $_parameters    = array();
    
    /**
     * 
     */
    protected $_extension     = NULL;
    
    /**
     * 
     */
    public function getParameters()
    {
        if( !$this->_hasParameters ) {
            
            $parameters = $this->_reflector->getParameters();
            
            foreach( $parameters as $parameter ) {
                
                $this->_parameters[ $parameter->getName() ] = ParameterReflector::getInstance(
                    $this->_reflector->getName(),
                    $parameter->getName()
                );
            }
            
            $this->_hasParameters = true;
        }
        
        return $this->_parameters;
    }
    
    /**
     * 
     */
    public function getExtension()
    {
        if( !$this->_hasExtension ) {
            
            $extension = $this->_reflector->getExtension();
            
            if( $extension ) {
                
                $this->_extension = ExtensionReflector::getInstance(
                    $extension->getName()
                );
                
            } else {
                
                $this->_extension = $extension;
            }
            
            $this->_hasExtension = true;
        }
        
        return $this->_extension;
    }
}
