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
 * MPEG-4 MDHD atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Mdhd extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'mdhd';
    
    protected function _processFlags( $rawFlags )
    {
        return new \stdClass();
    }
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data = parent::getProcessedData();
        
        if( $data->version === 1 )
        {
            $data->creation_time     = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->modification_time = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->timescale         = $this->_stream->bigEndianUnsignedLong();
            $data->duration          = $this->_stream->bigEndianUnsignedLong();
            $data->language          = $this->_stream->bigEndianIso639Code();
        }
        else
        {
            $data->creation_time     = $this->_stream->bigEndianUnsignedLong();
            $data->modification_time = $this->_stream->bigEndianUnsignedLong();
            $data->timescale         = $this->_stream->bigEndianUnsignedLong();
            $data->duration          = $this->_stream->bigEndianUnsignedLong();
            $data->language          = $this->_stream->bigEndianIso639Code();
        }
        
        return $data;
    }
}
