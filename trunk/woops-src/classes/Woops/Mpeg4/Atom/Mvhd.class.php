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
 * MPEG-4 MVHD atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Mvhd extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'mvhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 ); // Value is 64bits!!!
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 ); // Value is 64bits!!!
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 20 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 24 ); // Value is 64bits!!!
            $data->rate              = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 32 );
            $data->volume            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 8, 8, 36 );
            $data->matrix            = $this->_decodeMatrix( 48 );
            $data->next_track_ID     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 108 );
            
        } else {
            
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
            $data->timescale         = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 16 );
            $data->rate              = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 20 );
            $data->volume            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 8, 8, 24 );
            $data->matrix            = $this->_decodeMatrix( 36 );
            $data->next_track_ID     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 96 );
        }
        
        return $data;
    }
}
