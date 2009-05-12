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
namespace Woops\Midi;

/**
 * MIDI file parser
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi
 */
class Parser extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
     * @param   string      The location of the MIDI file
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
        // Process the whole stream
        while( !$this->_stream->endOfStream() ) {
            
            // Gets the chunk type
            $type = $this->_stream->bigEndianUnsignedLong();
            
            // Creates a new chunk
            $chunk = $this->_file->newChunk( $type );
            
            // Processes the chunk data
            $chunk->processData( $this->_stream );
        }
    }
    
    /**
     * Gets the MIDI file object
     * 
     * @return  Woops\Midi\File  The MIDI file object
     */
    public function getFile()
    {
        return $this->_file;
    }
}