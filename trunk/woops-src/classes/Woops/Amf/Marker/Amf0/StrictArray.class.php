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
 * AMF0 strict array marker (0x0A)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Marker.Amf0
 */
class Woops_Amf_Marker_Amf0_StrictArray extends Woops_Amf_Marker_Amf0
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF marker type
     */
    protected $_type = 0x0A;
    
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
        
        // Gets the number of entries
        $entries = $stream->bigEndianUnsignedLong();
        
        // The type of the last entry
        $lastType = 0;
        
        // Process each entry
        for( $i = 0; $i < $entries; $i++ ) {
            
            // Gets the entry type
            $type = $stream->unsignedChar();
            
            // For AMF0, checks if the last marker was an AVM+ marker
            if( $lastType === Woops_Amf_Packet_Amf0::MARKER_AVM_PLUS ) {
                
                // AMF3 marker for an AMF0 packet
                $type = $type | 0x1000;
            }
            
            // Creates and stores the marker
            $marker               = $this->_packet->newMarker( $type );
            $this->_data->value[] = $marker;
            
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
        // Creates a new stream
        $stream = new Woops_Amf_Binary_Stream( parent::__toString() );
        
        // Checks for entries
        if( isset( $data->value ) && is_array( $data->value ) ) {
            
            // Writes the number of entries
            $stream->writeBigEndianUnsignedLong( count( $this->_data->value ) );
            
            // Process each entry
            foreach( $this->_data->value as $marker ) {
                
                // Writes the entry
                $stream->write( ( string )$marker );
            }
            
        } else {
            
            // No entry
            $stream->writeBigEndianUnsignedLong( 0 );
        }
        
        // Returns the stream data
        return ( string )$stream;
    }
}
