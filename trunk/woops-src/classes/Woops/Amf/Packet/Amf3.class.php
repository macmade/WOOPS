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
 * AMF3 packet
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Packet
 */
class Woops_Amf_Packet_Amf3 extends Woops_Amf_Packet
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The allowed AMF markers
     */
    const MARKER_UNDEFINED  = 0x0000;
    const MARKER_NULL       = 0x0001;
    const MARKER_FALSE      = 0x0002;
    const MARKER_TRUE       = 0x0003;
    const MARKER_INTEGER    = 0x0004;
    const MARKER_DOUBLE     = 0x0005;
    const MARKER_STRING     = 0x0006;
    const MARKER_XML_DOC    = 0x0007;
    const MARKER_DATE       = 0x0008;
    const MARKER_ARRAY      = 0x0009;
    const MARKER_OBJECT     = 0x000A;
    const MARKER_XML        = 0x000B;
    const MARKER_BYTE_ARRAY = 0x000C;
    
    /**
     * The AMF packet version
     */
    protected $_version = 3;
    
    /**
     * The allowed AMF markers
     */
    protected $_markers = array(
        0x0000 => 'Woops_Amf_Marker_Amf3_Undefined',
        0x0001 => 'Woops_Amf_Marker_Amf3_Null',
        0x0002 => 'Woops_Amf_Marker_Amf3_False',
        0x0003 => 'Woops_Amf_Marker_Amf3_True',
        0x0004 => 'Woops_Amf_Marker_Amf3_Integer',
        0x0005 => 'Woops_Amf_Marker_Amf3_Double',
        0x0006 => 'Woops_Amf_Marker_Amf3_String',
        0x0007 => 'Woops_Amf_Marker_Amf3_XmlDoc',
        0x0008 => 'Woops_Amf_Marker_Amf3_Date',
        0x0009 => 'Woops_Amf_Marker_Amf3_Array',
        0x000A => 'Woops_Amf_Marker_Amf3_Object',
        0x000B => 'Woops_Amf_Marker_Amf3_Xml',
        0x000C => 'Woops_Amf_Marker_Amf3_ByteArray'
    );
    
    /**
     * The reference table for the strings, by index
     */
    protected $_stringReferences       = array();
    
    /**
     * The reference table for the strings, by object hash
     */
    protected $_stringReferencesByHash = array();
    
    /**
     * The reference table for the objects, by index
     */
    protected $_objectReferences       = array();
    
    /**
     * The reference table for the objects, by object hash
     */
    protected $_objectReferencesByHash = array();
    
    /**
     * The reference table for the traits, by index
     */
    protected $_traitReferences        = array();
    
    /**
     * The reference table for the traits, by object hash
     */
    protected $_traitReferencesByHash  = array();
    
    /**
     * Creates an AMF marker from a PHP primitive type
     * 
     * @param   mixed               The PHP variable
     * @return  Woops_Amf_Marker    The AMF marker
     */
    public function newMarkerFromPhpVariable( $var )
    {
        // Checks the variable type
        if( is_string( $var ) ) {
            
            // String
            $marker                   = $this->newMarker( self::MARKER_STRING );
            $marker->getData()->value = $var;
            
        } elseif( is_int( $var ) ) {
            
            // Number
            $marker                   = $this->newMarker( self::MARKER_INTEGER );
            $marker->getData()->value = $var;
            
        } elseif( is_double( $var ) || is_float( $var ) ) {
            
            // Number
            $marker                   = $this->newMarker( self::MARKER_DOUBLE );
            $marker->getData()->value = $var;
            
        } elseif( is_array( $var ) ) {
            
            // Array
            $marker      = $this->newMarker( self::MARKER_ARRAY );
            
            // Gets the data
            $data        = $marker->getData();
            
            // Storage
            $data->value = array();
            
            // Process each entry
            foreach( $var as $key => $value ) {
                
                // Creates the marker for the current entry data
                $data->value[ $key ] = $this->newMarkerFromPhpVariable( $value );
            }
            
        } elseif( is_object( $var ) ) {
            
            // Object
            $marker      = $this->newMarker( self::MARKER_OBJECT );
            
            // Gets the data
            $data        = $marker->getData();
            
            // Storage
            $data->value = array();
            
            // Process each entry
            foreach( $var as $key => $value ) {
                
                // Creates the marker for the current entry data
                $data->value[ $key ] = $this->newMarkerFromPhpVariable( $value );
            }
            
        } elseif( is_bool( $var ) && $var === true ) {
            
            // Boolean
            $marker                   = $this->newMarker( self::MARKER_TRUE );
            
        }  elseif( is_bool( $var ) && $var === false ) {
            
            // Boolean
            $marker                   = $this->newMarker( self::MARKER_FALSE );
            
        } else {
            
            // Unknown type - Creates a NULL marker
            $marker = $this->newMarker( self::MARKER_NULL );
        }
        
        // Returns the AMF marker
        return $marker;
    }
    
    /**
     * Creates a new marker
     * 
     * @param   int                         The AMF marker type
     * @return  Woops_Amf_Marker            The AMF marker object
     */
    public function newMarker( $markerType )
    {
        // Calls the parent method
        $marker = parent::newMarker( $markerType );
        
        // Checks if the marker can be referenced
        if( $markerType === self::MARKER_STRING ) {
            
            // Adds the marker to the string reference table
            $this->_stringReferences[]                                   = $marker;
            $this->_stringReferencesByHash[ spl_object_hash( $marker ) ] = count( $this->_stringReferences ) - 1;
            
        } elseif(    $markerType === self::MARKER_OBJECT
                  || $markerType === self::MARKER_ARRAY
                  || $markerType === self::MARKER_XML
                  || $markerType === self::MARKER_XML_DOC
                  || $markerType === self::MARKER_BYTE_ARRAY
                  || $markerType === self::MARKER_DATE
        ) {
            
            // Adds the marker to the object reference table
            $this->_objectReferences[]                                   = $marker;
            $this->_objectReferencesByHash[ spl_object_hash( $marker ) ] = count( $this->_objectReferences ) - 1;
            
        } elseif( false ) {
            
            // Object traits... What's an object trait anyway?!?
        }
        
        // Returns the new marker
        return $marker;
    }
}
