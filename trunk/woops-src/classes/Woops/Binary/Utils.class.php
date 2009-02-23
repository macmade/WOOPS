<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Binary utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Binary
 */
final class Woops_Binary_Utils
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The dividers values for the fixed point methods
     */
    private static $_dividers = array(
        2  => 4,            // 1 << 2  - (2 ** 2)  - For the 30.2 fixed point numbers
        8  => 256,          // 1 << 8  - (2 ** 8)  - For the 8.8 fixed point numbers
        16 => 65536,        // 1 << 16 - (2 ** 16) - For the 16.16 fixed point numbers
        30 => 1073741824    // 1 << 30 - (2 ** 30) - For the 2.30 fixed point numbers
    );
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
     */
    private function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Binary_Utils  The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * Unpacks data to the specified format
     * 
     * @param   string  The unpack format (see function unpack())
     * @param   string  The data from which to read (passed by reference)
     * @param   int     The offset from which to read the data
     * @return  int     The unpacked data in the specified format
     */
    private function _unpackData( $format, &$data, $dataOffset )
    {
        // Checks the unpack format
        if( $format === 'c' || $format === 'C' ) {
            
            // Number of bytes to read from the data
            $readByte = 1;
            
        } else if( $format === 's' || $format === 'S' || $format === 'n' || $format === 'v' ) {
            
            // Number of bytes to read from the data
            $readByte = 2;
            
        } else if( $format === 'l' || $format === 'L' || $format === 'N' || $format === 'V' ) {
            
            // Number of bytes to read from the data
            $readByte = 4;
        }
        
        // Unpacks the data
        $unpackData = unpack( $format, substr( $data, $dataOffset, $readByte ) );
        
        // Returns the processed data
        return array_shift( $unpackData );
    }
    
    /**
     * Gets a signed char
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The signed char
     * @see     _unpackData
     */
    public function signedChar( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'c', $data, $dataOffset );
    }
    
    /**
     * Gets an unsigned char
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The unsigned char
     * @see     _unpackData
     */
    public function unsignedChar( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'C', $data, $dataOffset );
    }
    
    /**
     * Gets a signed short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The signed short
     * @see     _unpackData
     */
    public function signedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 's', $data, $dataOffset );
    }
    
    /**
     * Gets an unsigned short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The unsigned short
     * @see     _unpackData
     */
    public function unsignedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'S', $data, $dataOffset );
    }
    
    /**
     * Gets a big endian unsigned short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The big endian unsigned short
     * @see     _unpackData
     */
    public function bigEndianUnsignedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'n', $data, $dataOffset );
    }
    
    /**
     * Gets a little endian unsigned short
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The little endian unsigned short
     * @see     _unpackData
     */
    public function littleEndianUnsignedShort( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'v', $data, $dataOffset );
    }
    
    /**
     * Gets a signed long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The signed long
     * @see     _unpackData
     */
    public function signedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'l', $data, $dataOffset );
    }
    
    /**
     * Gets an unsigned long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The unsigned long
     * @see     _unpackData
     */
    public function unsignedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'L', $data, $dataOffset );
    }
    
    /**
     * Gets a big endian unsigned long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The big endian unsigned long
     * @see     _unpackData
     */
    public function bigEndianUnsignedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'N', $data, $dataOffset );
    }
    
    /**
     * Gets a little endian unsigned long
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  int     The little endian unsigned long
     * @see     _unpackData
     */
    public function littleEndianUnsignedLong( &$data, $dataOffset = 0 )
    {
        return $this->_unpackData( 'V', $data, $dataOffset );
    }
    
    /**
     * Gets a fixed point number
     * 
     * Actually, only 8.8, 16.16, 30.2 and 2.30 fixed point formats are supported.
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     The number of bits for the integer part (2, 8, 16 or 30)
     * @param   int     The number of bits for the fractional part (2, 8, 16 or 30)
     * @param   int     An optionnal offset from which to read the data
     * @return  float   The fixed point number
     * @see     _unpackData
     */
    public function bigEndianFixedPoint( &$data, $integerLength, $fractionalLength, $dataOffset )
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
        $unpackData = $this->_unpackData( $unpackFormat, $data, $dataOffset );
        
        // Computes the integer part
        $integer    = $unpackData >> $fractionalLength;
        
        // Computes the fractional part
        $fractional = ( $unpackData & $fractionalMask ) / self::$_dividers[ $fractionalLength ];
        
        // Returns the fixed point number
        return $integer + $fractional;
    }
    
    /**
     * Gets an ISO-639-2 language code
     * 
     * @param   string  The data from which to read (passed by reference)
     * @param   int     An optionnal offset from which to read the data
     * @return  string  The ISO-639-2 language code
     * @see     _unpackData
     */
    public function bigEndianIso639Code( &$data, $dataOffset )
    {
        // Gets an big endian unsigned short (16 bits) from the given data offset
        $unpackData = $this->_unpackData( 'n', $data, $dataOffset );
        
        // Gets letters (each letter is coded on 5 bits)
        $letter1 = ( $unpackData & 0x7C00 ) >> 10;  // Mask is 0111 1100 0000 0000
        $letter2 = ( $unpackData & 0x03E0 ) >> 5;   // Mask is 0000 0011 1110 0000
        $letter3 = ( $unpackData & 0x001F );        // Mask is 0000 0000 0001 1111
        
        // Returns the language code as a string
        // 0x60 - 96 is added to each letter, as the language codes are lowercase letters
        return chr( $letter1 + 0x60 ) . chr( $letter2 + 0x60 ) . chr( $letter3 + 0x60 );
    }
    
    /**
     * Converts a integer to its float representation
     * 
     * This function converts a 32 bits integer to a single precision floating point
     * number, as specified by the IEEE Standard for Floating-Point Arithmetic
     * (IEEE 754). This standard can be found at the folowing address:
     * {@link http://ieeexplore.ieee.org/servlet/opac?punumber=4610933}
     * 
     * Single precsion floating point numbers are usually called 'float', or 'real'.
     * They are 4 bytes long, and are packed the following way, from left to right:
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
     * The sign indicates if the number is positive or negative (zero for positive,
     * one for negative).
     * 
     * The real exponent is computed by substracting 127 to the value of the
     * exponent field. It's the exponent of the number as it is expressed in the
     * scientific notation.
     * 
     * The full mantissa, which is also sometimes called significand, should be
     * considered as a 24 bits value. As we are using scientific notation, there is
     * an implicit leading bit (sometimes called the hidden bit), always set to 1,
     * as there is never a leading 0 in the scientific notation.
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
     * For instance, '0100 0000 1011 1000 0000 0000 0000 0000', which is 0x40B80000
     * in hexadecimal.
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
     * - The mantissa with the leading 1 bit, is '1011 1000 0000 0000 0000 0000'.
     * 
     * The final representation of the number in the binary scientific notation is:
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
     * Depending on the value of the exponent field, some numbers can have special
     * values. They can be:
     * 
     * - Denormalized numbers
     * - Zero
     * - Infinity
     * - NaN (not a number)
     * 
     * Denormalized numbers:
     * If the value of the exponent field is 0 and the value of the mantissa field
     * is greater than 0, then the number has to be treated as a denormalized
     * number.
     * In such a case, the exponent is not -127, but -126, and the implicit leading
     * bit is not 1 but 0.
     * That allows smaller numbers to be represented.
     * 
     * The scientific notation for a denormalized number is:
     * 
     * <code>
     * -1 ^ S *  0.M * 2 ^ -126
     * </code>
     * 
     * Zero:
     * If the exponent and the mantissa fields are both 0, then the final number is
     * zero. The sign bit is permitted, even if it does not have much sense
     * mathematically, allowing a positive or a negative zero.
     * Note that zero can be considered as a denormalized number. In that case,
     * it would be 0 * 2 ^ -126, which is zero.
     * 
     * Infinity:
     * If the value of the exponent field is 255 (all 8 bits are set) and if the
     * value of the mantissa field is 0, the number is an infinity, either positive
     * or negative, depending on the sign bit.
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
     * @param   int     The integer to convert to a floating point value
     * @return  float   The floating point number
     */
    public function binaryToFloat( $binary )
    {
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
}
