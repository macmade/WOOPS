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
 * PNG pHYs chunk (physical pixel dimensions)
 * 
 * The pHYs chunk specifies the intended pixel size or aspect ratio for
 * display of the image.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Phys extends \Woops\Png\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'pHYs';
    
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
        $data                = new \stdClass();
        
        // Gets the pixel aspect ratio
        $data->pixelPerUnitX = $this->_stream->bigEndianUnsignedLong();
        $data->pixelPerUnitY = $this->_stream->bigEndianUnsignedLong();
        
        // Gets the unit
        $data->unit          = $this->_stream->unsignedChar();
        
        // Returns the processed data
        return $data;
    }
}
