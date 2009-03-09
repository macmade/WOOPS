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
 * AMF0 number marker (0x00)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Marker.Amf0
 */
class Woops_Amf_Marker_Amf0_Number extends Woops_Amf_Marker_Amf0
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF marker type
     */
    protected $_type = 0x00;
    
    /**
     * Processes the raw data for the marker
     * 
     * @param   Woops_Amf_Binary_Stream The AMF binary stream object
     * @return  void
     */
    public function processData( Woops_Amf_Binary_Stream $stream )
    {
        // Gets the double number
        $this->_data->value = $stream->double();
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
        
        // Writes the double number
        $stream->writeDouble( $this->_data->value );
        
        // Returns the stream data
        return ( string )$stream;
    }
}
