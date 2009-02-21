<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * PNG IHDR chunk (image header)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Png.Chunk
 */
class Woops_File_Png_Chunk_Ihdr extends Woops_File_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'IHDR';
    
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
        $data = new stdClass();
        
        // Gets the PNG dimensions
        $data->width             = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 0 );
        $data->height            = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        
        // Gets the bit depth
        $data->bitDepth          = self::$_binUtils->unsignedChar( $this->_data, 8 );
        
        // Gets the colour type
        $data->colourType        = self::$_binUtils->unsignedChar( $this->_data, 9 );
        
        // Gets the compression method
        $data->compressionMethod = self::$_binUtils->unsignedChar( $this->_data, 10 );
        
        // Gets the filter method
        $data->filterMethod      = self::$_binUtils->unsignedChar( $this->_data, 11 );
        
        // Gets the interlace method
        $data->interlaceMethod   = self::$_binUtils->unsignedChar( $this->_data, 12 );
        
        // Returns the processed data
        return $data;
    }
}
