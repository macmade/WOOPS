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
 * ZIP data descriptor
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Zip.Central.File
 */
class Woops_Zip_Data_Descriptor
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The CRC32
     */
    protected $_crc32            = 0;
    
    /**
     * THe compressed data size
     */
    protected $_compressedSize   = 0;
    
    /**
     * The uncompressed data size
     */
    protected $_uncompressedSize = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops_Zip_Binary_Stream The binary stream
     * @return  void
     */
    public function processData( Woops_Zip_Binary_Stream $stream )
    {
        // Gets the CRC32
        $this->_crc32            = $stream->littleEndianUnsignedLong();
        
        // Checks if we have a signature for the data descriptor
        if( $this->_crc32 === 0x08074B50 ) {
            
            // Gets the CRC32
            $this->_crc32            = $stream->littleEndianUnsignedLong();
        }
        
        // Gets the compressed and uncompressed size
        $this->_compressedSize   = $stream->littleEndianUnsignedLong();
        $this->_uncompressedSize = $stream->littleEndianUnsignedLong();
    }
}
