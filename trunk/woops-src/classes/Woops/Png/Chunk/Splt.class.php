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
 * PNG sPLt chunk (suggested palette)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Splt extends \Woops\Png\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
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
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Storage
        $data              = new \stdClass();
        
        // Gets the palette name
        $data->paletteName = $this->_stream->nullTerminatedString();
        
        // Gets the sample depth
        $data->sampleDepth = $this->_stream->unsignedChar();
        
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
        while( !$this->_stream->endOfStream() ) {
            
            // Storage for the current entry
            $entry = new \stdClass();
            
            // Gets the values for the current palette entry (depending of the sample depth)
            $red              = $this->_stream->$entryValueMethod();
            $green            = $this->_stream->$entryValueMethod();
            $blue             = $this->_stream->$entryValueMethod();
            $alpha            = $this->_stream->$entryValueMethod();
            
            // Gets the frequency - Always 2 bytes
            $frequency        = $this->_stream->bigEndianUnsignedShort();
            
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
