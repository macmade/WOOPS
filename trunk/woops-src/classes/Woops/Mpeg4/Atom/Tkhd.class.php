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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Mpeg4\Atom;

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
 * @package     Woops.Mpeg4.Atom
 */
final class Tkhd extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
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
        $flags                   = new \stdClass();
        
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
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Gets the processed data from the parent (fullbox)
        $data = parent::getProcessedData();
        
        // Checks the atom version
        if( $data->version === 1 ) {
            
            // Process data
            $data->creation_time     = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->modification_time = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->track_ID          = $this->_stream->bigEndianUnsignedLong();
            $this->_stream->seek( 4, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->duration          = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $this->_stream->seek( 8, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->layer             = $this->_stream->bigEndianUnsignedShort();
            $data->alternate_group   = $this->_stream->bigEndianUnsignedShort();
            $data->volume            = $this->_stream->bigEndianFixedPoint( 8, 8 );
            $this->_stream->seek( 2, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->matrix            = $this->_stream->matrix();
            $data->width             = $this->_stream->bigEndianFixedPoint( 16, 16 );
            $data->height            = $this->_stream->bigEndianFixedPoint( 16, 16 );
            
        } else {
            
            // Process data
            $data->creation_time     = $this->_stream->bigEndianUnsignedLong();
            $data->modification_time = $this->_stream->bigEndianUnsignedLong();
            $data->track_ID          = $this->_stream->bigEndianUnsignedLong();
            $this->_stream->seek( 4, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->duration          = $this->_stream->bigEndianUnsignedLong();
            $this->_stream->seek( 8, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->layer             = $this->_stream->bigEndianUnsignedShort();
            $data->alternate_group   = $this->_stream->bigEndianUnsignedShort();
            $data->volume            = $this->_stream->bigEndianFixedPoint( 8, 8 );
            $this->_stream->seek( 2, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->matrix            = $this->_stream->matrix();
            $data->width             = $this->_stream->bigEndianFixedPoint( 16, 16 );
            $data->height            = $this->_stream->bigEndianFixedPoint( 16, 16 );
        }
        
        // Return the processed data
        return $data;
    }
}
