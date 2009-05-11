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
 * MPEG-4 TRUN atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class TrackRunBox extends FullBox( 'trun', 0, tr_flags )
 * {
 *      unsigned int( 32 ) sample_count;
 *      
 *      // The following are optional fields
 *      signed int( 32 ) data_offset;
 *      unsigned int( 32 ) first_sample_flags;
 *      
 *      // All fields in the following array are optional
 *      {
 *          unsigned int( 32 ) sample_duration;
 *          unsigned int( 32 ) sample_size;
 *          unsigned int( 32 ) sample_flags
 *          unsigned int( 32 ) sample_composition_time_offset;
 *      }[ sample_count ]
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Trun extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'trun';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Storage for the atom flags
        $flags                                          = new stdClass();
        
        // Process the atom flags
        $flags->data_offset_present                     = ( $rawFlags & 0x000001 ) ? true: false;
        $flags->first_sample_flags_present              = ( $rawFlags & 0x000004 ) ? true: false;
        $flags->sample_duration_present                 = ( $rawFlags & 0x000100 ) ? true: false;
        $flags->sample_size_present                     = ( $rawFlags & 0x000200 ) ? true: false;
        $flags->sample_flags_present                    = ( $rawFlags & 0x000400 ) ? true: false;
        $flags->sample_composition_time_offsets_present = ( $rawFlags & 0x000800 ) ? true: false;
        
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
        $data               = parent::getProcessedData();
        
        // Sample count
        $data->sample_count = $this->_stream->bigEndianUnsignedLong();
        
        // Storage for the samples
        $data->samples      = array();
        
        // Checks for the data offset
        if( $data->flags->data_offset_present ) {
            
            // Data offset
            $data->data_offset = $this->_stream->bigEndianUnsignedLong();
        }
        
        // Checks for the first sample flags
        if( $data->flags->first_sample_flags_present ) {
            
            // First sample flags
            $data->first_sample_flags = $this->_stream->bigEndianUnsignedLong();
        }
        
        // Process each sample
        for( $i = 0; $i < $data->sample_count; $i++ ) {
            
            // Storage for the current sample
            $sample = new stdClass();
            
            // Checks for the sample duration
            if( $data->flags->sample_duration_present ) {
                
                // Sample duration
                $sample->sample_duration = $this->_stream->bigEndianUnsignedLong();
            }
            
            // Checks for the sample size
            if( $data->flags->sample_size_present ) {
                
                // Sample size
                $sample->sample_size = $this->_stream->bigEndianUnsignedLong();
            }
            
            // Checks for the sample flags
            if( $data->flags->sample_flags_present ) {
                
                // Sample flags
                $sample->sample_flags = $this->_stream->bigEndianUnsignedLong();
            }
            
            // Checks for the sample composition tome offset
            if( $data->flags->sample_composition_time_offsets_present ) {
                
                // Sample composition tome offset
                $sample->sample_composition_time_offsets = $this->_stream->bigEndianUnsignedLong();
            }
            
            // Stores the current sample
            $data->samples[] = $sample;
        }
        
        // Return the processed data
        return $data;
    }
}
