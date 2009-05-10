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
 * PNG sRGB chunk (standard RGB colour space)
 * 
 * If the sRGB chunk is present, the image samples conform to the sRGB colour
 * space [IEC 61966-2-1] and should be displayed using the specified rendering
 * intent defined by the International Color Consortium [ICC-1] and [ICC-1A].
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Srgb extends \Woops\Png\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * Rendering intent values
     */
    const RGB_PERCEPTUAL            = 0x0;
    const RGB_RELATIVE_COLORIMETRIC = 0x1;
    const RGB_SATURATION            = 0x2;
    const RGB_ABSOLUTE_COLORIMETRIC = 0x3;
    
    /**
     * The chunk type
     */
    protected $_type = 'sRGB';
    
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
        $data                  = new \stdClass();
        
        // Gets the rendering intent
        $data->renderingIntent = $this->_stream->unsignedChar();
        
        // Stores the human readable rendering intent
        $data->perceptual           = ( $data->renderingIntent === self::RGB_PERCEPTUAL )            ? true : false;
        $data->relativeColorimetric = ( $data->renderingIntent === self::RGB_RELATIVE_COLORIMETRIC ) ? true : false;
        $data->saturation           = ( $data->renderingIntent === self::RGB_SATURATION )            ? true : false;
        $data->absoluteColorimetric = ( $data->renderingIntent === self::RGB_ABSOLUTE_COLORIMETRIC ) ? true : false;
        
        // Returns the processed data
        return $data;
    }
}
