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
 * SWF file header
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf
 */
class Woops_Swf_Header
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF version
     */
    protected $_version      = 0;
    
    /**
     * Whether the SWF file is compressed or not
     */
    protected $_isCompressed = false;
    
    /**
     * The rectangle object for the frame size
     */
    protected $_frameSize    = NULL;
    
    /**
     * The frame delay in 8.8 fixed number of frames per second
     */
    protected $_frameRate    = 0;
    
    /**
     * The total number of frames in file
     */
    protected $_frameCount   = 0;
    
    /**
     * Class constructor
     * 
     * @param   int     The SWF version
     * @return  void
     */
    public function __construct( $version = 10 )
    {
        // Stores the SWF version
        $this->_version   = ( int )$version;
        
        // Creates the rectangle object for the frame size
        $this->_frameSize = new Woops_Swf_Record_Rectangle();
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        // Gets the SWF file signature
        $signature = $stream->read( 3 );
        
        // Checks the SWF file signature
        if( $signature === 'FWS' ) {
            
            // No compression
            $this->_isCompressed = false;
            
        } elseif( $signature === 'CWS' ) {
            
            // SWF file data is compressed
            $this->_isCompressed = true;
            
        } else {
            
            // Error - Invalid SWF signature
            throw new Woops_Swf_Header_Exception(
                'Invalid SWF file signature (' . $signature . ')',
                Woops_Swf_Header_Exception::EXCEPTION_BAD_SIGNATURE
            );
        }
        
        // Gets the SWF version
        $this->_version = $stream->unsignedChar();
        
        // Do not process the file size
        $stream->seek( 4, Woops_Swf_Binary_Stream::SEEK_CUR );
        
        // Checks if we have to uncompress the SWF data
        if( $this->_isCompressed ) {
            
            // Uncompress the SWF data
            $stream->uncompressData();
        }
        
        // Processes the frame size rectangle
        $this->_frameSize->processData( $stream );
        
        // Gets the frame rate
        $this->_frameRate  = $stream->littleEndianFixedPoint( 8, 8 );
        
        // Gets the number of frames
        $this->_frameCount = $stream->littleEndianUnsignedShort();
    }
    
    /**
     * Checks whether the SWF file data is compressed or not
     * 
     * @return  boolean     True if the SWF file data is compressed, otherwise false
     */
    public function isCompressed()
    {
        return $this->_isCompressed;
    }
    
    /**
     * Sets the SWF file compression flag
     * 
     * @param   boolean True if the SWF file data must be compressed
     * @throws  Woops_Swf_Header_Exception  If the SWF version is smaller than 6, as the compression is only available since this version
     */
    public function setCompression( $value )
    {
        // Sets the compression flag
        $this->_isCompressed = ( boolean )$value;
        
        // Checks the SWF version
        if( $this->_version < 6 ) {
            
            // Error - Incompatible version
            throw new Woops_Swf_Header_Exception(
                'Incompatible SWF version (' . $this->_version . ')',
                Woops_Swf_Header_Exception::EXCEPTION_BAD_VERSION
            );
        }
    }
}
