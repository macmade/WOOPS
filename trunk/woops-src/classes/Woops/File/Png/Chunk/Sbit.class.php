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
 * PNG sBIT chunk (significant bits)
 * 
 * To simplify decoders, PNG specifies that only certain sample depths may be
 * used, and further specifies that sample values should be scaled to the full
 * range of possible values at the sample depth. The sBIT chunk defines the
 * original number of significant bits (which can be less than or equal to the
 * sample depth). This allows PNG decoders to recover the original data
 * losslessly even if the data had a sample depth not  directly supported
 * by PNG.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Png.Chunk
 */
class Woops_File_Png_Chunk_Sbit extends Woops_File_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'sBIT';
    
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
                
                // Gets the significant bits
                $data->significantGreyscaleBits = self::$_binUtils->unsignedChar( $this->_data, 0 );
                break;
            
            // RGB
            case 2:
                
                // Gets the significant bits
                $data->significantRedBits   = self::$_binUtils->unsignedChar( $this->_data, 0 );
                $data->significantGreenBits = self::$_binUtils->unsignedChar( $this->_data, 1 );
                $data->significantBlueBits  = self::$_binUtils->unsignedChar( $this->_data, 2 );
                break;
            
            // Indexed color
            case 3:
                
                // Gets the significant bits
                $data->significantRedBits   = self::$_binUtils->unsignedChar( $this->_data, 0 );
                $data->significantGreenBits = self::$_binUtils->unsignedChar( $this->_data, 1 );
                $data->significantBlueBits  = self::$_binUtils->unsignedChar( $this->_data, 2 );
                break;
            
            // Greyscale with alpha
            case 4:
                
                // Gets the significant bits
                $data->significantGreyscaleBits = self::$_binUtils->unsignedChar( $this->_data, 0 );
                $data->significantAlphaBits     = self::$_binUtils->unsignedChar( $this->_data, 1 );
                break;
            
            // RGB with alpha
            case 6:
                
                // Gets the significant bits
                $data->significantRedBits   = self::$_binUtils->unsignedChar( $this->_data, 0 );
                $data->significantGreenBits = self::$_binUtils->unsignedChar( $this->_data, 1 );
                $data->significantBlueBits  = self::$_binUtils->unsignedChar( $this->_data, 2 );
                $data->significantAlphaBits = self::$_binUtils->unsignedChar( $this->_data, 3 );
                break;
        }
        
        // Returns the processed data
        return $data;
    }
}
