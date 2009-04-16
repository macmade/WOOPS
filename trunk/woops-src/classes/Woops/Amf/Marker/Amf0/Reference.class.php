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
 * AMF0 reference marker (0x07)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Marker.Amf0
 */
class Woops_Amf_Marker_Amf0_Reference extends Woops_Amf_Marker_Amf0
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF marker type
     */
    protected $_type = 0x07;
    
    /**
     * Processes the raw data for the marker
     * 
     * @param   Woops_Amf_Binary_Stream The AMF binary stream object
     * @return  void
     */
    public function processData( Woops_Amf_Binary_Stream $stream )
    {
        // Gets the reference index
        $this->_data->value     = $stream->bigEndianUnsignedShort();
        
        // Gets the referenced marker object
        $this->_data->reference = $this->_packet->getReference( $this->_data->value );
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
        
        // Checks if we have an object or a reference index
        if( isset( $this->_data->value ) && is_object( $this->_data->value ) ) {
            
            // Gets and writes the reference index for the object
            $stream->writeBigEndignedUnsignedShort( $this->_packet->getReferenceIndex( $this->_data->value ) );
            
        } elseif( isset( $this->_data->value ) ) {
            
            // Writes the reference index
            $stream->writeBigEndignedUnsignedShort( $this->_data->value );
            
        } else {
            
            // No reference
            throw new Woops_Amf_Marker_Amf0_Reference_Exception(
                'Reference index is not set',
                Woops_Amf_Marker_Amf0_Reference_Exception::EXCEPTION_NO_REFERENCE
            );
        }
        
        // Returns the stream data
        return ( string )$stream;
    }
}
