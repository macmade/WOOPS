<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 PDIN atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class ProgressiveDownloadInfoBox extends FullBox( 'pdin', version = 0, 0 )
 * {
 *      for ( i = 0; ; i++ ) {
 *          
 *          unsigned int( 32 ) rate;
 *          unsigned int( 32 ) initial_delay;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Pdin extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'pdin';
    
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
        $data          = parent::getProcessedData();
        
        // Storage for the entries
        $data->entries = array();
        
        // Process each entry
        for( $i = 4; $i < $this->_dataLength; $i += 8 ) {
            
            // Storage for the current entry
            $entry                = new stdClass();
            
            // Process the current entry
            $entry->rate          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $entry->initial_delay = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 4 );
            
            // Stores the current entry
            $data->entries[]      = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
