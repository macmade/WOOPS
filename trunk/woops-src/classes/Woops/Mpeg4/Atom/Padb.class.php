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
 * MPEG-4 PADB atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class PaddingBitsBox extends FullBox( 'padb', version = 0, 0 )
 * {
 *      unsigned int( 32 ) sample_count;
 *      int i;
 *      
 *      for( i = 0; i < ( ( sample_count + 1 ) / 2 ); i++ ) {
 *          
 *          bit( 1 ) reserved = 0;
 *          bit( 3 ) pad1;
 *          bit( 1 ) reserved = 0;
 *          bit( 3 ) pad2;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Padb extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'padb';
    
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
        $data          = parent::getProcessedData();
            
        // Storage for the entries
        $data->entries = array();
        
        // Checks for the STSZ atom
        if( !isset( $this->_parent->stsz ) ) {
                    
            // Return the processed data
            return $data;
        }
        
        // Gets data from STSZ
        $stsz = $this->_parent->stsz->getProcessedData();
        
        // Process each priority
        for( $i = 4; $i < ( $stsz->entry_count + 1 ) / 2; $i += 2 ) {
            
            // Storage for the current entry
            $entry           = new stdClass();
            
            // Gets the raw data for the entry
            $entryData       = self::$_binUtils->bigEndianUnsignedShort( $this->_data, $i - 1 );
            
            // Process the entry data
            $entry->pad1     = $entryData & 0x0070; // Mask is 0000 0000 0111 0000 
            $entry->pad2     = $entryData & 0x0007; // Mask is 0000 0000 0000 0111
            
            // Stores the current entry
            $data->entries[] = $entry;
        }
        
        // Return the processed data
        return $data;
    }
}
