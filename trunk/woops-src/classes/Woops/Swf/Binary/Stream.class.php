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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * SWF binary stream
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Binary
 */
class Woops_Swf_Binary_Stream extends Woops_Binary_Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the SWF data is compressed or not
     */
    protected $_isCompressed   = false;
    
    /**
     * Class constructor
     * 
     * @param   string  The binary data for which to create a stream
     * @return  void
     * @see     Woops_Binary_Stream::__construct
     */
    public function __construct( $data = '' )
    {
        // Calls the parent constructor
        parent::__construct( $data );
        
        // Checks if we have compressed data
        if( $data && substr( $this->_data, 0, 3 ) === 'CWS' ) {
            
            // Sets the compressed flag
            $this->_isCompressed = true;
        }
    }
    
    /**
     * Gets an encoded 32bits unsigned integer, as specified in the SWF specification
     * 
     * SWF 9 and later supports the use of integers encoded with a variable
     * number of bytes. One type of encoded integer is supported.
     * This is a 32-bit unsigned integer value encoded with a variable number of
     * bytes to save space. 
     * All EncodedU32's are encoded as 1-5 bytes depending on the value (larger
     * values need more space). The encoding method is if the hi bit in the
     * current byte is set, then the next byte is also part of the value. Each
     * bit in a byte contributes 7 bits to the value, with the hi bit telling us 
     * whether to use the next byte, or if this is the last byte for the value.
     * 
     * @return  int     The integer
     */
    public function encodedU32()
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
            return ( $byte1 & 0x7F ) | ( $byte2 << 7 );
        }
        
        // Gets the third byte
        $byte3 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte3 & 0x80 ) ) {
            
            // Returns the integer (21 bits) - Range is 0x00-0x1FFFFF
            return ( $byte1 & 0x7F ) | ( ( $byte2 & 0x7F ) << 7 ) | ( $byte3 << 14 );
        }
        
        // Gets the fourth byte
        $byte4 = $this->unsignedChar();
        
        // Checks the MSB
        if( !( $byte4 & 0x80 ) ) {
            
            // Returns the integer (28 bits) - Range is 0x00-0xFFFFFFF
            return ( $byte1 & 0x7F ) | ( ( $byte2 & 0x7F ) << 7 ) | ( ( $byte3 & 0x7F ) << 14 ) | ( $byte4 << 21 );
        }
        
        // Gets the fifth byte
        $byte5 = $this->unsignedChar();
        
        // Returns the integer (35 bits) - Range is 0x00-0x7FFFFFFFF
        return ( $byte1 & 0x7F ) | ( ( $byte2 & 0x7F ) << 7 ) | ( ( $byte3 & 0x7F ) << 14 ) | ( ( $byte4 & 0x7F ) << 21 ) | ( ( $byte5 & 0x7F ) << 28 );
    }
    
    /**
     * Writes an encoded 32bits unsigned integer, as specified in the SWF specification
     * 
     * @param   int     The integer
     * @return  void
     */
    public function writeEncodedU32( $int )
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
            $this->writeChar( $part2 );
            $this->writeChar( $part1 );
            
        } elseif( $int <= 0x1FFFFF ) {
            
            // Computes the 3 bytes of the integer
            $part1 = ( $int >> 16 ) | 0x80;
            $part2 = ( $int >> 8 )  | 0x80;
            $part3 =   $int & 0x7F;
            
            // Writes integer as 3 byte
            $this->writeChar( $part3 );
            $this->writeChar( $part2 );
            $this->writeChar( $part1 );
            
        } elseif( $int <= 0xFFFFFFF ) {
            
            // Computes the 4 bytes of the integer
            $part1 = ( $int >> 24 ) | 0x80;
            $part2 = ( $int >> 16 ) | 0x80;
            $part3 = ( $int >> 8 )  | 0x80;
            $part4 =   $int & 0x7F;
            
            // Writes integer as 4 byte
            $this->writeChar( $part4 );
            $this->writeChar( $part3 );
            $this->writeChar( $part2 );
            $this->writeChar( $part1 );
            
        }  elseif( $int <= 0x7FFFFFFFF ) {
            
            // Computes the 5 bytes of the integer
            $part2 = ( $int >> 32 ) | 0x80;
            $part2 = ( $int >> 24 ) | 0x80;
            $part3 = ( $int >> 16 ) | 0x80;
            $part4 = ( $int >> 8 )  | 0x80;
            $part5 =   $int & 0x7F;
            
            // Writes integer as 5 byte
            $this->writeChar( $part5 );
            $this->writeChar( $part4 );
            $this->writeChar( $part3 );
            $this->writeChar( $part2 );
            $this->writeChar( $part1 );
            
        } else {
            
            // Error - Integer is too big
            throw new Woops_Swf_Binary_Stream_Exception(
                'Invalid integer range (bigger than 2^35-1)',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_INVALID_INT_RANGE
            );
        }
    }
    
    /**
     * Compresses the SWF data in the stream
     * 
     * @return  void
     */
    public function compressData()
    {
        // Checks if the GZIP functions are available
        if( !function_exists( 'gzcompress' ) ) {
            
            // Error - No GZIP
            throw new Woops_Swf_Binary_Stream_Exception(
                'The PHP GZIP functions are not available',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_NO_GZIP
            );
        }
        
        // Checks if we have the SWF signature for a compressed file
        if( substr( $this->_data, 0, 3 ) === 'CWS' ) {
            
            // Checks if the data has already been compressed
            if( !$this->_isCompressed ) {
                
                // Compresses the compressed SWF data
                $this->_data         = substr( $this->_data, 0, 8 )
                                     . gzcompress( substr( $this->_data, 8 ) );
                
                // Data has been compressed
                $this->_isCompressed = true;
                
                // Updates the data length
                $this->_dataLength   = strlen( $this->_data );
            }
            
        } else {
            
            // Error - Invalid data
            throw new Woops_Swf_Binary_Stream_Exception(
                'Invalid SWF data',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_
            );
        }
    }
    
    /**
     * Uncompresses the SWF data in the stream
     * 
     * @return  void
     */
    public function uncompressData()
    {
        // Checks if the GZIP functions are available
        if( !function_exists( 'gzuncompress' ) ) {
            
            // Error - No GZIP
            throw new Woops_Swf_Binary_Stream_Exception(
                'The PHP GZIP functions are not available',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_NO_GZIP
            );
        }
        
        // Checks if we have the SWF signature for a compressed file
        if( substr( $this->_data, 0, 3 ) === 'CWS' ) {
            
            // Checks if the data has already been uncompressed
            if( $this->_isCompressed ) {
                
                // Uncompresses the compressed SWF data
                $this->_data         = substr( $this->_data, 0, 8 )
                                     . gzuncompress( substr( $this->_data, 8 ) );
                
                // Data has been uncompressed
                $this->_isCompressed = false;
                
                // Updates the data length
                $this->_dataLength   = strlen( $this->_data );
            }
            
        } else {
            
            // Error - Invalid data
            throw new Woops_Swf_Binary_Stream_Exception(
                'Invalid SWF data',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_INVALID_DATA
            );
        }
    }
}
