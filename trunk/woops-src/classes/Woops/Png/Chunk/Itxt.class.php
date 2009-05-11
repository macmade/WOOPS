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

# $Id$

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Png\Chunk;

/**
 * PNG iTXt chunk (international textual data)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Itxt extends \Woops\Png\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The chunk type
     */
    protected $_type = 'iTXt';
    
    /**
     * Process the chunk data
     * 
     * This method will process the chunk raw data and returns human readable
     * values, stored as properties of an stdClass object. Please take a look
     * at the PNG specification for this specific chunk to see which data will
     * be extracted.
     * 
     * @return  stdClass    The human readable chunk data
     */
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Storage
        $data                    = new \stdClass();
        
        // Gets the profile name
        $data->keyword           = $this->_stream->nullTerminatedString();
        
        // Gets the compression flag
        $data->compressionFlag   = $this->_stream->unsignedChar();
        
        // Gets the compression method
        $data->compressionMethod = $this->_stream->unsignedChar();
        
        // Gets the language tag
        $data->languageTag       = $this->_stream->nullTerminatedString();
        
        // Gets the translated keyword
        $data->translatedKeyword = $this->_stream->nullTerminatedString();
        
        // Checks the compression method
        if( $data->compressionFlag && $data->compressionMethod === 0 ) {
            
            // Deflate
            $data->text = gzuncompress( $this->_stream->getRemainingData() );
            
        } else {
            
            // No compression, or unrecognized compression method - Stores the raw data
            $data->text = $this->_stream->getRemainingData();
        }
        
        // Returns the processed data
        return $data;
    }
}
