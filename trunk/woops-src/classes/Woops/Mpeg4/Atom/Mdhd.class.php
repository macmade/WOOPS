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
 * MPEG-4 MDHD atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Mdhd extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'mdhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 20 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 24 );
            $data->language          = self::$_binUtils->bigEndianIso639Code( $this->_data, 32 );
            
        } else {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 16 );
            $data->language          = self::$_binUtils->bigEndianIso639Code( $this->_data, 20 );
        }
        
        return $data;
    }
}
