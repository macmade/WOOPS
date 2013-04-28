<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 ELST atom
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Elst extends Woops_Mpeg4_FullBox
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
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data              = parent::getProcessedData();
        $data->entry_count = $this->_stream->bigEndianUnsignedLong();
        $data->entries     = array();
        
        if( $data->version === 1 ) {
            
            while( !$this->_stream->endOfStream() ) {
                
                $entry                   =  new stdClass();
                $entry->segment_duration = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
                $entry->media_time       = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
                $entry->media_rate       = $this->_stream->bigEndianFixedPoint( 16, 16 );
                $data->entries[]         = $entry;
            }
            
        } else {
            
            while( !$this->_stream->endOfStream() ) {
                
                $entry                   =  new stdClass();
                $entry->segment_duration = $this->_stream->bigEndianUnsignedLong();
                $entry->media_time       = $this->_stream->bigEndianUnsignedLong();
                $entry->media_rate       = $this->_stream->bigEndianFixedPoint( 16, 16 );
                $data->entries[]         = $entry;
            }
        }
        
        return $data;
    }
}
