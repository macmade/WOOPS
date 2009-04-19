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
 * TIFF file header
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff
 */
class Woops_Tiff_Header
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the byte order is big endian or not
     */
    protected $_isBigEndian = false;
    
    /**
     * The offset of the first IFD (Image File Directory)
     */
    protected $_offset      = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Tiff_Binary_Stream $stream )
    {
        // Gets the TIFF byte order
        $byteOrder = $stream->read( 2 );
        
        // Checks the TIFF byte order
        if( $byteOrder === 'II' ) {
            
            // Byte order is little endian
            $this->_isBigEndian = false;
            
        } elseif( $byteOrder === 'MM' ) {
            
            // Byte order is big endian
            $this->_isBigEndian = true;
            
        } else {
            
            // Error - Invalid byte order
            throw new Woops_Tiff_Header_Exception(
                'Invalid TIFF file signature (' . $byteOrder . ')',
                Woops_Tiff_Header_Exception::EXCEPTION_BAD_SIGNATURE
            );
        }
        
        // Gets the TIFF signature
        $signature = ( $this->_isBigEndian ) ? $stream->bigEndianUnsignedShort() : $stream->littleEndianUnsignedShort();
        
        // Checks the TIFF signature
        if( $signature !== 0x2A ) {
            
            // Error - Invalid TIFF signature
            throw new Woops_Tiff_Header_Exception(
                'Invalid TIFF file signature (' . $signature . ')',
                Woops_Tiff_Header_Exception::EXCEPTION_BAD_SIGNATURE
            );
        }
        
        // Gets the IFD offset
        $this->_offset = ( $this->_isBigEndian ) ? $stream->bigEndianUnsignedLong() : $stream->littleEndianUnsignedLong();
    }
    
    /**
     * Checks if the byte order is big endian
     * 
     * @return  boolean True if the byte order is big endian, otherwise false
     */
    public function isBigEndian()
    {
        return $this->_isBigEndian;
    }
    
    /**
     * Checks if the byte order is little endian
     * 
     * @return  boolean True if the byte order is little endian, otherwise false
     */
    public function isLittleEndian()
    {
        return ( $this->_isBigEndian ) ? false : true;
    }
    
    /**
     * Sets the byte order as big endian
     * 
     * @return  void
     */
    public function setBigEndian()
    {
        $this->_isBigEndian = true;
    }
    
    /**
     * Sets the byte order as little endian
     * 
     * @return  void
     */
    public function setLittleEndian()
    {
        $this->_isBigEndian = false;
    }
    
    /**
     * Gets the offset of the first IDF (Image File Directory)
     * 
     * @return  int     The offset of the first IDF (Image File Directory)
     */
    public function getFirstIfdOffset()
    {
        return $this->_offset;
    }
    
    /**
     * Sets the offset of the first IDF (Image File Directory)
     * 
     * @param   int     The offset of the first IDF (Image File Directory)
     * @return  void
     */
    public function setFirstIfdOffset( $value )
    {
        $this->_offset = ( int )$value;
    }
}
