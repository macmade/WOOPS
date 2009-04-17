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
 * SWF file parser
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf
 */
class Woops_Swf_Parser
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF file object
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
     * @param   string      The location of the SWF file
     * @return  void
     */
    public function __construct( $file )
    {
        // Create a new SWF file object
        $this->_file     = new Woops_Swf_File();
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Creates the binary stream
        $this->_stream   = new Woops_Swf_Binary_File_Stream( $file );
        
        // Parses the file
        $this->_parseFile();
        
        // Deletes the stream object
        unset( $this->_stream );
    }
    
    protected function _parseFile()
    {
        // Gets the SWF header
        $header = $this->_file->getHeader();
        
        // Processes the SWF header
        $header->processData( $this->_stream );
        
        // Process the tags
        while(!$this->_stream->endOfStream() ) {
            
            // Gets thge tag record header
            $tagHeader = $this->_stream->littleEndianUnsignedShort();
            
            // Gets the tag type
            $tagType   = $tagHeader >> 6;
            
            // Gets the tag length
            $tagLength = $tagHeader & 0x3F;
            
            // Checks for a 32bit length
            if( $tagLength === 0x3F ) {
                
                // Tag is long
                $tagLength = $this->_stream->littleEndianUnsignedLong();
            }
            
            // Creates the tag
            $tag     = $this->_file->newTag( $tagType );
            
            // Creates a binary stream with the tag data
            $tagData = new Woops_Swf_Binary_Stream( $this->_stream->read( $tagLength ) );
            
            // Processes the tag data
            $tag->processData( $tagData );
        }
    }
    
    /**
     * Gets the SWF file object
     * 
     * @return  Woops_Swf_File  The SWF file object
     */
    public function getFile()
    {
        return $this->_file;
    }
}
