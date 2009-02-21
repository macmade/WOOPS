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
 * MPEG-4 ELST atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Elst extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'elst';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data              = parent::getProcessedData();
        $data->entry_count = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->entries     = array();
        
        if( $data->version === 1 ) {
            
            for( $i = 8; $i < $this->_dataLength; $i += 20 ) {
                
                $entry                   =  new stdClass();
                $entry->segment_duration = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
                $entry->media_time       = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 8 );
                $entry->media_rate       = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, $i + 16 );
                $data->entries[]         = $entry;
            }
            
        } else {
            
            for( $i = 8; $i < $this->_dataLength; $i += 12 ) {
                
                $entry                   =  new stdClass();
                $entry->segment_duration = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i );
                $entry->media_time       = self::$_binUtils->bigEndianUnsignedLong( $this->_data, $i + 4 );
                $entry->media_rate       = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, $i + 8 );
                $data->entries[]         = $entry;
            }
        }
        
        return $data;
    }
}
