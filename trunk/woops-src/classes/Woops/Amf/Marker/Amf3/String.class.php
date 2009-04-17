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
 * AMF3 string marker (0x06)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Marker.Amf3
 */
class Woops_Amf_Marker_Amf3_String extends Woops_Amf_Marker_Amf3
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF marker type
     */
    protected $_type        = 0x06;
    
    /**
     * Whether the current object is a reference
     */
    protected $_isReference = false;
    
    /**
     * The referenced marker, if the current object is a reference
     */
    protected $_reference   = NULL;
    
    /**
     * Processes the raw data for the marker
     * 
     * @param   Woops_Amf_Binary_Stream The AMF binary stream object
     * @return  void
     */
    public function processData( Woops_Amf_Binary_Stream $stream )
    {
        // Reads the length and reference flag
        $u29 = $stream->u29Integer();
        
        // Checks if we have a reference or not
        if( $u29 & 0x01 ) {
            
            // Gets the reference index
            $refIndex           = $u29 >> 1;
            
            // Sets the reference flag
            $this->_isReference = true;
            
            // Gets the referenced object
            $this->_reference   = $this->_packet->getStringReference( $refIndex );
            
            // Gets the data from the referenced object
            $this->_data        = $this->_reference->getData();
            
        } else {
            
            // Gets the string length
            $length = $u29 >> 1;
            
            // Stores the string
            $this->_data->value = $stream->read( $length );
        }
    }
    
    /**
     * Gets the AMF marker as binary
     * 
     * @return  string  The AMF marker
     */
    public function __toString()
    {
        // Checks if we have a reference or not
        if( $this->_isReference() === true ) {
            
            
        }
    }
}
