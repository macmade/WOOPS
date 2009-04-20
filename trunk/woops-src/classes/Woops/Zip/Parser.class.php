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
 * ZIP file parser
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Zip
 */
class Woops_Zip_Parser
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The ZIP file object
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
     * @param   string      The location of the ZIP file
     * @return  void
     */
    public function __construct( $file )
    {
        // Create a new ZIP file object
        $this->_file     = new Woops_Zip_File();
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Creates the binary stream
        $this->_stream   = new Woops_Zip_Binary_File_Stream( $file );
        
        // Parses the file
        $this->_parseFile();
        
        // Deletes the stream object
        unset( $this->_stream );
    }
    
    protected function _parseFile()
    {
        // Gets the offset for the central directory
        $offset = $this->_stream->pos( chr( 0x50 ) . chr( 0x4B ) . chr( 0x01 ) . chr( 0x02 ) );
        
        // Checks if a central directory was found
        if( $offset === false ) {
            
            // Error - No central directory
            throw new Woops_Zip_Parser_Exception(
                'No ZIP central directory found',
                Woops_Zip_Parser_Exception::EXCEPTION_NO_CENTRAL_DIRECTORY
            );
        }
        
        // Moves to the start of the central directory
        $this->_stream->seek( $offset, Woops_Zip_Binary_File_Stream::SEEK_SET );
        
        // Gets the central directory
        $centralDirectory = $this->_file->getCentralDirectory();
        
        // Processes the central directory data
        $centralDirectory->processData( $this->_stream );
        
        // Signature of the local file headers
        $localSignature = chr( 0x50 ) . chr( 0x4B ) . chr( 0x03 ) . chr( 0x04 );
        
        // Process each central file header
        foreach( $centralDirectory as $key => $centralFileHeader ) {
            
            // Gets the offset of the local file header
            $offset = $centralFileHeader->getLocalFileHeaderOffset();
            
            // Moves to the start of the local file header
            $this->_stream->seek( $offset, Woops_Zip_Binary_File_Stream::SEEK_SET );
            
            // Gets the local file header signature
            $signature = $this->_stream->read( 4 );
            
            // Checks the local file header signature
            if( $signature !== $localSignature ) {
                
                // Error - Invalid local file header signature
                throw new Woops_Zip_Parser_Exception(
                    'Invalid ZIP local file header signature (' . $signature . ')',
                    Woops_Zip_Parser_Exception::EXCEPTION_BAD_FILE_HEADER_SIGNATURE
                );
            }
            
            // Creates a new local file header and adds it to the file
            $header = new Woops_Zip_Local_File_Header();
            $this->_file->addLocalFileHeader( $header );
            
            // Processes the local file header data
            $header->processData( $this->_stream );
            
            // Do not process the file data for now
            $this->_stream->seek( $centralFileHeader->getCompressedSize(), Woops_Zip_Binary_File_Stream::SEEK_CUR );
            
            // Checks if a data descriptor is present
            if( $header->hasDataDescriptor() ) {
                
                // Creates a new data descriptor and stores it
                $descriptor = new Woops_Zip_Data_Descriptor();
                $this->_file->addDataDescriptor( $descriptor );
                
                // Processes the data descriptor data
                $descriptor->processData( $this->_stream );
            }
        }
    }
    
    /**
     * Gets the ZIP file object
     * 
     * @return  Woops_Zip_File  The ZIP file object
     */
    public function getFile()
    {
        return $this->_file;
    }
}
