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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * SWF binary stream
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Binary
 */
class Woops_Swf_Binary_Stream extends Woops_Binary_Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the SWF data is compressed or not
     */
    protected $_isCompressed   = false;
    
    /**
     * Class constructor
     * 
     * @param   string  The binary data for which to create a stream
     * @return  void
     * @see     Woops_Binary_Stream::__construct
     */
    public function __construct( $data = '' )
    {
        // Calls the parent constructor
        parent::__construct( $data );
        
        // Checks if we have compressed data
        if( $data && substr( $this->_data, 0, 3 ) === 'CWS' ) {
            
            // Sets the compressed flag
            $this->_isCompressed = true;
        }
    }
    
    /**
     * 
     */
    public function compressData()
    {
        // Checks if the GZIP functions are available
        if( !function_exists( 'gzcompress' ) ) {
            
            // Error - No GZIP
            throw new Woops_Swf_Binary_Stream_Exception(
                'The PHP GZIP functions are not available',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_NO_GZIP
            );
        }
        
        // Checks if we have the SWF signature for a compressed file
        if( substr( $this->_data, 0, 3 ) === 'CWS' ) {
            
            // Checks if the data has already been compressed
            if( !$this->_isCompressed ) {
                
                // Compresses the compressed SWF data
                $this->_data         = substr( $this->_data, 0, 8 )
                                     . gzcompress( substr( $this->_data, 8 ) );
                
                // Data has been compressed
                $this->_isCompressed = true;
                
                // Updates the data length
                $this->_dataLength   = strlen( $this->_data );
            }
            
        } else {
            
            // Error - Invalid data
            throw new Woops_Swf_Binary_Stream_Exception(
                'Invalid SWF data',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_
            );
        }
    }
    
    /**
     * Uncompresses the SWF data in the stream
     * 
     * @return  void
     */
    public function uncompressData()
    {
        // Checks if the GZIP functions are available
        if( !function_exists( 'gzuncompress' ) ) {
            
            // Error - No GZIP
            throw new Woops_Swf_Binary_Stream_Exception(
                'The PHP GZIP functions are not available',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_NO_GZIP
            );
        }
        
        // Checks if we have the SWF signature for a compressed file
        if( substr( $this->_data, 0, 3 ) === 'CWS' ) {
            
            // Checks if the data has already been uncompressed
            if( $this->_isCompressed ) {
                
                // Uncompresses the compressed SWF data
                $this->_data         = substr( $this->_data, 0, 8 )
                                     . gzuncompress( substr( $this->_data, 8 ) );
                
                // Data has been uncompressed
                $this->_isCompressed = false;
                
                // Updates the data length
                $this->_dataLength   = strlen( $this->_data );
            }
            
        } else {
            
            // Error - Invalid data
            throw new Woops_Swf_Binary_Stream_Exception(
                'Invalid SWF data',
                Woops_Swf_Binary_Stream_Exception::EXCEPTION_INVALID_DATA
            );
        }
    }
}
