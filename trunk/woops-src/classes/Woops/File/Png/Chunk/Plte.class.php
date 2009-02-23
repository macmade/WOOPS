<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * PNG PLTE chunk (color palette)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Png.Chunk
 */
class Woops_File_Png_Chunk_Plte extends Woops_File_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
        // Storage
        $data          = new stdClass();
        $data->palette = array();
        
        // Process each color
        for( $i = 0; $i < $this->_dataLength; $i += 3 ) {
            
            // Storage
            $color        = new StdClass();
            
            // Gets the colors values
            $red          = self::$_binUtils->unsignedChar( $this->_data, $i );
            $green        = self::$_binUtils->unsignedChar( $this->_data, $i + 1 );
            $blue         = self::$_binUtils->unsignedChar( $this->_data, $i + 2 );
            
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
