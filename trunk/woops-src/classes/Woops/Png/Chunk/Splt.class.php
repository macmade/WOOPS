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
 * PNG sPLt chunk (suggested palette)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Woops_Png_Chunk_Splt extends Woops_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'sPLt';
    
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
        
        // Position of the null separator
        $null              = strpos( $this->_data, chr( 0 ) );
        
        // Gets the palette name
        $data->paletteName = substr( $this->_data, 0, $null );
        
        // Gets the sample depth
        $data->sampleDepth = self::$_binUtils->unsignedChar( $this->_data, $null + 1 );
        
        // Storage for the palette entries
        $data->entries     = array();
        
        // Checks the sample depth
        if( $data->sampleDepth === 8 ) {
            
            // 8 bit depth - Palette entries are 6 bytes
            $entrySize        = 6;
            
            // Size of the values in the palette entries is 1 byte
            $entryValueSize   = 1;
            
            // Method to get the values in the palette entries
            $entryValueMethod = 'unsignedChar';
            
        } elseif( $data->sampleDepth === 16 ) {
            
            // 16 bit depth - Palette entries are 10 bytes
            $entrySize = 10;
            
            // Size of the values in the palette entries is 2 byte
            $entryValueSize = 1;
            
            // Method to get the values in the palette entries
            $entryValueMethod = 'bigEndianUnsignedShort';
            
            
        } else {
            
            // Invalid depth - Do not process the palette entries
            return $data;
        }
        
        // Process each palette entry
        for( $i = $null + 2; $i < $this->_dataLength; $i += $entryLength ) {
            
            // Storage for the current entry
            $entry = new stdClass();
            
            // Gets the values for the current palette entry (depending of the sample depth)
            $red              = self::$_binUtils->$entryValueMethod( $this->_data, $i );
            $green            = self::$_binUtils->$entryValueMethod( $this->_data, $i + $entryValueSize );
            $blue             = self::$_binUtils->$entryValueMethod( $this->_data, $i + ( $entryValue * 2 ) );
            $alpha            = self::$_binUtils->$entryValueMethod( $this->_data, $i + ( $entryValue * 3 ) );
            
            // Gets the frequency - Always 2 bytes
            $frequency        = self::$_binUtils->bigEndianUnsignedShort( $this->_data, $i + ( $entryValue * 4 ) );
            
            // Gets the hexadecimal values
            $redHex           = dechex( $red );
            $greenHex         = dechex( $green );
            $blueHex          = dechex( $blue );
            
            // Completes each hexadecimal value if needed
            $redHex           = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
            $greenHex         = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
            $blueHex          = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
            
            // Stores the values
            $entry->red       = $red;
            $entry->green     = $green;
            $entry->blue      = $blue;
            $entry->alpha     = $alpha;
            $entry->frequency = $frequency;
            $entry->hex       = '#' . strtoupper( $redHex . $greenHex . $blueHex );
            
            // Adds the current entry
            $data->entries[] = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
