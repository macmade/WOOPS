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
 * PNG tIMe chunk (image last-modification time)
 * 
 * The tIME chunk gives the time of the last image modification (not the time
 * of initial image creation).
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png.Chunk
 */
class Time extends \Woops\Png\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The chunk type
     */
    protected $_type = 'tIMe';
    
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
        $data         = new \stdClass();
        
        // Gets the date informations
        $data->year   = $this->_stream->bigEndianUnsignedShort();
        $data->month  = $this->_stream->unsignedChar();
        $data->day    = $this->_stream->unsignedChar();
        $data->hour   = $this->_stream->unsignedChar();
        $data->minute = $this->_stream->unsignedChar();
        $data->second = $this->_stream->unsignedChar();
        
        // Creates a timestamp
        $data->tstamp = mktime
        (
            $data->hour,
            $data->minute,
            $data->second,
            $data->month,
            $data->day,
            $data->year
        );
        
        // Returns the processed data
        return $data;
    }
}
