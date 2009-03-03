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
 * PNG bKGd chunk (background colour)
 * 
 * The bKGD chunk specifies a default background colour to present the image
 * against. If there is any other preferred background, either user-specified
 * or part of a larger page (as in a browser), the bKGD chunk should be ignored.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Woops_Png_Chunk_Bkgd extends Woops_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'bKGD';
    
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
        // Storage
        $data     = new stdClass();
        
        // Gets the IHDR chunk
        $ihdr     = $this->_pngFile->IHDR;
        
        // Process the IHDR data
        $ihdrData = $ihdr->getProcessedData();
        
        // Checks the data length
        switch( $ihdrData->colourType ) {
            
            // Greyscale
            case 0:
                
                // Gets the greyscale value
                $data->greyscale = self::$_binUtils->unsignedChar( $this->_data, 1 );
                break;
            
            // RGB
            case 2:
                
                // Gets the color values
                $data->red   = self::$_binUtils->unsignedChar( $this->_data, 1 );
                $data->green = self::$_binUtils->unsignedChar( $this->_data, 3 );
                $data->blue  = self::$_binUtils->unsignedChar( $this->_data, 5 );
                
                // Gets the hexadecimal values
                $redHex      = dechex( $data->red );
                $greenHex    = dechex( $data->green );
                $blueHex     = dechex( $data->blue );
                
                // Completes each hexadecimal value if needed
                $redHex      = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
                $greenHex    = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
                $blueHex     = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
                
                // Adds the hexadecimal color value to colour type 2
                $data->hex   = '#' . strtoupper( $redHex . $greenHex . $blueHex );
                break;
            
            // Indexed color
            case 3:
                
                // Gets the palette index
                $data->paletteIndex = self::$_binUtils->unsignedChar( $this->_data, 0 );
                break;
            
            // Greyscale with alpha
            case 4:
                
                // Gets the greyscale and the alpha value
                $data->greyscale      = self::$_binUtils->unsignedChar( $this->_data, 0 );
                $data->greyscaleAlpha = self::$_binUtils->unsignedChar( $this->_data, 1 );
                break;
            
            // RGB with alpha
            case 6:
                
                // Gets the color and the alpha values
                $data->red        = self::$_binUtils->unsignedChar( $this->_data, 0 );
                $data->redAlpha   = self::$_binUtils->unsignedChar( $this->_data, 1 );
                $data->green      = self::$_binUtils->unsignedChar( $this->_data, 2 );
                $data->greenAlpha = self::$_binUtils->unsignedChar( $this->_data, 3 );
                $data->blue       = self::$_binUtils->unsignedChar( $this->_data, 4 );
                $data->blueAlpha  = self::$_binUtils->unsignedChar( $this->_data, 5 );
                
                // Gets the hexadecimal values
                $redHex           = dechex( $data->red );
                $greenHex         = dechex( $data->green );
                $blueHex          = dechex( $data->blue );
                
                // Completes each hexadecimal value if needed
                $redHex           = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
                $greenHex         = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
                $blueHex          = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
                
                // Adds the hexadecimal color value to colour type 2
                $data->hex        = '#' . strtoupper( $redHex . $greenHex . $blueHex );
                break;
            
        }
        
        // Returns the processed data
        return $data;
    }
}
