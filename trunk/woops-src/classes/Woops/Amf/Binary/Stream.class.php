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
     * Gets an AMF unsigned integer using 29bits coding, as specified in the AMF
     * 3 specification.
     * 
     * AMF 3 makes use of a special compact format for writing integers to
     * reduce the number of bytes required for encoding. As with a normal 32-bit
     * integer, up to 4 bytes are required to hold the value however the high
     * bit of the first 3 bytes are used as flags to determine whether the next
     * byte is part of the integer. With up to 3 bits of the 32 bits being used
     * as flags, only 29 significant bits remain for encoding an integer. This
     * means the largest unsigned integer value that can be represented is
     * 2^29 - 1.
     * 
     * <code>
     * Hexadecimal             : Binary 
     * 0x00000000 - 0x0000007F : 0xxxxxxx 
     * 0x00000080 - 0x00003FFF : 1xxxxxxx 0xxxxxxx 
     * 0x00004000 - 0x001FFFFF : 1xxxxxxx 1xxxxxxx 0xxxxxxx 
     * 0x00200000 - 0x3FFFFFFF : 1xxxxxxx 1xxxxxxx 1xxxxxxx xxxxxxxx 
     * 0x40000000 - 0xFFFFFFFF : throw range exception 
     * </code>
     * 
     * In ABNF syntax, the variable length unsigned 29-bit integer type is
     * described as follows: 
     * 
     * <code>
     * U29 = U29-1 | U29-2 | U29-3 | U29-4 
     * U29-1 = %x00-7F 
     * U29-2 = %x80-FF %x00-7F 
     * U29-3 = %x80-FF %x80-FF %x00-7F 
     * U29-4 = %x80-FF %x80-FF %x80-FF %x00-FF
     * </code>
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
            
            // Returns the integer (7 bits) - Range is 0x00-0x7F
            return $byte1;
        }
        
        // Gets the second byte
        $byte2 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte2 & 0x80 ) ) {
            
            // Returns the integer (14 bits) - Range is 0x00-0x3FFF
            return ( ( $byte1 & 0x7F ) << 7 ) | $byte2;
        }
        
        // Gets the third byte
        $byte3 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte3 & 0x80 ) ) {
            
            // Returns the integer (21 bits) - Range is 0x00-1FFFFF
            return ( ( $byte1 & 0x7F ) << 14 ) | ( ( $byte2 & 0x7F ) << 7 ) | $byte3;
        }
        
        // Gets the fourth byte
        $byte4 = $this->unsignedChar();
        
        // Returns the integer (27 bits) - Range is 0x00-3FFFFFFF
        return ( ( $byte1 & 0x7F ) << 22 ) | ( ( $byte2 & 0x7F ) << 15 ) | ( ( $byte3 & 0x7F ) << 8 ) | $byte4;
        
        // The following processes are a bit slower, but might be more readable...
        
#        // Storage
#        $curByte = 0;
#        $int     = 0;
#        
#        // Process a maximum of 3 bytes
#        for( $i = 0; $i < 3; $i++ ) {
#            
#            // Gets the current byte
#            $curByte = $this->unsignedChar();
#            
#            // Adds the value of the current byte (7 bits only)
#            $int    |= $curByte & 0x7F;
#            
#            // Checks if we have to read another byte
#            if( !( $curByte & 0x80 ) ) {
#                
#                // Returns the integer
#                return $int;
#            }
#            
#            // Another byte will be read
#            $int <<= 7;
#        }
#        
#        // Last byte does not have a flag
#        $int <<= 1;
#        
#        // Returns the integer
#        return $int | $this->unsignedChar();
#        
#        // Storage
#        $curByte = 0;
#        $int     = 0;
#        
#        // Process a maximum of 3 bytes
#        for( $i = 0; $i < 4; $i++ ) {
#            
#            // Gets the current byte
#            $curByte = $this->unsignedChar();
#            
#            // Checks if we have to read another byte
#            if( !( $curByte & 0x80 ) ) {
#                
#                // Returns the integer
#                return $int | $curByte;
#                
#            } elseif( $i !== 3 ) {
#                
#                // Adds the value of the current byte (7 bits only)
#                $int = ( $int << 7 ) | ( $curByte & 0x7F );
#                
#            } else {
#                
#                // Returns the integer
#                return ( $int << 8 ) | $curByte;
#            }
#        }
    }
}
