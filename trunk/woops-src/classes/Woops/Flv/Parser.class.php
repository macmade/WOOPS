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
namespace Woops\Flv;

/**
 * FLV file parser
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Flv
 */
class Parser extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The TIFF file object
     */
    protected $_file     = NULL;
    
    /**
     * The binary stream
     */
    protected $_stream   = NULL;
    
    /**
     * The file path
     */
    protected $_filePath = '';
    
    /**
     * Class constructor
     * 
     * @param   string      The location of the TIFF file
     * @return  void
     */
    public function __construct( $file )
    {
        // Create a new TIFF file object
        $this->_file     = new File();
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Creates the binary stream
        $this->_stream   = new Binary\File\Stream( $file );
        
        // Parses the file
        $this->_parseFile();
        
        // Deletes the stream object
        unset( $this->_stream );
    }
    
    protected function _parseFile()
    {
        // Gets the TIFF header
        $header = $this->_file->getHeader();
        
        // Process the header data
        $header->processData( $this->_stream );
        
        // Moves to the data start
        $this->_stream->seek( $header->getDataOffset() + 4, Binary\File\Stream::SEEK_SET );
        
        // Process the FLV body
        while( !$this->_stream->endOfStream() )
        {
            $type         = $this->_stream->unsignedChar();
            $tag          = $this->_file->newTag( $type );
            
            $tag->processData( $this->_stream );
            
            $previousSize = $this->_stream->bigEndianUnsignedLong();
        }
    }
    
    /**
     * Gets the FLV file object
     * 
     * @return  Woops\Flv\File  The TIFF file object
     */
    public function getFile()
    {
        return $this->_file;
    }
}
