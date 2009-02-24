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
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Sbgp extends Woops_File_Mpeg4_FullBox
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
        // Gets the processed data from the parent (fullbox)
        $data                = parent::getProcessedData();
        
        // Process the atom data
        $data->grouping_type = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entry_count   = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
        
        // Storage for the entries
        $data->entries       = array();
        
        // Process each entry
        for( $i = 12; $i < $this->_dataLength; $i += 8 ) {
            
            // Storage for the current entry
            $entry                          = new stdClass();
            
            // Process the entry data
            $entry->sample_count            = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $entry->group_description_index = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 4 );
            
            // Stores the current entry
            $data->entries[]                = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
