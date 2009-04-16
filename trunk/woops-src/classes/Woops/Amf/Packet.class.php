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
     * Creates an AMF marker from a PHP primitive type
     * 
     * @param   mixed               The PHP variable
     * @return  Woops_Amf_Marker    The AMF marker
     */
    abstract public function newMarkerFromPhpVariable( $var );
    
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
        $stream = new Woops_Amf_Binary_Stream();
        
        // Writes the AMF version
        $stream->writeBigEndianUnsignedShort( $this->_version );
        
        // Writes the number of headers
        $stream->writeBigEndianUnsignedShort( $this->_headerCount );
        
        // Process each header
        foreach( $this->_headers as $header ) {
            
            // Writes the header
            $stream->write( ( string )$header );
        }
        
        // Writes the number of messages
        $stream->writeBigEndianUnsignedShort( $this->_messageCount );
        
        // Process each message
        foreach( $this->_messages as $message ) {
            
            // Writes the message
            $stream->write( ( string )$message );
        }
        
        // Returns the stream data
        return ( string )$stream;
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
     * Creates a new marker
     * 
     * @param   int                         The AMF marker type
     * @return  Woops_Amf_Marker            The AMF marker object
     * @throws  Woops_Amf_Marker_Exception  If the marker type is invalid
     */
    public function newMarker( $markerType )
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
        $marker      = new $markerClass( $this );
        
        // Returns the new marker
        return $marker;
    }
    
    /**
     * Creates a new AMF header
     * 
     * @param   string              The header's name
     * @param   int                 The marker's type (one of the MARKER_XXX constant)
     * @param   boolean             Whether the header must be understood
     * @return  Woops_Amf_Header    The AMF header object
     */
    public function newHeader( $name, $markerType, $mustUnderstand = false )
    {
        // Creates the new AMF header
        $header = new Woops_Amf_Header(
            $name,
            $this->newMarker( $markerType ),
            $mustUnderstand
        );
        
        // Stores the AMF header
        $this->_headers[] = $header;
        
        // Updates the number of headers
        $this->_headerCount++;
        
        // Returns the new AMF header
        return $header;
    }
    
    /**
     * Creates a new AMF header from a PHP primitive type
     * 
     * @param   string              The header's name
     * @param   mixed               The PHP variable
     * @param   boolean             Whether the header must be understood
     * @return  Woops_Amf_Header    The AMF header object
     */
    public function newHeaderFromPhpVariable( $name, $var, $markerType, $mustUnderstand = false )
    {
        // Creates the AMF marker object
        $marker = $this->newMarkerFromPhpVariable( $var );
        
        // Creates the new AMF header
        $header = new Woops_Amf_Header(
            $name,
            $marker,
            $mustUnderstand
        );
        
        // Stores the AMF message
        $this->_headers[] = $header;
        
        // Updates the number of messages
        $this->_headerCount++;
        
        // Returns the new AMF header
        return $header;
    }
    
    /**
     * Creates a new AMF message
     * 
     * @param   string              The target URI
     * @param   string              The response URI
     * @param   int                 The marker's type (one of the MARKER_XXX constant)
     * @return  Woops_Amf_Message   The AMF message object
     */
    public function newMessage( $targetUri, $responseUri, $markerType )
    {
        // Creates the new message
        $message = new Woops_Amf_Message(
            $targetUri,
            $responseUri,
            $this->newMarker( $markerType )
        );
        
        // Stores the AMF message
        $this->_messages[] = $message;
        
        // Updates the number of messages
        $this->_messageCount++;
        
        // Returns the new message
        return $message;
    }
    
    /**
     * Creates a new AMF message from a PHP primitive type
     * 
     * @param   string              The target URI
     * @param   string              The response URI
     * @param   mixed               The PHP variable
     * @return  Woops_Amf_Message   The AMF message object
     */
    public function newMessageFromPhpVariable( $targetUri, $responseUri, $var )
    {
        // Creates the AMF marker object
        $marker = $this->newMarkerFromPhpVariable( $var );
        
        // Creates the new message
        $message = new Woops_Amf_Message(
            $targetUri,
            $responseUri,
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
     * Gets an AMF header
     * 
     * @param   int     The header's index
     * @return  mixed   An instance of Woops_Amf_Header if the header exists, otherwise NULL
     */
    public function getHeader( $index )
    {
        return ( isset( $this->_headers[ $index ] ) ) ? $this->_headers[ $index ] : NULL;
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
     * @param   int The index of the AMF header
     * @return  void
     */
    public function removeHeader( $index )
    {
        // Checks if the header exists
        if( isset( $this->_headers[ $index ] ) ) {
            
            // Removes the header
            unset( $this->_headers[ $index ] );
            
            // Updates the number of headers
            $this->_messageCount--;
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
