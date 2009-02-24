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
 * aligned( 8 ) class TrackHeaderBox extends FullBox( 'tkhd', version, flags )
 * { 
 *      if( version == 1 ) {
 *          
 *          unsigned int( 64 ) creation_time;
 *          unsigned int( 64 ) modification_time;
 *          unsigned int( 32 ) track_ID;
 *          const unsigned int( 32 ) reserved = 0;
 *          unsigned int( 64 ) duration;
 *          
 *      } else { // version == 0
 *          
 *          unsigned int( 32 ) creation_time;
 *          unsigned int( 32 ) modification_time;
 *          unsigned int( 32 ) track_ID;
 *          const unsigned int( 32 ) reserved = 0;
 *          unsigned int( 32 ) duration;
 *      }
 *      
 *      const unsigned int( 32 )[ 2 ] reserved = 0;
 *      template int( 16 ) layer = 0;
 *      template int( 16 ) alternate_group = 0;
 *      template int( 16 ) volume = { if track_is_audio 0x0100 else 0 };
 *      const unsigned int( 16 ) reserved = 0;
 *      template int( 32 )[ 9 ] matrix = { 0x00010000, 0, 0, 0, 0x00010000, 0, 0, 0, 0x40000000 };
 *      unsigned int( 32 ) width;
 *      unsigned int( 32 ) height;
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Tkhd extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'tkhd';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Storage for the atom flags
        $flags                   = new stdClass();
        
        // Process the atom flags
        $flags->track_enabled    = ( $rawFlags & 0x000001 ) ? true: false;
        $flags->track_in_movie   = ( $rawFlags & 0x000002 ) ? true: false;
        $flags->track_in_preview = ( $rawFlags & 0x000004 ) ? true: false;
        
        // Returns the atom flags
        return $flags;
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
        
        // Checks the atom version
        if( $data->version === 1 ) {
            
            // Process data
            #$data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 ); // Value is 64bits!!!
            #$data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 ); // Value is 64bits!!!
            $data->track_ID          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 20 );
            #$data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 28 ); // Value is 64bits!!!
            $data->layer             = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 44 );
            $data->alternate_group   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 46 );
            $data->volume            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 8, 8, 48 );
            $data->matrix            = $this->_decodeMatrix( 52 );
            $data->width             = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 88 );
            $data->height            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 92 );
            
        } else {
            
            // Process data
            $data->creation_time     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
            $data->modification_time = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
            $data->track_ID          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
            $data->duration          = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 20 );
            $data->layer             = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 32 );
            $data->alternate_group   = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 34 );
            $data->volume            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 8, 8, 36 );
            $data->matrix            = $this->_decodeMatrix( 40 );
            $data->width             = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 76 );
            $data->height            = self::$_binUtils->bigEndianFixedPoint( $this->_data, 16, 16, 80 );
        }
        
        // Return the processed data
        return $data;
    }
}
