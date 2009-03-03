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
 * MPEG-4 STSC atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Stsc extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'stsc';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->entry_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entries     = array();
        
        for( $i = 8; $i < $this->_dataLength; $i += 12 ) {
            
            $entry                           = new stdClass();
            $entry->first_chunk              = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
            $entry->samples_per_chunk        = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 4 );
            $entry->sample_description_index = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 8 );
            $data->entries[]                 = $entry;
        }
        
        return $data;
    }
}
