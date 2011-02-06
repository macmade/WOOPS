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
 * PNG tRNs chunk (transparency)
 * 
 * The tRNS chunk specifies either alpha values that are associated with
 * palette entries (for indexed-colour images) or a single transparent colour
 * (for greyscale and truecolour images).
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Trns extends \Woops\Png\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The chunk type
     */
    protected $_type = 'tRNs';
    
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
        $data     = new \stdClass();
        
        // Gets the IHDR chunk
        $ihdr     = $this->_pngFile->IHDR;
        
        // Process the IHDR data
        $ihdrData = $ihdr->getProcessedData();
        
        // Checks the data length
        switch( $ihdrData->colourType )
        {
            // Greyscale
            case 0:
                
                // Gets the sample value
                $data->greySampleValue = $this->_stream->bigEndianUnsignedShort();
                break;
            
            // RGB
            case 2:
                
                // Gets the sample values
                $data->redSampleValue   = $this->_stream->bigEndianUnsignedShort();
                $data->greenSampleValue = $this->_stream->bigEndianUnsignedShort();
                $data->blueSampleValue  = $this->_stream->bigEndianUnsignedShort();
                break;
            
            // Indexed color
            case 3:
                
                // Storage
                $data->alphasForPaletteIndexes = array();
                
                // Process the chunk data till the end
                while( !$this->_stream->endOfStream() )
                {
                    // Gets the alpha for the current palette index
                    $data->alphasForPaletteIndexes[] = $this->_stream->unsignedChar();
                }
                
                break;
        }
        
        // Returns the processed data
        return $data;
    }
}
