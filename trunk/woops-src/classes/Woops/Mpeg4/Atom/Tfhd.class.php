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
namespace Woops\Mpeg4\Atom;

/**
 * MPEG-4 TFHD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class TrackFragmentHeaderBox extends FullBox( 'tfhd', 0, tf_flags )
 * {
 *      unsigned int( 32 ) track_ID;
 *      
 *      // All the following are optional fields
 *      unsigned int( 64 ) base_data_offset;
 *      unsigned int( 32 ) sample_description_index;
 *      unsigned int( 32 ) default_sample_duration;
 *      unsigned int( 32 ) default_sample_size;
 *      unsigned int( 32 ) default_sample_flags
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Tfhd extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'tfhd';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Storage for the atom flags
        $flags                                   = new \stdClass();
        
        // Process the atom flags
        $flags->base_data_offset_present         = ( $rawFlags & 0x000001 ) ? true: false;
        $flags->sample_description_index_present = ( $rawFlags & 0x000002 ) ? true: false;
        $flags->default_sample_duration_present  = ( $rawFlags & 0x000008 ) ? true: false;
        $flags->default_sample_size_present      = ( $rawFlags & 0x000010 ) ? true: false;
        $flags->default_sample_flags_present     = ( $rawFlags & 0x000020 ) ? true: false;
        $flags->duration_is_empty                = ( $rawFlags & 0x010000 ) ? true: false;
        
        // Returns the atom flags
        return $flags;
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Gets the processed data from the parent (fullbox)
        $data           = parent::getProcessedData();
        
        // Track ID
        $data->track_ID = $this->_stream->bigEndianUnsignedLong();
        
        // Checks for the base data offset
        if( $data->flags->base_data_offset_present )
        {
            // Base data offset
            $data->base_data_offset = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
        }
        
        // Checks for the sample description index
        if( $data->flags->sample_description_index_present )
        {
            // Sample description index
            $data->sample_description_index = $this->_stream->bigEndianUnsignedLong();
        }
        
        // Checks for the default sample duration
        if( $data->flags->default_sample_duration_present )
        {
            // Default sample duration
            $data->default_sample_duration = $this->_stream->bigEndianUnsignedLong();
        }
        
        if( $data->flags->default_sample_size_present )
        {
            $data->default_sample_size = $this->_stream->bigEndianUnsignedLong();
        }
        
        if( $data->flags->default_sample_flags_present )
        {
            $data->default_sample_flags = $this->_stream->bigEndianUnsignedLong();
        }
        
        // Return the processed data
        return $data;
    }
}
