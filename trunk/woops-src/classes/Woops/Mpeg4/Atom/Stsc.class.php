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
 * MPEG-4 STSC atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Stsc extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'stsc';
    
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
        
        while( !$this->_stream->endOfStream() )
        {
            $entry                           = new \stdClass();
            $entry->first_chunk              = $this->_stream->bigEndianUnsignedLong();
            $entry->samples_per_chunk        = $this->_stream->bigEndianUnsignedLong();
            $entry->sample_description_index = $this->_stream->bigEndianUnsignedLong();
            $data->entries[]                 = $entry;
        }
        
        return $data;
    }
}
