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
 * MPEG-4 SBGP atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class SampleToGroupBox extends FullBox( 'sbgp', version = 0, 0 )
 * {
 *      unsigned int( 32 ) grouping_type;
 *      unsigned int( 32 ) entry_count;
 *      
 *      for( i = 1; i <= entry_count; i++ ) {
 *          
 *          unsigned int( 32 ) sample_count;
 *          unsigned int( 32 ) group_description_index;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Sbgp extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'sbgp';
    
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
        $data                = parent::getProcessedData();
        
        // Process the atom data
        $data->grouping_type = $this->_stream->bigEndianUnsignedLong();
        $data->entry_count   = $this->_stream->bigEndianUnsignedLong();
        
        // Storage for the entries
        $data->entries       = array();
        
        // Process each entry
        while( !$this->_stream->endOfStream() ) {
            
            // Storage for the current entry
            $entry                          = new stdClass();
            
            // Process the entry data
            $entry->sample_count            = $this->_stream->bigEndianUnsignedLong();
            $entry->group_description_index = $this->_stream->bigEndianUnsignedLong();
            
            // Stores the current entry
            $data->entries[]                = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
