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

/**
 * PNG zTXt chunk (compressed textual data)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Woops_Png_Chunk_Ztxt extends Woops_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'zTXt';
    
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
        $data                           = new stdClass();
        
        // Gets the profile name
        $data->keyword                  = $this->_stream->nullTerminatedString();
        
        // Gets the compression method
        $data->compressionMethod        = $this->_stream->unsignedChar();
        
        // Checks the compression method
        if( $data->compressionMethod === 0 ) {
            
            // Deflate
            $data->compressedTextDataStream = gzuncompress( $this->_stream->getRemainingData() );
            
        } else {
            
            // Unrecognized compression method - Stores the raw data
            $data->compressedTextDataStream = $this->_stream->getRemainingData();
        }
        
        // Returns the processed data
        return $data;
    }
}
