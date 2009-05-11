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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Gzip;

/**
 * GZIP member
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Gzip
 */
class Member extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The supported compression methods
     */
    const COMPRESSION_DEFLATE = 0x08;
    
    /**
     * The supported operating systems
     */
    const OS_FAT              = 0x00;
    const OS_MSDOS            = 0x00;
    const OS_OS2              = 0x00;
    const OS_NT               = 0x00;
    const OS_WIN32            = 0x00;
    const OS_AMIGA            = 0x01;
    const OS_VMS              = 0x02;
    const OS_OPEN_VMS         = 0x02;
    const OS_UNIX             = 0x03;
    const OS_VM_CMS           = 0x04;
    const OS_ATARI_TOS        = 0x05;
    const OS_HPFS             = 0x06;
    const OS_MACINTOSH        = 0x07;
    const OS_Z_SYSTEM         = 0x08;
    const OS_CP_M             = 0x09;
    const OS_TOPS_20          = 0x0A;
    const OS_NTFS             = 0x0B;
    const OS_QDOS             = 0x0C;
    const OS_ACORN_RISCOS     = 0x0D;
    const OS_UNKNOWN          = 0xFF;
    
    /**
     * The member's flags
     */
    const FLAG_TEXT           = 0x01;
    const FLAG_HRCR           = 0x02;
    const FLAG_EXTRA          = 0x04;
    const FLAG_NAME           = 0x08;
    const FLAG_COMMENT        = 0x10;
    
    /**
     * The member's extra flags
     */
    const XFLAG_MAX           = 0x02;
    const XFLAG_FAST          = 0x04;
    
    /**
     * The types of the extra fields
     */
    const EXTRA_APOLLO        = 0x7041;
    
    /**
     * The extra field types, with their corresponding PHP class name
     */
    protected static $_extraFields = array(
        0x7041 => '\Woops\Gzip\ExtraField\ApolloFileTypeInformation'
    );
    
    /**
     * The compression method
     */
    protected $_compressionMethod  = 8;
    
    /**
     * The modification time
     */
    protected $_mTime              = 0;
    
    /**
     * The operating system
     */
    protected $_os                 = 0;
    
    /**
     * The extra field
     */
    protected $_extraField         = NULL;
    
    /**
     * The file name
     */
    protected $_fileName           = '';
    
    /**
     * The file comment
     */
    protected $_fileComment        = '';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops\Gzip\Binary\Stream    The binary stream
     * @return  void
     */
    public function processData( Binary\Stream $stream )
    {
        // Gets the member signature
        $signature = $stream->littleEndianUnsignedShort();
        
        // Checks the member signature
        if( $signature !== 0x8B1F ) {
            
            // Error - Invalid member signature
            throw new Member\Exception(
                'Invalid member signature (' . $signature . ')',
                Member\Exception::EXCEPTION_BAD_SIGNATURE
            );
        }
        
        // Gets the compression method
        $this->_compressionMethod = $stream->unsignedChar();
        
        // Checks the compression method
        if( $this->_compressionMethod !== self::COMPRESSION_DEFLATE ) {
            
            // Error - Invalid compression method
            throw new Member\Exception(
                'Invalid compression method (' . $this->_compressionMethod . ')',
                Member\Exception::EXCEPTION_BAD_COMPRESSION_METHOD
            );
        }
        
        // Gets the flags
        $flags                    = $stream->unsignedChar();
        
        // Gets the modification time
        $this->_mTime             = $stream->littleEndianUnsignedLong();
        
        // Gets the extra flags
        $extraFlags               = $stream->unsignedChar();
        
        // Gets the operating system
        $this->_os                = $stream->unsignedChar();
        
        // Checks for an extra field
        if( $flags & self::FLAG_EXTRA ) {
            
            // Gets the extra field type
            $type = $stream->littleEndianUnsignedShort();
            
            // Checks if the type is known
            if( isset( self::$_extraFields[ $type ] ) ) {
                
                // Gets the field class
                $fieldClass = self::$_extraFields[ $type ];
                
                // Creates the extra field
                $this->_extraField = new $fieldClass();
                
            } else {
                
                // Unknown extra field
                $this->_extraField = new UnknownExtraField( $type );
            }
            
            // Process the extra field data
            $this->_extraField->processData( $stream );
        }
        
        // Checks for a file name
        if( $flags & self::FLAG_NAME ) {
            
            // Gets the file name
            $this->_fileName = $stream->nullTerminatedString();
        }
        
        // Checks for a file comment
        if( $flags & self::FLAG_COMMENT ) {
            
            // Gets the file comment
            $this->_fileComment = $stream->nullTerminatedString();
        }
        
        // Checks for a CRC16
        if( $flags & self::FLAG_HRCR ) {
            
            // Gets the CRC16
            $crc16 = $stream->littleEndianUnsignedShort();
        }
    }
    
    /**
     * Gets the compression method
     * 
     * @return  int     The compression method
     */
    public function getCompressionMethod()
    {
        return $this->_compressionMethod;
    }
    
    /**
     * Gets the modification time
     * 
     * @return  int     The modification time
     */
    public function getMTime()
    {
        return $this->_mTime;
    }
    
    /**
     * Gets the file name
     * 
     * @return  string  The file name
     */
    public function getFileName()
    {
        return $this->_fileName;
    }
    
    /**
     * Gets the file comment
     * 
     * @return  string  The file comment
     */
    public function getFileComment()
    {
        return $this->_fileComment;
    }
    
    /**
     * Gets the operating system
     * 
     * @return  int     The operating system
     */
    public function getOs()
    {
        return $this->_os;
    }
    
    /**
     * Gets the extra field 
     * 
     * @return  mixed   An instance of Woops\Gzip\ExtraField, or NULL if no extra field is present
     */
    public function getExtraField()
    {
        return $this->_extraField;
    }
    
    /**
     * Gets the compression method
     * 
     * @param   int     The compression method (one of the COMPRESSION_XXX constant)
     * @return  void
     */
    public function setCompressionMethod( $value )
    {
        $this->_compressionMethod = ( int )$value;
    }
    
    /**
     * Gets the modification time
     * 
     * @param   int     The modification time
     * @return  void
     */
    public function setMTime( $value )
    {
        $this->_mTime = ( int )$value;
    }
    
    /**
     * Gets the file name
     * 
     * @param   string  The file name
     * @return  void
     */
    public function setFileName( $value )
    {
        $this->_fileName = ( string )$value;
    }
    
    /**
     * Gets the file comment
     * 
     * @param   string  The file comment
     * @return  void
     */
    public function setFileComment( $value )
    {
        $this->_fileComment = ( string )$value;
    }
    
    /**
     * Gets the operating system
     * 
     * @param   int     The operating system (one of the OS_XXX constant)
     * @return  void
     */
    public function setOs( $value )
    {
        $this->_os = ( int )$value;
    }
    
    /**
     * Gets the extra field 
     * 
     * @param   Woops\Gzip\ExtraField   The extra field object
     * @return  void
     */
    public function setExtraField( ExtraField $field )
    {
        $this->_extraField = $field;
    }
}
