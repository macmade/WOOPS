<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * ZIP central file header
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Zip.Central.File
 */
class Woops_Zip_Central_File_Header extends Woops_Zip_Local_File_Header
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    protected $_madeByVersion          = 0;
    
    /**
     * 
     */
    protected $_diskNumberStart        = 0;
    
    /**
     * 
     */
    protected $_internalFileAttributes = 0;
    
    /**
     * 
     */
    protected $_externalFileAttributes = 0;
    
    /**
     * 
     */
    protected $_localHeaderOffset      = 0;
    
    /**
     * 
     */
    protected $_fileComment            = '';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops_Zip_Binary_Stream The binary stream
     * @return  void
     */
    public function processData( Woops_Zip_Binary_Stream $stream )
    {
        $this->_madeByVersion          = $stream->littleEndianUnsignedShort();
        $this->_extractVersion         = $stream->littleEndianUnsignedShort();
        
        $this->_flags                  = $stream->littleEndianUnsignedShort();
        
        $this->_compressionMethod      = $stream->littleEndianUnsignedShort();
        
        $this->_mTime                  = $stream->littleEndianUnsignedShort();
        $this->_mDate                  = $stream->littleEndianUnsignedShort();
        
        $this->_crc32                  = $stream->littleEndianUnsignedLong();
        
        $this->_compressedSize         = $stream->littleEndianUnsignedLong();
        $this->_uncompressedSize       = $stream->littleEndianUnsignedLong();
        
        $fileNameLength                = $stream->littleEndianUnsignedShort();
        $extraFieldLength              = $stream->littleEndianUnsignedShort();
        $fileCommentLength             = $stream->littleEndianUnsignedShort();
        
        $this->_diskNumberStart        = $stream->littleEndianUnsignedShort();
        
        $this->_internalFileAttributes = $stream->littleEndianUnsignedShort();
        $this->_externalFileAttributes = $stream->littleEndianUnsignedLong();
        
        $this->_localHeaderOffset      = $stream->littleEndianUnsignedLong();
        
        $this->_fileName               = $stream->read( $fileNameLength );
        
        $this->_processExtraField( $stream, $extraFieldLength );
        
        $this->_fileComment            = $stream->read( $fileCommentLength );
    }
    
    /**
     * Gets the offset to the local file header
     * 
     * @return  int     The offset to the local file header
     */
    public function getLocalFileHeaderOffset()
    {
        return $this->_localHeaderOffset;
    }
}
