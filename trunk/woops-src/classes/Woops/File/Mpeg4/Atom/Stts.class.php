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
 * MPEG-4 STTS atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class TimeToSampleBox extends FullBox( 'stts', version = 0, 0 )
 * {
 *      unsigned int( 32 ) entry_count;
 *      int i;
 *      
 *      for( i = 0; i < entry_count; i++ ) {
 *          
 *          unsigned int( 32 )  sample_count;
 *          unsigned int( 32 )  sample_delta;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Stts extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'stts';
    
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
        for( $i = 8; $i < $this->_dataLength; $i += 8 ) {
            
            // Storage for the current entry
            $entry               = new stdClass();
            
            // Entry data
            $entry->sample_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $entry->sample_delta = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 4 );
            
            // Stores the current entry
            $data->entries[]     = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
