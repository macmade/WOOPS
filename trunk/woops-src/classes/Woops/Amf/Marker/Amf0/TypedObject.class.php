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
 * AMF0 typed object marker (0x10)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Marker.Amf0
 */
class Woops_Amf_Marker_Amf0_TypedObject extends Woops_Amf_Marker_Amf0
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF marker type
     */
    protected $_type = 0x10;
    
    /**
     * Processes the raw data for the marker
     * 
     * @param   Woops_Amf_Binary_Stream The AMF binary stream object
     * @return  void
     */
    public function processData( Woops_Amf_Binary_Stream $stream )
    {
        // Storage for the entries
        $this->_data->value = array();
        
        // Gets the class name
        $this->_data->class = $stream->utf8String();
        
        // The type of the last entry
        $lastType = 0;
        
        // Process each entry
        while( !$stream->endOfStream() ) {
            
            // Gets the entry name
            $name = $stream->utf8String();
            
            // Gets the entry type
            $type = $stream->unsignedChar();
            
            // Checks for the object-end marker
            if( $type === Woops_Amf_Packet_Amf0::MARKER_OBJECT_END ) {
                
                // No more properties
                break;
            }
            
            // For AMF0, checks if the last marker was an AVM+ marker
            if( $lastType === Woops_Amf_Packet_Amf0::MARKER_AVM_PLUS ) {
                
                // AMF3 marker for an AMF0 packet
                $type = $type | 0x1000;
            }
            
            // Creates and stores the marker
            $marker                      = $this->_packet->newMarker( $type );
            $this->_data->value[ $name ] = $marker;
            
            // Process the marker data
            $marker->processData( $stream );
            
            // Stores the entry's type
            $lastType = $type;
        }
    }
    
    /**
     * Gets the AMF marker as binary
     * 
     * @return  string  The AMF marker
     */
    public function __toString()
    {
        // Checks if we have a class name
        $class = ( isset( $this->_data->class ) ) ? ( string )$this->_data->class : '';
        
        // Creates a new stream
        $stream = new Woops_Amf_Binary_Stream( parent::__toString() );
        
        // Writes the class name
        $stream->writeUtf8String( $class );
        
        // Checks for properties
        if( isset( $this->_data->value ) && is_array( $this->_data->value ) ) {
            
            // Process each property
            foreach( $this->_data->value as $name => $marker ) {
                
                // Writes the property name
                $stream->writeUtf8String( $name );
                
                // Checks if we have a PHP variable or an AMF marker object
                if( !is_object( $marker ) || !( $marker instanceof Woops_Amf_Marker ) ) {
                    
                    // Creates the marker object from the PHP variable
                    $marker = $this->_packet->newMarkerFromPhpVariable( $marker );
                }
                
                // Writes the entry
                $stream->write( ( string )$marker );
            }
        }
        
        // Adds an object-end marker
        $end = $this->_packet->newMarker( Woops_Amf_Packet_Amf0::MARKER_OBJECT_END );
        $stream->write( ( string )$end );
        
        // Returns the stream data
        return ( string )$stream;
    }
}
