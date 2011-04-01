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
 * PNG hIST chunk (image histogram)
 * 
 * The hIST chunk gives the approximate usage frequency of each colour in the
 * palette. A histogram chunk can appear only when a PLTE chunk appears. If a
 * viewer is unable to provide all the colours listed in the palette,
 * the histogram may help it decide how to choose a subset of the colours
 * for display.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Woops_Png_Chunk_Hist extends Woops_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'hIST';
    
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
        $data            = new stdClass();
        $data->frequency = array();
        
        // Process each frequency
        while( !$this->_stream->endOfStream() ) {
            
            // Adds the current frequency
            $data->frequency[] = $this->_stream->bigEndianUnsignedShort();
        }
        
        // Returns the processed data
        return $data;
    }
}
