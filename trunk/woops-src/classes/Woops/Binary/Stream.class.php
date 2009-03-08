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
 * Binary stream
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Binary
 */
class Woops_Binary_Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Seek position equals to offset bytes
     */
    const SEEK_SET = SEEK_SET;
    
    /**
     * Seek position equals current location plus offset
     */
    const SEEK_CUR = SEEK_CUR;
    
    /**
     * Seek position equals end-of-file plus offset
     */
    const SEEK_END = SEEK_END;
    
    /**
     * The dividers values for the fixed point methods
     */
    protected static $_dividers = array(
        2  => 4,            // 1 << 2  - (2 ** 2)  - For the 30.2 fixed point numbers
        8  => 256,          // 1 << 8  - (2 ** 8)  - For the 8.8 fixed point numbers
        16 => 65536,        // 1 << 16 - (2 ** 16) - For the 16.16 fixed point numbers
        30 => 1073741824    // 1 << 30 - (2 ** 30) - For the 2.30 fixed point numbers
    );
    
    /**
     * The binary data
     */
    protected $_data            = '';
    
    /**
     * Length of the data
     */
    protected $_dataLength      = 0;
    
    /**
     * The data offset (in bytes)
     */
    protected $_offset          = 0;
    
    /**
     * Class constructor
     * 
     * @param   string  The binary data for which to create a stream
     * @return  void
     */
    public function __construct( $data = '' )
    {
        // Stores the binary data
        $this->_data       = $data;
        
        // Stores the data length
        $this->_dataLength = strlen( $data );
    }
    
    /**
     * Unpacks data to the specified format
     * 
     * @param   string                          The unpack format (see function unpack())
     * @return  int                             The unpacked data in the specified format
     * @throws  Woops_Binary_Stream_Exception   If the end of the stream has been reached
     */
    protected function _unpackData( $format )
    {
        // Checks the unpack format
        if( $format === 'c' || $format === 'C' ) {
            
            // Number of bytes to read from the data
            $readBytes = 1;
            
        } else if( $format === 's' || $format === 'S' || $format === 'n' || $format === 'v' ) {
            
            // Number of bytes to read from the data
            $readBytes = 2;
            
        } else if( $format === 'l' || $format === 'L' || $format === 'N' || $format === 'V' ) {
            
            // Number of bytes to read from the data
            $readBytes = 4;
        }
        
        // Checks if the stream end has been reached
        if( $this->_offset + $readBytes > $this->_dataLength ) {
            
            // Error - No more data
            throw new Woops_Binary_Stream_Exception(
                'Reached the end of the binary stream',
                Woops_Binary_Stream_Exception::EXCEPTION_END_OF_STREAM
            );
        }
        
        // Unpacks the data
        $unpackData = unpack( $format, substr( $this->_data, $this->_offset, $readBytes ) );
        
        // Increases the offset
        $this->_offset += $readBytes;
        
        // Returns the processed data
        return array_shift( $unpackData );
    }
    
    /**
     * Tests for the end of the stream
     * 
     * @return  boolean True if the end of the stream has been reached, otheriwse false;
     */
    public function endOfStream()
    {
        return $this->_offset === $this->_dataLength;
    }
    
    /**
     * Seeks on the stream pointer
     * 
     * @param   int                             The offset
     * @param   int                             The seek type (one of the SEEK_XXX constant)
     * @return  void
     * @throws  Woops_Binary_Stream_Exception   If the seek type is invalid
     */
    public function seek( $offset, $whence = self::SEEK_SET )
    {
        // Checks the seek type
        if( $whence === self::SEEK_SET ) {
            
            // Sets the offset from the stream start
            $this->_offset = 0 + $offset;
            
        } elseif( $whence === self::SEEK_CUR ) {
            
            // Sets the offset from the current position
            $this->_offset += $offset;
            
        } elseif( $whence === self::SEEK_END ) {
            
            // Sets the offset from the stream end
            $this->_offset = $this->_dataLength + $offset;
            
        } else {
            
            // Error - Invalid seek type
            throw new Woops_Binary_Stream_Exception(
                'Invalid seek type (' . $whence . ')',
                Woops_Binary_Stream_Exception::EXCEPTION_INVALID_SEEK_TYPE
            );
        }
        
        // Checks the offset
        if( $this->_offset < 0 ) {
            
            // Sets the offset to the data start
            $this->_offset = 0;
            
        } elseif( $this->_offset > $this->_dataLength ) {
            
            // Sets the offset to the data length
            $this->_offset = $this->_dataLength;
        }
    }
    
    /**
     * Rewinds the position of the stream pointer
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_offset = 0;
    }
    
    /**
     * Returns the current position of the stream pointer
     * 
     * @return  int The current position of the stream pointer
     */
    public function getOffset()
    {
        return $this->_offset;
    }
    
    /**
     * Reads bytes from the binary stream
     * 
     * @param   int                             The number of bytes to read
     * @return  string                          The number of requested bytes from the binary stream
     * @throws  Woops_Binary_Stream_Exception   If the end of the stream has been reached
     */
    public function read( $readBytes = 1 )
    {
        // Checks if the stream end has been reached
        if( $this->_offset + $readBytes > $this->_dataLength ) {
            
            // Error - No more data
            throw new Woops_Binary_Stream_Exception(
                'Reached the end of the binary stream',
                Woops_Binary_Stream_Exception::EXCEPTION_END_OF_STREAM
            );
        }
        
        // Ensures we have an integer
        $readBytes      = ( int )$readBytes;
        
        // Gets the data
        $data           = substr( $this->_data, $this->_offset, $readBytes );
        
        // Increases the offset
        $this->_offset += $readBytes;
        
        // Returns the data
        return $data;
    }
    
    /**
     * Writes bytes to the binary stream
     * 
     * @param   string  The data to write
     * @return  void
     */
    public function write( $data )
    {
        $this->_data       .= $data;
        $this->_dataLength += strlen( $data );
        $this->_offset     += $this->_dataLength;
    }
    
    /**
     * Gets the remaining stream data
     * 
     * @return  string  The remaining stream data
     * @throws  Woops_Binary_Stream_Exception   If the end of the stream has been reached
     */
    public function getRemainingData()
    {
        // Checks if the stream end has been reached
        if( $this->_offset === $this->_dataLength ) {
            
            // Error - No more data
            throw new Woops_Binary_Stream_Exception(
                'Reached the end of the binary stream',
                Woops_Binary_Stream_Exception::EXCEPTION_END_OF_STREAM
            );
        }
        
        // Returns the remaining data
        return substr( $this->_data, $this->_offset );
    }
    
    /**
     * Gets a signed char from the binary stream
     * 
     * @return  int The signed char
     * @see     _unpackData
     */
    public function signedChar()
    {
        return $this->_unpackData( 'c' );
    }
    
    /**
     * Writes a signed char to the binary stream
     * 
     * @param   int     The signed char
     * @return  void
     * @see     _unpackData
     */
    public function writeSignedChar( $data )
    {
        $this->_data       .= pack( 'c', $data );
        $this->_dataLength += 1;
        $this->_offset     += 1;
    }
    
    /**
     * Gets an unsigned char from the binary stream
     * 
     * @return  int The unsigned char
     * @see     _unpackData
     */
    public function unsignedChar()
    {
        return $this->_unpackData( 'C' );
    }
    
    /**
     * Writes an unsigned char to the binary stream
     * 
     * @param   int     The unsigned char
     * @return  void
     */
    public function writeUnsignedChar( $data )
    {
        $this->_data       .= pack( 'C', $data );
        $this->_dataLength += 1;
        $this->_offset     += 1;
    }
    
    /**
     * Gets a signed short from the binary stream
     * 
     * @return  int The signed short
     * @see     _unpackData
     */
    public function signedShort()
    {
        return $this->_unpackData( 's' );
    }
    
    /**
     * Writes a signed short to the binary stream
     * 
     * @param   int     The signed short
     * @return  void
     */
    public function writeSignedShort( $data )
    {
        $this->_data       .= pack( 's', $data );
        $this->_dataLength += 2;
        $this->_offset     += 2;
    }
    
    /**
     * Gets an unsigned short from the binary stream
     * 
     * @return  int The unsigned short
     * @see     _unpackData
     */
    public function unsignedShort()
    {
        return $this->_unpackData( 'S' );
    }
    
    /**
     * Writes an unsigned short to the binary stream
     * 
     * @param   int     The unsigned short
     * @return  void
     */
    public function writeUnsignedShort( $data )
    {
        $this->_data       .= pack( 'S', $data );
        $this->_dataLength += 2;
        $this->_offset     += 2;
    }
    
    /**
     * Gets a big endian unsigned short from the binary stream
     * 
     * @return  int The big endian unsigned short
     * @see     _unpackData
     */
    public function bigEndianUnsignedShort()
    {
        return $this->_unpackData( 'n' );
    }
    
    /**
     * Writes a big endian unsigned short to the binary stream
     * 
     * @param   int     The big endian unsigned short
     * @return  void
     */
    public function writeBigEndianUnsignedShort( $data )
    {
        $this->_data       .= pack( 'n', $data );
        $this->_dataLength += 2;
        $this->_offset     += 2;
    }
    
    /**
     * Gets a little endian unsigned short from the binary stream
     * 
     * @return  int The little endian unsigned short
     * @see     _unpackData
     */
    public function littleEndianUnsignedShort()
    {
        return $this->_unpackData( 'v' );
    }
    
    /**
     * Writes a little endian unsigned short to the binary stream
     * 
     * @param   int     The little endian unsigned short
     * @return  void
     */
    public function writeLittleEndianUnsignedShort( $data )
    {
        $this->_data       .= pack( 'v', $data );
        $this->_dataLength += 2;
        $this->_offset     += 2;
    }
    
    /**
     * Gets a signed long from the binary stream
     * 
     * @return  int The signed long
     * @see     _unpackData
     */
    public function signedLong()
    {
        return $this->_unpackData( 'l' );
    }
    
    /**
     * Writes a signed long to the binary stream
     * 
     * @param   int     The signed long
     * @return  void
     */
    public function writeSignedLong( $data )
    {
        $this->_data       .= pack( 'l', $data );
        $this->_dataLength += 4;
        $this->_offset     += 4;
    }
    
    /**
     * Gets an unsigned long from the binary stream
     * 
     * @return  int The unsigned long
     * @see     _unpackData
     */
    public function unsignedLong()
    {
        return $this->_unpackData( 'L' );
    }
    
    /**
     * Writes an unsigned long to the binary stream
     * 
     * @param   int     The unsigned long
     * @return  void
     */
    public function writeUnsignedLong( $data )
    {
        $this->_data       .= pack( 'L', $data );
        $this->_dataLength += 4;
        $this->_offset     += 4;
    }
    
    /**
     * Gets a big endian unsigned long from the binary stream
     * 
     * @return  int The big endian unsigned long
     * @see     _unpackData
     */
    public function bigEndianUnsignedLong()
    {
        return $this->_unpackData( 'N' );
    }
    
    /**
     * Writes a big endian unsigned long to the binary stream
     * 
     * @param   int     The big endian unsigned long
     * @return  void
     */
    public function writeBigEndianUnsignedLong( $data )
    {
        $this->_data       .= pack( 'N', $data );
        $this->_dataLength += 4;
        $this->_offset     += 4;
    }
    
    /**
     * Gets a little endian unsigned long from the binary stream
     * 
     * @return  int The little endian unsigned long
     * @see     _unpackData
     */
    public function littleEndianUnsignedLong()
    {
        return $this->_unpackData( 'V' );
    }
    
    /**
     * Writes a little endian unsigned long to the binary stream
     * 
     * @param   int     The little endian unsigned long
     * @return  void
     */
    public function writeLittleEndianUnsignedLong( $data )
    {
        $this->_data       .= pack( 'V', $data );
        $this->_dataLength += 4;
        $this->_offset     += 4;
    }
    
    /**
     * Gets a fixed point number from the binary stream
     * 
     * Actually, only 8.8, 16.16, 30.2 and 2.30 fixed point formats are supported.
     * 
     * @param   int     The number of bits for the integer part (2, 8, 16 or 30)
     * @param   int     The number of bits for the fractional part (2, 8, 16 or 30)
     * @return  float   The fixed point number
     * @see     _unpackData
     */
    public function bigEndianFixedPoint( $integerLength, $fractionalLength )
    {
        // Checks if the fixed point number is expressed on 16 or 32 bits
        if( $integerLength + $fractionalLength === 16 ) {
            
            // Unsigned short - big endian
            $unpackFormat   = 'n';
            
            // Mask for the fractional part
            $fractionalMask = 0x00FF;
            
        } else {
            
            // Unsigned long - big endian
            $unpackFormat   = 'N';
            
            // Mask for the fractional part
            $fractionalMask = 0x0000FFFF;
        }
        
        // Gets the decimal value for the fixed point number from the data
        $unpackData = $this->_unpackData( $unpackFormat );
        
        // Computes the integer part
        $integer    = $unpackData >> $fractionalLength;
        
        // Computes the fractional part
        $fractional = ( $unpackData & $fractionalMask ) / self::$_dividers[ $fractionalLength ];
        
        // Returns the fixed point number
        return $integer + $fractional;
    }
    
    /**
     * Gets an ISO-639-2 language code from the binary stream
     * 
     * @return  string  The ISO-639-2 language code
     * @see     _unpackData
     */
    public function bigEndianIso639Code()
    {
        // Gets an big endian unsigned short (16 bits) from the given data offset
        $unpackData = $this->_unpackData( 'n' );
        
        // Gets letters (each letter is coded on 5 bits)
        $letter1 = ( $unpackData & 0x7C00 ) >> 10;  // Mask is 0111 1100 0000 0000
        $letter2 = ( $unpackData & 0x03E0 ) >> 5;   // Mask is 0000 0011 1110 0000
        $letter3 = ( $unpackData & 0x001F );        // Mask is 0000 0000 0001 1111
        
        // Returns the language code as a string
        // 0x60 - 96 is added to each letter, as the language codes are lowercase letters
        return chr( $letter1 + 0x60 ) . chr( $letter2 + 0x60 ) . chr( $letter3 + 0x60 );
    }
    
    /**
     * Writes an ISO-639-2 language code to the binary stream
     * 
     * @param   string  The ISO-639-2 language code
     * @return  void
     */
    public function writeBigEndianIso639Code( $data )
    {
        // Checks the length of the string
        if( strlen( $data ) !== 3 ) {
            
            // Error - Language code must be 3 characters
            throw new Woops_Binary_Stream_Exception(
                'Passed argument is not a valid IS0-639-2 language code',
                Woops_Binary_Stream_Exception::EXCEPTION_BAD_ISO_639_CODE
            );
        }
        
        // Gets the packed format for each letter
        $letter1 = ord( $data[ 0 ] );
        $letter2 = ord( $data[ 1 ] );
        $letter3 = ord( $data[ 2 ] );
        
        // Creates the binary version of the language code
        $code = ( ( $letter1 & 0x1F ) << 10 )
              | ( ( $letter2 & 0x1F ) << 5 )
              |   ( $letter3 & 0x1F );
        
        // Writes the language code as a big endian unsigned short
        $this->writeBigEndianUnsignedShort( $code );
    }
    
    /**
     * Gets a string that ends with an ASCII NULL character
     * 
     * @return  string  The string
     */
    public function nullTerminatedString()
    {
        // Gets the position of the next NULL character
        $null   = strpos( $this->_data, chr( 0 ), $this->_offset );
        
        // Gets the string
        $string = substr( $this->_data, $this->_offset, $null );
        
        // Checks if a NULL character was found
        if( !$null ) {
            
            // No NULL character, reads untils the end of the stream
            $this->_offset = $this->_dataLength;
            
        } else {
            
            // Increases the offset
            $this->_offset += $null + 1;
        }
        
        // Returns the string
        return $string;
        
    }
    
    /**
     * Gets an UTF-8 string (16bits length)
     * 
     * @return  string  The UTF-8 string
     */
    public function utf8String()
    {
        // Gets the string length
        $length = $this->bigEndianUnsignedShort();
        
        // Returns the string
        return $this->read( $length );
    }
    
    /**
     * Gets an UTF-8 string (32bits length)
     * 
     * @return  string  The UTF-8 string
     */
    public function longUtf8String()
    {
        // Gets the string length
        $length = $this->bigEndianUnsignedLong();
        
        // Returns the string
        return $this->read( $length );
    }
    
    /**
     * Gets a float number from the binary stream
     * 
     * This function gets a single precision floating point number, as specified
     * by the IEEE Standard for Floating-Point Arithmetic (IEEE 754). This
     * standard can be found at the folowing address:
     * {@link http://ieeexplore.ieee.org/servlet/opac?punumber=4610933}
     * 
     * Single precsion floating point numbers are usually called 'float', or
     * 'real'. They are 4 bytes long, and are packed the following way, from
     * left to right:
     * 
     * - Sign:     1 bit
     * - Exponent: 8 bits
     * - Mantissa: 23 bits
     * 
     * <code>
     * ====================================================
     * | X     | XXXX XXXX | XXX XXXX XXXX XXXX XXXX XXXX |
     * ----------------------------------------------------
     * | Sign  | Exponent  | Mantissa                     |
     * | 1 bit | 8 bits    | 23 bits                      |
     * ====================================================
     * </code>
     * 
     * The sign indicates if the number is positive or negative (zero for
     * positive, one for negative).
     * 
     * The real exponent is computed by substracting 127 to the value of the
     * exponent field. It's the exponent of the number as it is expressed in the
     * scientific notation.
     * 
     * The full mantissa, which is also sometimes called significand, should be
     * considered as a 24 bits value. As we are using scientific notation, there
     * is an implicit leading bit (sometimes called the hidden bit), always set
     * to 1, as there is never a leading 0 in the scientific notation.
     * For instance, you won't say 0.123 * 10 ^ 5 but 1.23 * 10 ^ 4.
     * 
     * The conversion is performed the following way:
     * 
     * <code>
     * -1 ^ S * 1.M * 2 ^ ( E - 127 )
     * </code>
     * 
     * Where S is the sign, M the mantissa, and E the exponent.
     * 
     * For instance, '0100 0000 1011 1000 0000 0000 0000 0000', which is
     * 0x40B80000 in hexadecimal.
     * 
     * <code>
     * ===============================================================
     * | Hex | 4    | 0    | B    | 8    | 0    | 0    | 0    | 0    |
     * ---------------------------------------------------------------
     * | Bin | 0100 | 0000 | 1011 | 1000 | 0000 | 0000 | 0000 | 0000 |
     * ===============================================================
     * 
     * ====================================================
     * | S | E         | M                                |
     * ----------------------------------------------------
     * | 0 | 1000 0001 | (1) 011 1000 0000 0000 0000 0000 |
     * ====================================================
     * </code>
     * 
     * - The sign is '0', so the number is positive.
     * - The exponent field is '1000 0001', which is 129 in decimal. The real
     *   exponent value is then 129 - 127, which is 2.
     * - The mantissa with the leading 1 bit is '1011 1000 0000 0000 0000 0000'.
     * 
     * The final representation of the number in the binary scientific notation
     * is:
     * 
     * <code>
     * 1.0111 * ( 2 ^ 2 )
     * </code>
     * 
     * Mathematically, this means:
     * 
     * <code>
     * ( 1 * 2 ^ 0 + 0 * 2 ^ -1 + 1 * 2 ^ -2 + 1 * 2 ^ -3 + 1 * 2 ^ -4 ) * 2 ^ 2
     * ( 2 ^ 0 + 2 ^ -2 + 2 ^ -3 + 2 ^ -4 ) * 2 ^ 2
     * 2 ^ 2 + 2 ^ 0 + 2 ^ -1 + 2 ^ -2
     * 4 + 1 + 0.5 + 0.75
     * </code>
     * 
     * The floating point value is then 5.75.
     * 
     * Special numbers:
     * Depending on the value of the exponent field, some numbers can have
     * special values. They can be:
     * 
     * - Denormalized numbers
     * - Zero
     * - Infinity
     * - NaN (not a number)
     * 
     * Denormalized numbers:
     * If the value of the exponent field is 0 and the value of the mantissa
     * field is greater than 0, then the number has to be treated as a
     * denormalized number.
     * In such a case, the exponent is not -127, but -126, and the implicit
     * leading bit is not 1 but 0.
     * That allows smaller numbers to be represented.
     * 
     * The scientific notation for a denormalized number is:
     * 
     * <code>
     * -1 ^ S *  0.M * 2 ^ -126
     * </code>
     * 
     * Zero:
     * If the exponent and the mantissa fields are both 0, then the final number
     * is zero. The sign bit is permitted, even if it does not have much sense
     * mathematically, allowing a positive or a negative zero.
     * Note that zero can be considered as a denormalized number. In that case,
     * it would be 0 * 2 ^ -126, which is zero.
     * 
     * Infinity:
     * If the value of the exponent field is 255 (all 8 bits are set) and if the
     * value of the mantissa field is 0, the number is an infinity, either
     * positive or negative, depending on the sign bit.
     * 
     * NaN:
     * If the value of the exponent field is 255 (all 8 bits are set) and if the
     * value of the mantissa field is not 0, then the value is not a number. The
     * sign bit as no meaning in such a case.
     * 
     * Range:
     * The range of the floating point number is:
     * 
     * - For the normalized numbers:
     *      -#  Min:    ±1.1754944909521E-38 / ±1.00000000000000000000001 ^ -126
     *      -#  Max:    ±3.4028234663853E+38 / ±1.11111111111111111111111 ^ 128
     * - For the denormalized numbers:
     *      -#  Min:    ±1.4012984643248E-45 / ±0.00000000000000000000001 ^ -126
     *      -#  Max:    ±1.1754942106924E-38 / ±0.11111111111111111111111 ^ -126
     * 
     * @return  float   The floating point number
     */
    public function float()
    {
        // Gets the data as a 32bit integer
        $binary = $this->unsignedLong();
        
        // Gets the sign field
        // Bit 0, left to right
        $sign     = $binary >> 31;
        
        // Gets the exponent field
        // Bits 1 to 8, left to right
        $exp      = ( ( $binary >> 23 ) & 0xFF );
        
        // Gets the mantissa field
        // Bits 9 to 32, left to right
        $mantissa = ( $binary & 0x7FFFFF );
        
        // Checks the values of the exponent and the mantissa fields to handle
        // special numbers
        if( $exp === 0 && $mantissa === 0 ) {
            
            // Zero - No need for a computation even if it can be considered
            // as a denormalized number
            return 0;
            
        } elseif( $exp === 255 && $mantissa === 0 ) {
            
            // Infinity
            return ( $sign === 0 ) ? INF : '-' . INF;
            
        } elseif( $exp === 255 && $mantissa !== 0 ) {
            
            // Not a number
            return NAN;
            
        } elseif( $exp === 0 && $mantissa !== 0 ) {
            
            // Donormalized number - Exponent is fixed to -126
            $exp = -126;
            
        } else {
            
            // Computes the real exponent
            $exp      = $exp - 127;
            
            // Adds the implicit bit to the mantissa
            $mantissa = $mantissa | 0x800000;
        }
        
        // Initial value for the float
        $float = 0;
        
        // Process the 24 bits of the mantissa
        for( $i = 0; $i > -24; $i-- ) {
            
            // Checks if the current bit is set
            if( $mantissa & ( 1 << $i + 23 ) ) {
                
                // Adds the value for the current bit
                // This is done by computing two raised to the power of the
                // exponent plus the bit position (negative if it's after the
                // implicit bit, as we are using scientific notation)
                $float += pow( 2, $i + $exp );
            }
        }
        
        // Returns the final float value
        return ( $sign === 0 ) ? $float : -$float;
    }
    
    /**
     * Gets a double number from the binary stream
     * 
     * This function gets a double precision floating point number, as specified
     * by the IEEE Standard for Floating-Point Arithmetic (IEEE 754). This
     * standard can be found at the folowing address:
     * {@link http://ieeexplore.ieee.org/servlet/opac?punumber=4610933}
     * 
     * Double precsion floating point numbers are usually called 'double'.
     * They are 8 bytes long, and are packed the same way as single precision
     * floating point numbers, except the fact that the fields are bigger:
     * 
     * - Sign:     1 bit
     * - Exponent: 11 bits
     * - Mantissa: 52 bits
     * 
     * Please see the documentation about the 'float()' method if you want to
     * know more about floating point arithmetic.
     * 
     * @see 
     * @return  double  The floating point number
     */
    public function double()
    {
        // Gets the data as a 64bit integer
        $binary = ( $this->unsignedLong() << 32 ) | $this->unsignedLong();
        
        // Gets the sign field
        // Bit 0, left to right
        $sign     = $binary >> 63;
        
        // Gets the exponent field
        // Bits 1 to 8, left to right
        $exp      = ( ( $binary >> 52 ) & 0x7FF );
        
        // Gets the mantissa field
        // Bits 9 to 32, left to right
        $mantissa = ( $binary & 0xFFFFFFFFFFFF );
        
        // Checks the values of the exponent and the mantissa fields to handle
        // special numbers
        if( $exp === 0 && $mantissa === 0 ) {
            
            // Zero - No need for a computation even if it can be considered
            // as a denormalized number
            return 0;
            
        } elseif( $exp === 2047 && $mantissa === 0 ) {
            
            // Infinity
            return ( $sign === 0 ) ? INF : '-' . INF;
            
        } elseif( $exp === 2047 && $mantissa !== 0 ) {
            
            // Not a number
            return NAN;
            
        } elseif( $exp === 0 && $mantissa !== 0 ) {
            
            // Donormalized number - Exponent is fixed to -1022
            $exp = -1022;
            
        } else {
            
            // Computes the real exponent
            $exp      = $exp - 1023;
            
            // Adds the implicit bit to the mantissa
            $mantissa = $mantissa | 0x10000000000000;
        }
        
        // Initial value for the float
        $float = 0;
        
        // Process the 24 bits of the mantissa
        for( $i = 0; $i > -53; $i-- ) {
            
            // Checks if the current bit is set
            if( $mantissa & ( 1 << $i + 53 ) ) {
                
                // Adds the value for the current bit
                // This is done by computing two raised to the power of the
                // exponent plus the bit position (negative if it's after the
                // implicit bit, as we are using scientific notation)
                $float += pow( 2, $i + $exp );
            }
        }
        
        // Returns the final float value
        return ( $sign === 0 ) ? $float : -$float;
    }
}
