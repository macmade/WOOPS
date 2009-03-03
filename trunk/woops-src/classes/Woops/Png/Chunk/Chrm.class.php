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
 * PNG cHRM chunk (primary chromaticities and white point)
 * 
 * The cHRM chunk may be used to specify the 1931 CIE x,y chromaticities of the
 * red, green, and blue display primaries used in the image, and the referenced
 * white point.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Woops_Png_Chunk_Chrm extends Woops_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'cHRM';
    
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
        $data              = new stdClass();
        
        // Gets the XY chromaticities for the white point
        $data->whitePointX = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 0 ) / 100000;
        $data->whitePointY = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 ) / 100000;
        
        // Gets the XY chromaticities for red
        $data->redX        = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 ) / 100000;
        $data->redY        = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 ) / 100000;
        
        // Gets the XY chromaticities for green
        $data->greenX      = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 16 ) / 100000;
        $data->greenY      = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 20 ) / 100000;
        
        // Gets the XY chromaticities for blue
        $data->blueX       = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 24 ) / 100000;
        $data->blueY       = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 28 ) / 100000;
        
        // Returns the processed data
        return $data;
    }
}
