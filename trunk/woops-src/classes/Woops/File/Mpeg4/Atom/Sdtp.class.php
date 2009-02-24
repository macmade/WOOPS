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
 * MPEG-4 TKHD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class SampleDependencyTypeBox extends FullBox( 'sdtp', version = 0, 0 )
 * { 
 *      for ( i = 0; i < sample_count; i++ ) { 
 *          
 *          unsigned int( 2 ) reserved = 0; 
 *          unsigned int( 2 ) sample_depends_on; 
 *          unsigned int( 2 ) sample_is_depended_on; 
 *          unsigned int( 2 ) sample_has_redundancy; 
 *      } 
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Sdtp extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'sdtp';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
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
        $data = parent::getProcessedData();
            
        // Storage for the entries
        $data->entries = array();
        
        // Checks for the STSZ atom
        if( !isset( $this->_parent->stsz ) ) {
                    
            // Return the processed data
            return $data;
        }
        
        // Gets data from STSZ
        $stsz = $this->_parent->stsz->getProcessedData();
        
        // Process each sample
        for( $i = 0; $i < $stsz->entry_count; $i++ ) {
            
            // Checks if we are reading the first entry
            if( $i === 0 ) {
                
                // Gets the raw data for the current entry
                $entryData = ( self::$_binUtils->bigEndianUnsignedShort( $this->_data, $i ) & 0xFF00 ) >> 8;
                
            } else {
                
                // Gets the raw data for the current entry
                $entryData = self::$_binUtils->bigEndianUnsignedShort( $this->_data, $i - 1 ) & 0x00FF;
            }
            
            // Storage for the current sample
            $entry = new stdClass();
            
            // Process the data for the current entry
            $entry->sample_depends_on     = ( $entryData & 0x30 ) >> 4; // Mask is 0011 0000
            $entry->sample_is_depended_on = ( $entryData & 0x0C ) >> 2; // Mask is 0000 1100
            $entry->sample_has_redundancy = $entryData & 0x03;          // Mask is 0000 0011
            
            // Stores the current entry
            $data->entries[] = $entry;
        }
        
        // Return the processed data
        return $data;
    }
}
