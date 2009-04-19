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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * Abstract for the TIFF tag classes
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff
 */
abstract class Woops_Tiff_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The value types
     */
    const TYPE_BYTE      = 0x01;
    const TYPE_ASCII     = 0x02;
    const TYPE_SHORT     = 0x03;
    const TYPE_LONG      = 0x04;
    const TYPE_RATIONAL  = 0x05;
    const TYPE_SBYTE     = 0x06;
    const TYPE_UNDEFINED = 0x07;
    const TYPE_SSHORT    = 0x08;
    const TYPE_SLONG     = 0x09;
    const TYPE_SRATIONAL = 0x0A;
    const TYPE_FLOAT     = 0x0B;
    const TYPE_DOUBLE    = 0x0C;
    
    /**
     * The value types, with their size in byte
     */
    protected static $_types = array(
        0x01 => 1,  // 8-bit unsigned integer
        0x02 => 1,  // 8-bit byte that contains a 7-bit ASCII code; the last byte must be NUL (binary zero)
        0x03 => 2,  // 16-bit (2-byte) unsigned integer
        0x04 => 4,  // 32-bit (4-byte) unsigned integer
        0x05 => 8,  // Two LONGs: the first represents the numerator of a fraction; the second, the denominator
        0x06 => 1,  // An 8-bit signed (twos-complement) integer
        0x07 => 1,  // An 8-bit byte that may contain anything, depending on the definition of the field
        0x08 => 2,  // A 16-bit (2-byte) signed (twos-complement) integer
        0x09 => 4,  // A 32-bit (4-byte) signed (twos-complement) integer
        0x0A => 8,  // Two SLONG’s: the first represents the numerator of a fraction, the second the denominator
        0x0B => 4,  // Single precision (4-byte) IEEE format
        0x0C => 8   // Double precision (8-byte) IEEE format
    );
    
    /**
     * The TIFF tag type
     */
    protected $_type         = 0x0000;
    
    /**
     * The value type
     */
    protected $_valueType    = 1;
    
    /**
     * The TIFF file
     */
    protected $_file         = NULL;
    
    /**
     * The TIFF header
     */
    protected $_header       = NULL;
    
    /**
     * The tag value(s)
     */
    protected $_values       = array();
    
    /**
     * Class constructor
     * 
     * @param   Woops_Tiff_File The TIFF file in which the tag is contained
     * @return  void
     */
    public function __construct( Woops_Tiff_File $file )
    {
        // Stores the TIFF file and header
        $this->_file   = $file;
        $this->_header = $this->_file->getHeader();
    }
    
    /**
     * Reads tag value(s) from the binary stream
     * 
     * @param   Woops_Tiff_Binary_Stream    The binary stream
     * @param   int                         The number of values
     * @return  void
     */
    protected function _readValuesFromStream( $stream, $count )
    {
        // Checks the value type
        switch( $this->_valueType ) {
            
            // 8-bit unsigned integer
            case 0x01:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = $stream->unsignedChar();
                }
                break;
                
            // 8-bit byte that contains a 7-bit ASCII code; the last byte must be NUL (binary zero)
            case 0x02:
                
                // Gets the current value
                $this->_values[] = $stream->nullTerminatedString();
                break;
                
            // 16-bit (2-byte) unsigned integer
            case 0x03:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedShort() : $stream->littleEndianUnsignedShort();
                }
                break;
                
            // 32-bit (4-byte) unsigned integer
            case 0x04:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedLong() : $stream->littleEndianUnsignedLong();
                }
                break;
                
            // Two LONGs: the first represents the numerator of a fraction; the second, the denominator
            case 0x05:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = array(
                        ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedLong() : $stream->littleEndianUnsignedLong(),
                        ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedLong() : $stream->littleEndianUnsignedLong()
                    );
                }
                break;
                
            // An 8-bit signed (twos-complement) integer
            case 0x06:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = $stream->signedChar();
                }
                break;
                
            // An 8-bit byte that may contain anything, depending on the definition of the field
            case 0x07:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = $stream->read( 1 );
                }
                break;
                
            // A 16-bit (2-byte) signed (twos-complement) integer
            case 0x08:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = ( $this->_header->isBigEndian() ) ? $stream->bigEndianSignedShort() : $stream->littleEndianSignedShort();
                }
                break;
                
            // A 32-bit (4-byte) signed (twos-complement) integer
            case 0x09:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = ( $this->_header->isBigEndian() ) ? $stream->bigEndianSignedLong() : $stream->littleEndianSignedLong();
                }
                break;
                
            // Two SLONG’s: the first represents the numerator of a fraction, the second the denominator
            case 0x0A:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = array(
                        ( $this->_header->isBigEndian() ) ? $stream->bigEndianSignedLong() : $stream->littleEndianSignedLong(),
                        ( $this->_header->isBigEndian() ) ? $stream->bigEndianSignedLong() : $stream->littleEndianSignedLong()
                    );
                }
                break;
                
            // Single precision (4-byte) IEEE format
            case 0x0B:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = $stream->float();
                }
                break;
                
            // Double precision (8-byte) IEEE format
            case 0x0C:
                
                // Process the values
                for( $i = 0; $i < $count; $i++ ) {
                    
                    // Gets the current value
                    $this->_values[] = $stream->double();
                }
                break;
        }
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops_Tiff_Binary_Stream    The binary stream
     * @return  void
     * @throws  Woops_Tiff_Tag_Exception    If the value type is invalid
     */
    public function processData( Woops_Tiff_Binary_Stream $stream )
    {
        // Resets the value array
        $this->_values = array();
        
        // Gets the value type
        $this->_valueType  = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedShort() : $stream->littleEndianUnsignedShort();
        
        // Checks the value type
        if( !isset( self::$_types[ $this->_valueType ] ) ) {
            
            // Error - Invalid value type
            throw new Woops_Tiff_Tag_Exception(
                'Invalid value type (' . $this->_valueType . ')',
                Woops_Tiff_Tag_Exception::EXCEPTION_INVALID_VALUE_TYPE
            );
        }
        
        // Gets the number of values
        $valueCount = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedLong()  : $stream->littleEndianUnsignedLong();
        
        // Number of bytes to read for the value
        $readBytes  = ( $valueCount * self::$_types[ $this->_valueType ] );
        
        // Gets the current offset, so we can rewind the stream
        $offset       = $stream->getOffset();
        
        // Checks if the value can be contained in the tag
        if( $readBytes > 4 ) {
            
            // Gets the value offset
            $valueOffset  = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedLong() : $stream->littleEndianUnsignedLong();
            
            
            // Moves to the value offset
            $stream->seek( $valueOffset, Woops_Tiff_Binary_Stream::SEEK_SET );
            
            // Reads the value(s)
            $this->_readValuesFromStream( $stream, $valueCount );
            
            // Moves the stream to the end of the value offset
            $stream->seek( $offset + 4, Woops_Tiff_Binary_Stream::SEEK_SET );
            
        } else {
            
            // Reads the value(s)
            $this->_readValuesFromStream( $stream, $valueCount );
            
            // Moves the stream to the end of the value
            $stream->seek( $offset + 4, Woops_Tiff_Binary_Stream::SEEK_SET );
        }
    }
    
    /**
     * Gets the tag type
     * 
     * @return  int     The tag type
     */
    public function getType()
    {
        return $this->_type;
    }
    
    /**
     * Gets the value type
     * 
     * @return  int     The value type
     */
    public function getValueType()
    {
        return $this->_valueType;
    }
    
    /**
     * Gets the tag values
     * 
     * @return  array   An array with the tag values
     */
    public function getValues()
    {
        return $this->_values;
    }
    
    /**
     * Sets the value type
     * 
     * @param   int                         The value type (one of the TYPE_XXX constant)
     * @return  void
     * @throws  Woops_Tiff_Tag_Exception    If the value type is invalid
     */
    public function setValueType( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks the value type
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid value type
            throw new Woops_Tiff_Tag_Exception(
                'Invalid value type (' . $type . ')',
                Woops_Tiff_Tag_Exception::EXCEPTION_INVALID_VALUE_TYPE
            );
        }
        
        // Sets the value type
        $this->_valueType = $type;
    }
    
    /**
     * Sets the tag values
     * 
     * @param   array   An array with the tag values
     * @return  void
     */
    public function setValues( array $values )
    {
        $this->_values = $values;
    }
}
