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
 * MPEG-4 ELST atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Elst extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The atom type
     */
    protected $_type = 'elst';
    
    protected function _processFlags( $flags )
    {
        return new \stdClass();
    }
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data              = parent::getProcessedData();
        $data->entry_count = $this->_stream->bigEndianUnsignedLong();
        $data->entries     = array();
        
        if( $data->version === 1 ) {
            
            while( !$this->_stream->endOfStream() ) {
                
                $entry                   =  new \stdClass();
                $entry->segment_duration = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
                $entry->media_time       = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
                $entry->media_rate       = $this->_stream->bigEndianFixedPoint( 16, 16 );
                $data->entries[]         = $entry;
            }
            
        } else {
            
            while( !$this->_stream->endOfStream() ) {
                
                $entry                   =  new \stdClass();
                $entry->segment_duration = $this->_stream->bigEndianUnsignedLong();
                $entry->media_time       = $this->_stream->bigEndianUnsignedLong();
                $entry->media_rate       = $this->_stream->bigEndianFixedPoint( 16, 16 );
                $data->entries[]         = $entry;
            }
        }
        
        return $data;
    }
}
