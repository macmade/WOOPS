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

/**
 * AMF server
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf
 */
class Woops_Amf_Server
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF packet object for the AMF request
     */
    protected $_request         = NULL;
    
    /**
     * The AMF packet object for the AMF response
     */
    protected $_response        = NULL;
    
    /**
     * Writes the AMF response
     * 
     * @return  string  The AMF response
     */
    public function __toString()
    {
        return ( string )$this->_response;
    }
    
    /**
     * Creates a callback for an AMF message operation
     * 
     * @param   Woops_Amf_Message           The AMF message object
     * @return  Woops_Core_Callback_Helper  The callback object
     * @throws  Woops_Amf_Server_Exception  If the callback is invalid (class does not exist)
     * @throws  Woops_Amf_Server_Exception  If the callback is invalid (method does not exist)
     * @throws  Woops_Amf_Server_Exception  If the callback is invalid (function does not exist)
     */
    protected function _createMessageCallback( Woops_Amf_Message $message )
    {
        // Gets the target URI
        $target      = $message->getTargetUri();
        
        // Separates the function from the fully-qualified class name, if it exists
        $targetInfos = explode( '.', $target );
        
        // Checks if we have a class name
        if( isset( $targetInfos[ 1 ] ) ) {
            
            // Gets the PHP class name
            $className = str_replace( '/', '_', $targetInfos[ 0 ] );
            
            // Gets the method name
            $method    = $targetInfos[ 1 ];
            
            // Checks if we can call the method
            if( !class_exists( $className ) ) {
                
                // Error - The class does not exist
                throw new Woops_Amf_Server_Exception(
                    'Cannot use unexisting class \'' . $className . '\' as an AMF callback',
                    Woops_Amf_Server_Exception::EXCEPTION_INVALID_CLASS
                );
                
            } elseif( !method_exists( $className, $method ) ) {
                
                // Error - The method does not exist
                throw new Woops_Amf_Server_Exception(
                    'Cannot use unexisting method \'' . $className . '::' . $method . '\' as an AMF callback',
                    Woops_Amf_Server_Exception::EXCEPTION_INVALID_METHOD
                );
            }
            
            // Creates a reflection object for the method
            $reflection = Woops_Core_Reflection_Method::getInstance( $className, $method );
            
            // Checks if the method is static
            if( $reflection->isStatic() ) {
                
                // Creates a static callback
                $callback = new Woops_Core_Callback_Helper( array( $className, $method ) );
                
            } else {
                
                // Creates an object
                $object   = new $className();
                
                // Creates a member callback
                $callback = new Woops_Core_Callback_Helper( array( $object, $method ) );
                
            }
            
        } else {
            
            // Gets the function name
            $function  = $targetInfos[ 0 ];
            
            // Checks if the function can be called
            if( !function_exists( $function ) ) {
                
                // Error - The function does not exist
                throw new Woops_Amf_Server_Exception(
                    'Cannot use unexisting function \'' . $function . '\' as an AMF callback',
                    Woops_Amf_Server_Exception::EXCEPTION_INVALID_FUNCTION
                );
            }
            
            // Creates a function callback
            $callback = new Woops_Core_Callback_Helper( $function );
        }
        
        // Returns the callback object
        return $callback;
    }
    
    /**
     * Gets the AMF request packet object
     * 
     * @return  mixed    An instance of the Woops_Amf_Packet class if an AMF request was made, otherwise NULL
     */
    public function getRequest()
    {
        return $this->_request;
    }
    
    /**
     * Gets the AMF response packet object
     * 
     * @return  mixed    An instance of the Woops_Amf_Packet class if an AMF request was made, otherwise NULL
     */
    public function getResponse()
    {
        return $this->_response;
    }
    
    /**
     * 
     */
    public function handle( $data = '' )
    {
        // Checks if we must process specific data
        if( !$data ) {
            
            // No - Gets the data form the PHP input
            $data = file_get_contents( 'php://input' );
        }
        
        // Checks if there's AMF data to process
        if( $data ) {
            
            // Creates an AMF unserializer
            $amf             = new Woops_Amf_Unserializer( $data );
            
            // Stores the request packet object
            $this->_request  = $amf->getPacket();
            
            // Gets the request messages
            $messages        = $this->_request->getMessages();
            
            // Storage for the results
            $results         = array();
            
            // Gets the request AMF version
            $version         = $this->_request->getVersion();
            
            // Class name for the AMF packet response object
            $responseClass   = get_class( $this->_request );
            
            // Creates a new AMF packet object for the AMF response
            $this->_response = new $responseClass();
            
            // From now, error will be redirected to the AMF client
            try {
                
                // Process each message
                foreach( $messages as $message ) {
                    
                    // Creates the new callback for the current message operation
                    $callback = $this->_createMessageCallback( $message );
                    
                    // Gets the operation's result
                    $result   = $callback->invoke( $message->getMarker(), $this->_response, $this->_request );
                    
                    // Creates the response message
                    $this->_response->newMessageFromPhpVariable(
                        $message->getResponseUri() . '/onResult',
                        '',
                        $result
                    );
                }
                
            } catch( Exception $e ) {
                
                // Creates an object to store the exception informations
                $markerType = ( $version === 3 ) ? Woops_Amf_Packet_Amf3::MARKER_OBJECT : Woops_Amf_Packet_Amf0::MARKER_OBJECT;
                
                // Adds the error message
                $error = $this->_response->newMessage(
                    $message->getResponseUri() . '/onStatus',
                    '',
                    $markerType
                );
                
                // Adds the exception informations
                $data                     = $error->getMarker()->getData();
                $data->value              = array();
                $data->value[ 'message' ] = $e->getMessage();
                $data->value[ 'code' ]    = $e->getCode();
            }
        }
    }
}
