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
     * @return  int     The AMF integer
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
    }
    
    /**
     * Writes an AMF unsigned integer using 29bits coding, as specified in the
     * AMF 3 specification.
     * 
     * @param   int                                 The AMF integer
     * @return  void
     * @throws  Woops_Amf_Binary_Stream_Exception   If the range of the integer is invalid (> 2^29 - 1)
     */
    public function writeU29Integer( $int )
    {
        // Ensures we have an integer
        $int = ( int )$int;
        
        // Checks the integer range
        if( $int <= 0x7F ) {
            
            // Writes integer as 1 byte
            $this->writeChar( $int );
            
        } elseif( $int <= 0x3FFF ) {
            
            // Computes the 2 bytes of the integer
            $part1 = ( $int >> 8 ) | 0x80;
            $part2 =   $int & 0x7F;
            
            // Writes integer as 2 byte
            $this->writeChar( $part1 );
            $this->writeChar( $part2 );
            
        } elseif( $int <= 0x1FFFFF ) {
            
            // Computes the 3 bytes of the integer
            $part1 = ( $int >> 16 ) | 0x80;
            $part2 = ( $int >> 8 )  | 0x80;
            $part3 =   $int & 0x7F;
            
            // Writes integer as 3 byte
            $this->writeChar( $part1 );
            $this->writeChar( $part2 );
            $this->writeChar( $part3 );
            
        } elseif( $int <= 0x3FFFFFFF ) {
            
            // Computes the 4 bytes of the integer
            $part1 = ( $int >> 24 ) | 0x80;
            $part2 = ( $int >> 16 ) | 0x80;
            $part3 = ( $int >> 8 )  | 0x80;
            $part4 =   $int & 0xFF;
            
            // Writes integer as 4 byte
            $this->writeChar( $part1 );
            $this->writeChar( $part2 );
            $this->writeChar( $part3 );
            $this->writeChar( $part4 );
            
        } else {
            
            // Error - Integer is too big
            throw new Woops_Amf_Binary_Stream_Exception(
                'Invalid integer range (bigger than 2^29-1)',
                Woops_Amf_Binary_Stream_Exception::EXCEPTION_INVALID_INT_RANGE
            );
        }
    }
}
