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
namespace Woops\Zip\Central;

/**
 * ZIP central directory
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Zip.Central
 */
class Directory extends \Woops\Core\Object implements \Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The central file headers
     */
    protected $_centralFileHeaders = array();
    
    /**
     * The digital signature
     */
    protected $_digitalSignature   = NULL;
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos        = 0;
    
    /**
     * Gets the current file header object (SPL Iterator method)
     * 
     * @return  Woops\Zip\Central\File\Header   The current file header object
     */
    public function current()
    {
        return $this->_centralFileHeaders[ $this->_iteratorPos ];
    }
    
    /**
     * Moves to the next file header object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current file header object (SPL Iterator method)
     * 
     * @return  int     The index of the current file header
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next file header object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next file header, otherwise false
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_centralFileHeaders );
    }
    
    /**
     * Rewinds the SPL Iterator pointer (SPL Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_iteratorPos = 0;
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops\Zip\Binary\Stream The binary stream
     * @return  void
     */
    public function processData( \Woops\Zip\Binary\Stream $stream )
    {
        // Resets the digital signature
        $this->_digitalSignature = NULL;
        
        // Signature of the central file headers
        $fileHeaderSignature     = chr( 0x50 ) . chr( 0x4B ) . chr( 0x01 ) . chr( 0x02 );
        
        // Gets the central file header signature
        $signature               = $stream->read( 4 );
        
        // Checks the central file header signature
        if( $signature !== $fileHeaderSignature ) {
            
            // Error - Invalid central file header signature
            throw new Directory\Exception(
                'Invalid central file header signature',
                Directory\Exception::EXCEPTION_BAD_FILE_HEADER_SIGNATURE
            );
        }
        
        // Processes the central file headers
        while( $signature === $fileHeaderSignature ) {
            
            // Creates and stores the central file header object
            $fileHeader = new File\Header();
            $this->_centralFileHeaders[] = $fileHeader;
            
            // Processes the central file header data
            $fileHeader->processData( $stream );
            
            // Gets the next 4 bytes, to find an additionnal signature
            $signature = $stream->read( 4 );
        }
        
        // Checks for a digital signature
        if( $signature === chr( 0x50 ) . chr( 0x4B ) . chr( 0x05 ) . chr( 0x05 ) ) {
            
            // Creates the digital signature
            $this->_digitalSignature = new \Woops\Zip\Digital\Signature();
            
            // Process the digital signature data
            $this->_digitalSignature->processData( $stream );
            
            // Gets the next 4 bytes, to find an additionnal signature
            $signature = $stream->read( 4 );
            
        } else {
            
            // Rewinds the stream
            $stream->seek( -4, \Woops\Zip\Binary\Stream::SEEK_CUR );
        }
    }
}
