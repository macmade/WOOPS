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
 * GZIP file parser
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Gzip
 */
class Woops_Gzip_Parser extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The GZIP file object
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
     * @param   string      The location of the GZIP file
     * @return  void
     */
    public function __construct( $file )
    {
        // Create a new GZIP file object
        $this->_file     = new Woops_Gzip_File();
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Creates the binary stream
        $this->_stream   = new Woops_Gzip_Binary_File_Stream( $file );
        
        // Parses the file
        $this->_parseFile();
        
        // Deletes the stream object
        unset( $this->_stream );
    }
    
    protected function _parseFile()
    {
        // Signature for the GZIP members
        $memberSignature = chr( 31 ) . chr( 139 ) . chr( 8 );
        
        // Gets the offset of the first member
        $memberOffset    = $this->_stream->pos( $memberSignature );
        
        // Checks if a member signature was found
        if( $memberOffset === false ) {
            
            // Error - No member in the GZIP file
            throw new Woops_Gzip_Parser_Exception(
                'No member in the GZIP file',
                Woops_Gzip_Parser_Exception::EXCEPTION_NO_MEMBER
            );
        }
        
        // Process each member
        while( $memberOffset !== false ) {
            
            // Moves the stream pointer to the start of the member
            $this->_stream->seek( $memberOffset, Woops_Gzip_Binary_File_Stream::SEEK_SET );
            
            // Creates a new GZIP member
            $member = new Woops_Gzip_Member();
            
            // Adds the member to the GZIP file
            $this->_file->addMember( $member );
            
            // Processes the member data
            $member->processData( $this->_stream );
            
            // Tries to find another member
            $memberOffset = $this->_stream->pos( $memberSignature, $this->_stream->getOffset() );
        }
    }
    
    /**
     * Gets the GZIP file object
     * 
     * @return  Woops_Gzip_File The GZIP file object
     */
    public function getFile()
    {
        return $this->_file;
    }
}
