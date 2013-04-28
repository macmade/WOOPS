<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 SUBS atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class SubSampleInformationBox extends FullBox( 'subs', version, 0 )
 * {
 *      unsigned int( 32 ) entry_count;
 *      int i, j;
 *      
 *      for( i = 0; i < entry_count; i++ ) {
 *          
 *          unsigned int( 32 ) sample_delta;
 *          unsigned int( 16 ) subsample_count;
 *          
 *          if( subsample_count > 0 ) {
 *              
 *              for( j = 0; j < subsample_count; j++ ) {
 *                  
 *                  if( version == 1 ) {
 *                      
 *                      unsigned int( 32 ) subsample_size;
 *                      
 *                  } else {
 *                      
 *                      unsigned int( 16 ) subsample_size;
 *                  }
 *                  
 *                  unsigned int( 8 ) subsample_priority;
 *                  unsigned int( 8 ) discardable;
 *                  unsigned int( 32 ) reserved = 0;
 *              }
 *          }
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Subs extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'subs';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        return new stdClass();
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
        $data              = parent::getProcessedData();
        
        // Number of entries
        $data->entry_count = $this->_stream->bigEndianUnsignedLong();
        
        // Storage for the entries
        $data->entries     = array();
        
        // Process each entry
        for( $i = 0; $i < $data->entry_count; $i++ ) {
            
            // Storage for the current entry
            $entry                  = new stdClass();
            
            // Process the data for the current entry
            $entry->sample_delta    = $this->_stream->bigEndianUnsignedLong();
            $entry->subsample_count = $this->_stream->bigEndianUnsignedShort();
            $entry->subsamples      = array();
            
            // Checks for subsamples
            if( $entry->subsample_count > 0 ) {
                
                // Process each subsample
                for( $j = 0; $j < $subsample_count; $j++ ) {
                    
                    // Storage for the current subsample
                    $subSample = new stdClass();
                    
                    // Checks the atom version
                    if( $data->version === 1 ) {
                        
                        // Size of the subsample
                        $subSample->subsample_size = $this->_stream->bigEndianUnsignedLong();
                        
                    } else {
                        
                        // Size of the subsample
                        $subSample->subsample_size = $this->_stream->bigEndianUnsignedShort();
                    }
                    
                    // Remaining subsample data
                    $subSampleData                 = $this->_stream->bigEndianUnsignedShort();
                    
                    // Process the remaining data
                    $subSample->subsample_priority = $subSampleData > 8;        // 8 first bits
                    $subSample->discardable        = $subSampleData & 0x00FF;   // 8 last bits
                    
                    // Stores the current subsample
                    $entry->subsamples[]           = $subSample;
                }
            }
            
            // Stores the current entry
            $data->entries[] = $entry;
        }
        
        // Return the processed data
        return $data;
    }
}
