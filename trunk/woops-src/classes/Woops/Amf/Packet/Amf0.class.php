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
 * AMF0 packet
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Packet
 */
class Woops_Amf_Packet_Amf0 extends Woops_Amf_Packet
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The allowed AMF markers
     */
    const MARKER_NUMBER          = 0x0000;
    const MARKER_BOOLEAN         = 0x0001;
    const MARKER_STRING          = 0x0002;
    const MARKER_OBJECT          = 0x0003;
    const MARKER_MOVIE_CLIP      = 0x0004;
    const MARKER_NULL            = 0x0005;
    const MARKER_UNDEFINED       = 0x0006;
    const MARKER_REFERENCE       = 0x0007;
    const MARKER_ECMA_ARRAY      = 0x0008;
    const MARKER_OBJECT_END      = 0x0009;
    const MARKER_STRICT_ARRAY    = 0x000A;
    const MARKER_DATE            = 0x000B;
    const MARKER_LONG_STRING     = 0x000C;
    const MARKER_UNSUPPORTED     = 0x000D;
    const MARKER_RECORDSET       = 0x000E;
    const MARKER_XML_DOCUMENT    = 0x000F;
    const MARKER_TYPED_OBJECT    = 0x0010;
    const MARKER_AVM_PLUS        = 0x0011;
    
    /**
     * The allowed AMF3 markers
     * 
     * AMF3 packets can be placed on an AMF0 packet, by using the AVM+
     * AMF0 marker, that specifies the next object is an AMF3 object.
     */
    const MARKER_AMF3_UNDEFINED  = 0x1000;
    const MARKER_AMF3_NULL       = 0x1001;
    const MARKER_AMF3_FALSE      = 0x1002;
    const MARKER_AMF3_TRUE       = 0x1003;
    const MARKER_AMF3_INTEGER    = 0x1004;
    const MARKER_AMF3_DOUBLE     = 0x1005;
    const MARKER_AMF3_STRING     = 0x1006;
    const MARKER_AMF3_XML_DOC    = 0x1007;
    const MARKER_AMF3_DATE       = 0x1008;
    const MARKER_AMF3_ARRAY      = 0x1009;
    const MARKER_AMF3_OBJECT     = 0x100A;
    const MARKER_AMF3_XML        = 0x100B;
    const MARKER_AMF3_BYTE_ARRAY = 0x100C;
    
    /**
     * The AMF packet version
     */
    protected $_version = 0;
    
    /**
     * The allowed AMF packets
     */
    protected $_markers = array(
        0x0000 => 'Woops_Amf_Marker_Amf0_Number',
        0x0001 => 'Woops_Amf_Marker_Amf0_Boolean',
        0x0002 => 'Woops_Amf_Marker_Amf0_String',
        0x0003 => 'Woops_Amf_Marker_Amf0_Object',
        0x0004 => 'Woops_Amf_Marker_Amf0_MovieClip',
        0x0005 => 'Woops_Amf_Marker_Amf0_Null',
        0x0006 => 'Woops_Amf_Marker_Amf0_Undefined',
        0x0007 => 'Woops_Amf_Marker_Amf0_Reference',
        0x0008 => 'Woops_Amf_Marker_Amf0_EcmaArray',
        0x0009 => 'Woops_Amf_Marker_Amf0_ObjectEnd',
        0x000A => 'Woops_Amf_Marker_Amf0_StrictArray',
        0x000B => 'Woops_Amf_Marker_Amf0_Date',
        0x000C => 'Woops_Amf_Marker_Amf0_LongString',
        0x000D => 'Woops_Amf_Marker_Amf0_Unsupported',
        0x000E => 'Woops_Amf_Marker_Amf0_Recordset',
        0x000F => 'Woops_Amf_Marker_Amf0_XmlDocument',
        0x0010 => 'Woops_Amf_Marker_Amf0_TypedObject',
        0x0011 => 'Woops_Amf_Marker_Amf0_AvmPlus'
    );
    
    /**
     * The allowed AMF3 markers
     * 
     * AMF3 packets can be placed on an AMF0 packet, by using the AVM+
     * AMF0 marker, that specifies the next object is an AMF3 object.
     */
    protected $_amf3Markers = array(
        0x1000 => 'Woops_Amf_Marker_Amf3_Undefined',
        0x1001 => 'Woops_Amf_Marker_Amf3_Null',
        0x1002 => 'Woops_Amf_Marker_Amf3_False',
        0x1003 => 'Woops_Amf_Marker_Amf3_True',
        0x1004 => 'Woops_Amf_Marker_Amf3_Integer',
        0x1005 => 'Woops_Amf_Marker_Amf3_Double',
        0x1006 => 'Woops_Amf_Marker_Amf3_String',
        0x1007 => 'Woops_Amf_Marker_Amf3_XmlDoc',
        0x1008 => 'Woops_Amf_Marker_Amf3_Date',
        0x1009 => 'Woops_Amf_Marker_Amf3_Array',
        0x100A => 'Woops_Amf_Marker_Amf3_Object',
        0x100B => 'Woops_Amf_Marker_Amf3_Xml',
        0x100C => 'Woops_Amf_Marker_Amf3_ByteArray'
    );
    
    /**
     * The reference table for the AMF markers, by index
     */
    protected $_references       = array();
    
    /**
     * The reference table for the AMF markers, by object hash
     */
    protected $_referencesByHash = array();
    
    /**
     * Gets the reference to an AMF marker
     * 
     * @param   int     The reference's index
     * @return  mixed   An instance of Woops_Amf_Marker if the marker exists, otherwise NULL
     */
    public function getReference( $index )
    {
        return ( isset( $this->_references[ $index ] ) ) ? $this->_references[ $index ] : NULL;
    }
    
    /**
     * Gets the reference index of an AMF marker
     * 
     * @param   Woops_Amf_Marker                The AMF marker
     * @return  int                             The index of the reference table for the AMF marker
     * @throws  Woops_Amf_Packet_Amf0_Exception If the marker does not exists in the reference table
     */
    public function getReferenceIndex( Woops_Amf_Marker $marker )
    {
        $hash = spl_object_hash( $marker );
        
        if( isset( $this->_referencesByHash[ $hash ] ) ) {
            
            return $this->_referencesByHash[ $hash ];
            
        } else {
            
            throw new Woops_Amf_Packet_Amf0_Exception(
                'Passed marker does not exist in the reference table',
                Woops_Amf_Packet_Amf0_Exception::EXCEPTION_NO_MARKER_REFERENCE
            );
        }
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
        // Checks if we are using an AMF3 marker
        if( $markerType & 0x1000 ) {
            
            // Checks if the marker type is valid
            if( !isset( $this->_amf3Markers[ $markerType ] ) ) {
                
                // Error - Invalid marker type
                throw new Woops_Amf_Packet_Exception(
                    'Invalid AMF marker type (' . $markerType . ')',
                    Woops_Amf_Packet_Exception::EXCEPTION_INVALID_MARKER_TYPE
                );
            }
            
            // Checks if an AVM+ marker was added
            if( !end( $this->_references ) || !( prev( $this->_references ) instanceof Woops_Amf_Marker_Amf0_AvmPlus ) ) {
                
                // Error - No AVM+ marker
                throw new Woops_Amf_Packet_Amf0_Exception(
                    'An AVM+ marker must be added before an AMF3 marker in an AMF0 packet',
                    Woops_Amf_Packet_Amf0_Exception::EXCEPTION_NO_AVM_PLUS
                );
            }
            
            // Resets the headers' array pointer
            rewind( $this->_references );
            
            // Creates a new marker
            $markerClass = $this->_amf3Markers[ $markerType ];
            $marker      = new $markerClass( $this );
            
            // Checks if the marker can be referenced
            if(    $markerType === self::MARKER_OBJECT
                || $markerType === self::MARKER_ECMA_ARRAY
                || $markerType === self::MARKER_STRICT_ARRAY
                || $markerType === self::MARKER_TYPED_OBJECT
            ) {
                
                // Adds the marker to the reference table
                $this->_references[]                                   = $marker;
                $this->_referencesByHash[ spl_object_hash( $marker ) ] = count( $this->_references - 1 );
            }
            
            // Returns the new marker
            return $marker;
            
        } else {
            
            // Calls the parent method
            return parent::newHeader( $markerType );
        }
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
     * @throws  Woops_Amf_Packet_Exception  If the previous AMF marker is not an AVM+ marker, when adding an AMF3 marker
     */
    public function newHeader( $name, $markerType, $mustUnderstand = false )
    {
        // Checks if we are using an AMF3 marker
        if( $markerType & 0x1000 ) {
            
            // Checks if the marker type is valid
            if( !isset( $this->_amf3Markers[ $markerType ] ) ) {
                
                // Error - Invalid marker type
                throw new Woops_Amf_Packet_Exception(
                    'Invalid AMF marker type (' . $markerType . ')',
                    Woops_Amf_Packet_Exception::EXCEPTION_INVALID_MARKER_TYPE
                );
            }
            
            // Checks if an AVM+ marker was added
            if( !end( $this->_headers ) || !( prev( $this->_headers->getMarker() ) instanceof Woops_Amf_Marker_Amf0_AvmPlus ) ) {
                
                // Error - No AVM+ marker
                throw new Woops_Amf_Packet_Amf0_Exception(
                    'An AVM+ marker must be added before an AMF3 marker in an AMF0 packet',
                    Woops_Amf_Packet_Amf0_Exception::EXCEPTION_NO_AVM_PLUS
                );
            }
            
            // Resets the headers' array pointer
            rewind( $this->_headers );
            
            // Creates a new marker
            $markerClass = $this->_amf3Markers[ $markerType ];
            $marker      = new $markerClass( $this );
            
            // Checks if the marker can be referenced
            if(    $markerType === self::MARKER_OBJECT
                || $markerType === self::MARKER_ECMA_ARRAY
                || $markerType === self::MARKER_STRICT_ARRAY
                || $markerType === self::MARKER_TYPED_OBJECT
            ) {
                
                // Adds the marker to the reference table
                $this->_references[]                                   = $marker;
                $this->_referencesByHash[ spl_object_hash( $marker ) ] = count( $this->_references - 1 );
            }
            
            // Creates the new AMF header
            $header = new Woops_Amf_Header(
                $name,
                $markerType,
                $mustUnderstand
            );
            
            // Stores the AMF message
            $this->_headers[] = $header;
            
            // Updates the number of messages
            $this->_headerCount++;
            
            // Returns the new AMF message
            return $header;
            
        } else {
            
            // Calls the parent method
            return parent::newHeader( $name, $markerType, $mustUnderstand );
        }
    }
    
    /**
     * Creates a new AMF message
     * 
     * @param   string                      The target URI
     * @param   string                      The response URI
     * @param   int                         The marker's type (one of the MARKER_XXX constant)
     * @return  Woops_Amf_Message           The AMF message object
     * @throws  Woops_Amf_Packet_Exception  If the marker type is not a valid AMF marker type
     * @throws  Woops_Amf_Packet_Exception  If the previous AMF marker is not an AVM+ marker, when adding an AMF3 marker
     */
    public function newMessage( $targetUri, $responseUri, $markerType )
    {
        // Checks if we are using an AMF3 marker
        if( $markerType & 0x1000 ) {
            
            // Checks if the marker type is valid
            if( !isset( $this->_amf3Markers[ $markerType ] ) ) {
                
                // Error - Invalid marker type
                throw new Woops_Amf_Packet_Exception(
                    'Invalid AMF marker type (' . $markerType . ')',
                    Woops_Amf_Packet_Exception::EXCEPTION_INVALID_MARKER_TYPE
                );
            }
            
            // Checks if an AVM+ marker was added
            if( !end( $this->_messages ) || !( prev( $this->_messages->getMarker() ) instanceof Woops_Amf_Marker_Amf0_AvmPlus ) ) {
                
                // Error - No AVM+ marker
                throw new Woops_Amf_Packet_Amf0_Exception(
                    'An AVM+ marker must be added before an AMF3 marker in an AMF0 packet',
                    Woops_Amf_Packet_Amf0_Exception::EXCEPTION_NO_AVM_PLUS
                );
            }
            
            // Resets the messages' array pointer
            rewind( $this->_messages );
            
            // Creates a new marker
            $markerClass = $this->_amf3Markers[ $markerType ];
            $marker      = new $markerClass( $this );
            
            // Checks if the marker can be referenced
            if(    $markerType === self::MARKER_OBJECT
                || $markerType === self::MARKER_ECMA_ARRAY
                || $markerType === self::MARKER_STRICT_ARRAY
                || $markerType === self::MARKER_TYPED_OBJECT
            ) {
                
                // Adds the marker to the reference table
                $this->_references[]                                   = $marker;
                $this->_referencesByHash[ spl_object_hash( $marker ) ] = count( $this->_references - 1 );
            }
            
            // Creates the new AMF message
            $message = new Woops_Amf_Message(
                $targetUri,
                $responseUri,
                $marker
            );
            
            // Stores the AMF message
            $this->_messages[] = $message;
            
            // Updates the number of messages
            $this->_messageCount++;
            
            // Returns the new AMF message
            return $message;
            
        } else {
            
            // Calls the parent method
            return parent::newMessage( $targetUri, $responseUri, $markerType );
        }
    }
}
