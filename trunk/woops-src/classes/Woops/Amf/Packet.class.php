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
 * Abstract class for the AMF packet classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Packet
 */
abstract class Woops_Amf_Packet
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The allowed AMF markers
     */
    protected $_markers      = array();
    
    /**
     * The AMF packet version
     */
    protected $_version      = 0;
    
    /**
     * The AMF headers
     */
    protected $_headers      = array();
    
    /**
     * The AMF messages
     */
    protected $_messages     = array();
    
    /**
     * The number of AMF headers
     */
    protected $_headerCount  = 0;
    
    /**
     * The number of AMF messages
     */
    protected $_messageCount = 0;
    
    /**
     * Gets the AMF packet as binary
     * 
     * @return  string  The AMF packet
     */
    public function __toString()
    {
        // Creates a new binary stream
        $stream = new Woops_Binary_Stream();
        
        // Writes the AMF version
        $stream->writeUnsignedShort( $this->_version );
        
        // Writes the number of headers
        $stream->writeUnsignedShort( $this->_headerCount );
        
        // Process each header
        foreach( $this->_headers as $header ) {
            
            // Writes the header
            $stream->write( ( string )$header );
        }
        
        // Writes the number of messages
        $stream->writeUnsignedShort( $this->_messageCount );
        
        // Process each message
        foreach( $this->_messages as $message ) {
            
            // Writes the message
            $stream->write( ( string )$message );
        }
    }
    
    /**
     * Gets the AMF version
     * 
     * @return  int The AMF version
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * Creates a new AMF header
     * 
     * @param   string                      The header's name
     * @param   int                         The marker's type (one of the MARKER_XXX constant)
     * @param   boolean                     Whether the header must be understood
     * @return  Woops_Amf_header            The AMF message object
     * @throws  Woops_Amf_Packet_Exception  If a header with the same name already exists
     * @throws  Woops_Amf_Packet_Exception  If the marker type is not a valid AMF marker type
     */
    public function newHeader( $name, $markerType, $mustUnderstand = false )
    {
        // Checks if the header already exists
        if( isset( $this->_headers[ $name ] ) ) {
            
            // Error - The header already exist
            throw new Woops_Amf_Packet_Exception(
                'A header with the same name (' . $name . ') already exists',
                Woops_Amf_Packet_Exception::EXCEPTION_HEADER_EXISTS
            );
        }
        
        // Checks if the marker type is valid
        if( !isset( $this->_markers[ $markerType ] ) ) {
            
            // Error - Invalid marker type
            throw new Woops_Amf_Packet_Exception(
                'Invalid AMF marker type (' . $markerType . ')',
                Woops_Amf_Packet_Exception::EXCEPTION_INVALID_MARKER_TYPE
            );
        }
        
        // Creates a new marker
        $markerClass = $this->_markers[ $markerType ];
        $marker      = new $markerClass();
        
        // Creates and stores the new header
        $this->_headers[ $name ] = new Woops_Amf_Header(
            $name,
            $marker,
            $mustUnderstand
        );
        
        // Updates the number of headers
        $this->_headerCount++;
        
        // Returns the new header
        return $this->_headers[ $name ];
    }
    
    /**
     * Creates a new AMF message
     * 
     * @param   string                      The target URI
     * @param   string                      The request URI
     * @param   int                         The marker's type (one of the MARKER_XXX constant)
     * @return  Woops_Amf_Message           The AMF message object
     * @throws  Woops_Amf_Packet_Exception  If the marker type is not a valid AMF marker type
     */
    public function newMessage( $targetUri, $requestUri, $markerType )
    {
        // Checks if the marker type is valid
        if( !isset( $this->_markers[ $markerType ] ) ) {
            
            // Error - Invalid marker type
            throw new Woops_Amf_Packet_Exception(
                'Invalid AMF marker type (' . $markerType . ')',
                Woops_Amf_Packet_Exception::EXCEPTION_INVALID_MARKER_TYPE
            );
        }
        
        // Creates a new marker
        $markerClass = $this->_markers[ $markerType ];
        $marker      = new $markerClass();
        
        // Creates the new message
        $message = new Woops_Amf_Message(
            $targetUri,
            $requestUri,
            $marker
        );
        
        // Stores the AMF message
        $this->_messages[] = $message;
        
        // Updates the number of messages
        $this->_messageCount++;
        
        // Returns the new message
        return $message;
    }
    
    /**
     * Adds an AMF header
     * 
     * @param   Woops_Amf_Header            The AMF header object
     * @return  void
     * @throws  Woops_Amf_Packet_Exception  If a header with the same name already exists
     * @throws  Woops_Amf_Packet_Exception  If the AMF marker contained in the AMF header cannot be placed in the current AMF packet (depending on the AMF version)
     */
    public function addHeader( Woops_Amf_Header $header )
    {
        // Checks if the header already exists
        if( isset( $this->_headers[ $header->getName() ] ) ) {
            
            // Error - The header already exist
            throw new Woops_Amf_Packet_Exception(
                'A header with the same name (' . $header->getName() . ') already exists',
                Woops_Amf_Packet_Exception::EXCEPTION_HEADER_EXISTS
            );
        }
        
        // Checks the version of the AMF marker in the header
        if( $header->getMarker()->getVersion() !== $this->_version ) {
            
            // Error - Invalid AMF version
            throw new Woops_Amf_Packet_Exception(
                'Cannot add an AMF header containing an AMF' . $message->getMarker()->getVersion() . ' marker in an AMF' . $this->_version . ' packet',
                Woops_Amf_Packet_Exception::EXCEPTION_INVALID_MARKER_VERSION
            );
        }
        
        // Updates the number of headers
        $this->_headerCount++;
        
        // Stores the header object
        $this->_headers[ $header->getName() ] = $header;
    }
    
    /**
     * Adds an AMF message
     * 
     * @param   Woops_Amf_Message           The AMF message object
     * @return  void
     * @throws  Woops_Amf_Packet_Exception  If the AMF marker contained in the AMF message cannot be placed in the current AMF packet (depending on the AMF version)
     */
    public function addMessage( Woops_Amf_Message $message )
    {
        // Checks the version of the AMF marker in the message
        if( $message->getMarker()->getVersion() !== $this->_version ) {
            
            // Error - Invalid AMF version
            throw new Woops_Amf_Packet_Exception(
                'Cannot add an AMF message containing an AMF' . $message->getMarker()->getVersion() . ' marker in an AMF' . $this->_version . ' packet',
                Woops_Amf_Packet_Exception::EXCEPTION_INVALID_MARKER_VERSION
            );
        }
        
        // Updates the number of messages
        $this->_messageCount++;
        
        // Stores the message object
        $this->_messages[] = $message;
    }
    
    /**
     * Gets an AMF header
     * 
     * @param   string  The name of the header
     * @return  mixed   An instance of Woops_Amf_Header if the message exists, otherwise NULL
     */
    public function getHeader( $name )
    {
        return ( isset( $this->_headers[ $name ] ) ) ? $this->_headers[ $name ] : NULL;
    }
    
    /**
     * Gets an AMF message
     * 
     * @param   int     The message's index
     * @return  mixed   An instance of Woops_Amf_Message if the message exists, otherwise NULL
     */
    public function getMessage( $index )
    {
        return ( isset( $this->_messages[ $index ] ) ) ? $this->_messages[ $index ] : NULL;
    }
    
    /**
     * Gets the AMF headers
     * 
     * @return  array   An array with instances of the Woops_Amf_Header class
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    
    /**
     * Gets the AMF messages
     * 
     * @return  array   An array with instances of the Woops_Amf_Message class
     */
    public function getMessages()
    {
        return $this->_messages;
    }
    
    /**
     * Removes an AMF header
     * 
     * @param   string  The name of the AMF header
     * @return  void
     */
    public function removeHeader( $name )
    {
        // Checks if the header exists
        if( isset( $this->_headers[ $name ] ) ) {
            
            // Removes the header
            unset( $this->_headers[ $name ] );
            
            // Updates the number of headers
            $this->_headerCount--;
        }
    }
    
    /**
     * Removes an AMF message
     * 
     * @param   int The index of the AMF message
     * @return  void
     */
    public function removeMessage( $index )
    {
        // Checks if the header exists
        if( isset( $this->_messages[ $index ] ) ) {
            
            // Removes the header
            unset( $this->_messages[ $index ] );
            
            // Updates the number of headers
            $this->_messages--;
        }
    }
}
