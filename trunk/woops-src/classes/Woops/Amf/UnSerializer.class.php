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
 * AMF un-serializer
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf
 */
class Woops_Amf_UnSerializer
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The allowed AMF versions
     */
    protected static $_versions = array(
        0 => 'Woops_Amf_Packet_Amf0',
        3 => 'Woops_Amf_Packet_Amf3'
    );
    
    /**
     * The binary stream
     */
    protected $_stream          = NULL;
    
    /**
     * The AMF packet object
     */
    protected $_packet          = NULL;
    
    /**
     * Class constructor
     * 
     * @param   string  The AMF packet raw data
     * @return  void
     */
    public function __construct( $rawData )
    {
        // Creates the binary stream object
        $this->_stream  = new Woops_Amf_Binary_Stream( $rawData );
        
        // Gets the packet version
        $version        = $this->_stream->bigEndianUnsignedShort();
        
        // Checks the version
        if( !isset( self::$_versions[ $version ] ) ) {
            
            // Unsupported version
            throw new Woops_Amf_UnSerializer_Exception(
                'Invalid AMF version (' . $version . ')',
                Woops_Amf_UnSerializer_Exception::EXCEPTION_INVALID_VERSION
            );
        }
        
        // Class name for the AMF packet object
        $packetClass    = self::$_versions[ $version ];
        
        // Creates a new AMF packet object
        $this->_packet  = new $packetClass();
        
        // Gets the number of headers
        $headerCount    = $this->_stream->bigEndianUnsignedShort();
        
        // The type of the last header
        $lastHeaderType = 0;
        
        // Process the headers
        for( $i = 0; $i < $headerCount; $i++ ) {
            
            // Gets the header's informations
            $headerName     = $this->_stream->utf8String();
            $mustUnderstand = ( $this->_stream->unsignedChar() ) ? true : false;
            $headerLength   = $this->_stream->bigEndianUnsignedLong();
            $type           = $this->_stream->unsignedChar();
            
            // For AMF0, checks if the last marker was an AVM+ marker
            if( $version === 0 && $lastHeaderType === Woops_Amf_Packet_Amf0::MARKER_AVM_PLUS ) {
                
                // AMF3 marker for an AMF0 packet
                $type = $type | 0x1000;
            }
            
            // Stores the header's type
            $lastHeaderType = $type;
            
            // Creates the AMF header
            $this->_packet->newHeader(
                $headerName,
                $type,
                $mustUnderstand
            );
            
            // Process value here...
        }
        
        // Gets the number of messages
        $messageCount    = $this->_stream->bigEndianUnsignedShort();
        
        // The type of the last header
        $lastMessageType = 0;
        
        // Process the messages
        for( $i = 0; $i < $headerCount; $i++ ) {
            
            // Gets the message's informations
            $targetUri       = $this->_stream->utf8String();
            $responseUri     = $this->_stream->utf8String();
            $messageLength   = $this->_stream->bigEndianUnsignedLong();
            $type            = $this->_stream->unsignedChar();
            
            // For AMF0, checks if the last marker was an AVM+ marker
            if( $version === 0 && $lastHeaderType === Woops_Amf_Packet_Amf0::MARKER_AVM_PLUS ) {
                
                // AMF3 marker for an AMF0 packet
                $type = $type | 0x1000;
            }
            
            // Stores the message's type
            $lastMessageType = $type;
            
            // Creates the AMF message
            $this->_packet->newMessage(
                $targetUri,
                $responseUri,
                $type
            );
            
            // Process value here...
        }
    }
    
    /**
     * Gets the AMF packet object
     * 
     * @return  Woops_Amf_Packet    The AMF packet object
     */
    public function getPacket()
    {
        return $this->_packet;
    }
    
    /**
     * Gets an AMF header
     * 
     * @param   string  The name of the header
     * @return  mixed   An instance of Woops_Amf_Header if the message exists, otherwise NULL
     */
    public function getHeader( $name )
    {
        return $this->_packet->getHeader( $name );
    }
    
    /**
     * Gets an AMF message
     * 
     * @param   int     The message's index
     * @return  mixed   An instance of Woops_Amf_Message if the message exists, otherwise NULL
     */
    public function getMessage( $index )
    {
        return $this->_packet->getMessage( $index );
    }
    
    /**
     * Gets the AMF headers
     * 
     * @return  array   An array with instances of the Woops_Amf_Header class
     */
    public function getHeaders()
    {
        return $this->_packet->getHeaders();
    }
    
    /**
     * Gets the AMF messages
     * 
     * @return  array   An array with instances of the Woops_Amf_Message class
     */
    public function getMessages()
    {
        return $this->_packet->getMessages();
    }
}
