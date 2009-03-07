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
 * AMF binary stream
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Binary
 */
class Woops_Amf_Binary_Stream extends Woops_Binary_Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Gets an AMF unsigned integer using 29bits coding
     * 
     * @return  int                                 The AMF integer
     * @throws  Woops_Amf_Binary_Stream_Exception   If the range of the integer is invalid (> 2^29 - 1)
     */
    public function u29Integer()
    {
        // Gets the first byte
        $byte1 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte1 & 0x80 ) ) {
            
            // Returns the integer
            return $byte1 & 0x7F;
        }
        
        // Gets the second byte
        $byte2 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte2 & 0x8000 ) ) {
            
            // Returns the integer
            return ( $byte1 | ( $byte2 << 8 ) ) & 0x7FFF;
        }
        
        // Gets the third byte
        $byte3 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte3 & 0x800000 ) ) {
            
            // Returns the integer
            return ( $byte1 | ( $byte2 << 8 ) | ( $byte3 << 16 ) ) & 0x7FFFFF;
        }
        
        // Gets the fourth byte
        $byte4 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte4 & 0x80000000 ) ) {
            
            // Returns the integer
            return ( $byte1 | ( $byte2 << 8 ) | ( $byte3 << 16 ) | ( $byte4 << 24 ) ) & 0x7FFFFFFF;
        }
        
        // Invalid integer range
        throw new Woops_Amf_Binary_Stream_Exception(
            'Invalid AMF integer range',
            Woops_Amf_Binary_Stream_Exception::EXCEPTION_INVALID_INTEGER
        );
    }
}
