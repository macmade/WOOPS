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
 * PNG PLTE chunk (color palette)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Plte extends \Woops\Png\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The chunk type
     */
    protected $_type = 'PLTE';
    
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
        $data          = new \stdClass();
        $data->palette = array();
        
        // Process each color
        while( !$this->_stream->endOfStream() ) {
            
            // Storage
            $color        = new \stdClass();
            
            // Gets the colors values
            $red          = $this->_stream->unsignedChar();
            $green        = $this->_stream->unsignedChar();
            $blue         = $this->_stream->unsignedChar();
            
            // Gets the hexadecimal values
            $redHex       = dechex( $red );
            $greenHex     = dechex( $green );
            $blueHex      = dechex( $blue );
            
            // Completes each hexadecimal value if needed
            $redHex       = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
            $greenHex     = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
            $blueHex      = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
            
            // Stores the color values
            $color->red   = $red;
            $color->green = $green;
            $color->blue  = $blue;
            $color->hex   = '#' . strtoupper( $redHex . $greenHex . $blueHex );
            
            // Adds the current color
            $data->palette[] = $color;
        }
        
        // Returns the processed data
        return $data;
    }
}
