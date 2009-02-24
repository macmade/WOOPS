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
 * PNG iTXt chunk (international textual data)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Png.Chunk
 */
class Woops_File_Png_Chunk_Itxt extends Woops_File_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'iTXt';
    
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
        $data                    = new stdClass();
        
        // Position of the null separators
        $null1                   = strpos( $this->_data, chr( 0 ) );
        $null2                   = strpos( $this->_data, chr( 0 ), $null1 + 1 );
        $null3                   = strpos( $this->_data, chr( 0 ), $null2 + 1 );
        
        // Gets the profile name
        $data->keyword           = substr( $this->_data, 0, $null );
        
        // Gets the compression flag
        $data->compressionFlag   = self::$_binUtils->unsignedChar( $this->_data, $null1 + 1 );
        
        // Gets the compression method
        $data->compressionMethod = self::$_binUtils->unsignedChar( $this->_data, $null1 + 2 );
        
        // Gets the language tag
        $data->languageTag       = substr( $this->_data, $null1 + 3, $null2 - ( $null1 + 3 ) );
        
        // Gets the translated keyword
        $data->translatedKeyword = substr( $this->_data, $null2 + 1, $null3 - ( $null2 + 1 ) );
        
        // Checks the compression method
        if( $data->compressionFlag && $data->compressionMethod === 0 ) {
            
            // Deflate
            $data->text = gzuncompress( substr( $this->_data, $null3 ) );
            
        } else {
            
            // No compression, or unrecognized compression method - Stores the raw data
            $data->text = substr( $this->_data, $null3 );
        }
        
        // Returns the processed data
        return $data;
    }
}
