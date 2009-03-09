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
 * AMF3 integer marker (0x04)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Marker.Amf3
 */
class Woops_Amf_Marker_Amf3_Integer extends Woops_Amf_Marker_Amf3
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF marker type
     */
    protected $_type = 0x04;
    
    /**
     * Processes the raw data for the marker
     * 
     * @param   Woops_Amf_Binary_Stream The AMF binary stream object
     * @return  void
     */
    public function processData( Woops_Amf_Binary_Stream $stream )
    {
        $this->_data->value = $stream->u29Integer();
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
        
        // Writes the value
        $stream->writeU29Integer( $this->_data->value );
        
        // Resets the stream pointer
        $stream->rewind();
        
        // Returns the stream data
        return ( string )$stream;
    }
}
