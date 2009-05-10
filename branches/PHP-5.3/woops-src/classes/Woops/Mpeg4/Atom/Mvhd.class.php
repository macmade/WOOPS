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
 * MPEG-4 MVHD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class MovieHeaderBox extends FullBox( 'mvhd', version, 0 )
 * {
 *      if( version == 1 ) {
 *          
 *          unsigned int( 64 ) creation_time;
 *          unsigned int( 64 ) modification_time;
 *          unsigned int( 32 ) timescale;
 *          unsigned int( 64 ) duration;
 *          
 *      } else {
 *          
 *          unsigned int( 32 ) creation_time;
 *          unsigned int( 32 ) modification_time;
 *          unsigned int( 32 ) timescale;
 *          unsigned int( 32 ) duration;
 *      }
 *      
 *      template int( 32 ) rate = 0x00010000;
 *      template int( 16 ) volume = 0x0100;
 *      const bit( 16 ) reserved = 0;
 *      const unsigned int( 32 )[ 2 ] reserved = 0;
 *      template int( 32 )[ 9 ] matrix = { 0x00010000,0,0,0,0x00010000,0,0,0,0x40000000 };
 *      bit( 32 )[ 6 ] pre_defined = 0;
 *      unsigned int( 32 ) next_track_ID;
 *  }
 * </code>
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Mvhd extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The atom type
     */
    protected $_type = 'mvhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new \stdClass();
    }
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->modification_time = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->timescale         = $this->_stream->bigEndianUnsignedLong();
            $data->duration          = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->rate              = $this->_stream->bigEndianFixedPoint( 16, 16 );
            $data->volume            = $this->_stream->bigEndianFixedPoint( 8, 8 );
            $this->_stream->seek( 10, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->matrix            = $this->_stream->matrix();
            $this->_stream->seek( 24, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->next_track_ID     = $this->_stream->bigEndianUnsignedLong();
            
        } else {
            
            $data->creation_time     = $this->_stream->bigEndianUnsignedLong();
            $data->modification_time = $this->_stream->bigEndianUnsignedLong();
            $data->timescale         = $this->_stream->bigEndianUnsignedLong();
            $data->duration          = $this->_stream->bigEndianUnsignedLong();
            $data->rate              = $this->_stream->bigEndianFixedPoint( 16, 16 );
            $data->volume            = $this->_stream->bigEndianFixedPoint( 8, 8 );
            $this->_stream->seek( 10, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->matrix            = $this->_stream->matrix();
            $this->_stream->seek( 24, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->next_track_ID     = $this->_stream->bigEndianUnsignedLong();
        }
        
        return $data;
    }
}
