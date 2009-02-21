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
 * MPEG-4 STSZ atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Stsz extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'stsz';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->sample_size = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entry_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
        $data->entries     = array();
        
        for( $i = 16; $i < $this->_dataLength; $i += 4 ) {
            
            $entry             = new stdClass();
            $entry->entry_size = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $data->entries[]   = $entry;
        }
        
        return $data;
    }
}
