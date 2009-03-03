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
 * MPEG-4 STCO atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class ChunkOffsetBox extends FullBox( 'stco', version = 0, 0 )
 * {
 *      unsigned int( 32 ) entry_count;
 *      
 *      for ( i=1; i <= entry_count; i++ ) {
 *          
 *          unsigned int( 32 )  chunk_offset;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Stco extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'stco';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $flags )
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
        $data              = parent::getProcessedData();
        
        // Number of entries
        $data->entry_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        
        // Storage for the entries
        $data->entries     = array();
        
        // Process each entry
        for( $i = 8; $i < $this->_dataLength; $i += 4 ) {
            
            // Storage for the current entry
            $entry               = new stdClass();
            
            // Process the entry data
            $entry->chunk_offset = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            
            // Stores the current entry
            $data->entries[]     = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
