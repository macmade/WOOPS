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
namespace Woops\Soap;

/**
 * SOAP server class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Soap
 */
class Server extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The instance of the SOAP server
     */
    protected $_soapServer = NULL;
    
    /**
     * Class constructor
     * 
     * @param   string  The URL to the WSDL file
     * @return  void
     */
    public function __construct( $wsdl )
    {
        // Checks if the Soap_Server class is available
        if( !class_exists( 'Soap_Server' ) ) {
            
            // Error - SOAP support is disabled
            throw new Server\Exception(
                'The SoapServer class does not exist',
                Server\Exception::EXCEPTION_NO_SOAP
            );
        }
        
        // Checks if we have raw POST data
        if ( !isset( $GLOBALS[ 'HTTP_RAW_POST_DATA' ] ) ) {
            
            // Sets the raw POST data (compatibility issue)
            $GLOBALS[ 'HTTP_RAW_POST_DATA' ] = file_get_contents( 'php://input' );
        }
        
        // Creates the SOAP server
        $this->_soapServer = new SoapServer( $wsdl );    
    }
    
    /**
     * PHP method calls overloading
     * 
     * This method will reroute all the call on this object to the SOAP server
     * object.
     * 
     * @param   string                      The name of the called method
     * @param   array                       The arguments for the called method
     * @return  mixed                       The result of the SOAP server method called
     * @throws  Woops\Soap\Server\Exception If the called method does not exist
     */
    public function __call( $name, array $args = array() )
    {
        // Checks if the method can be called
        if( !is_callable( array( $this->_soapServer, $name ) ) ) {
            
            // Called method does not exist
            throw new Server\Exception(
                'The method \'' . $name . '\' cannot be called on the PDO object',
                Server\Exception::EXCEPTION_BAD_METHOD
            );
        }
        
        // Creates a callback
        $callback = new \Woops\Core\Callback( array( $this->_soapServer, $name ) );
        
        // Invokes the callback and returns it's result
        return $callback->invoke( $args );
    }
    
    /**
     * Sets the WSDL cache property (PHP configuration value)
     * 
     * @param   boolean Whether to cache the WSDL files
     * @return  boolean The old configuration value
     */
    public static function setWsdlCache( $value )
    {
        // Checks if we can call the ini_set() function
        if( is_callable( 'ini_set' ) && is_callable( 'ini_get' ) ) {
            
            // Gets the old value
            $oldValue = ini_get( 'soap.wsdl_cache_enabled' );
            
            // Sets the new value
            ini_set( 'soap.wsdl_cache_enabled', ( boolean )$value );
            
            // Returns the new value
            return $oldValue;
        }
        
        // The ini_set() function cannot be called
        throw new Server\Exception(
            'Cannot set the WSDL cache property through the ini_set() function',
            Server\Exception::EXCEPTION_NO_INI_SET
        );
    }
    
    /**
     * Sets the class that will handle the SOAP procedures
     * 
     * @param   string  The name of the class
     * @param   array   An optional array with the arguments to pass to the class constructor
     * @return  void
     */
    public function setHandlerClass( $className, array $args = array() )
    {
        $this->_soapServer->setClass( $className, $args );
        $this->_soapServer->handle();
    }
}
