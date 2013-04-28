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
 * MPEG-4 MDHD atom
 *
 * @author      Jean-David Gadina - www.xs-labs.com
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
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data = parent::getProcessedData();
        
        if( $data->version === 1 ) {
            
            $data->creation_time     = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->modification_time = ( $this->_stream->bigEndianUnsignedLong() << 32 ) | $this->_stream->bigEndianUnsignedLong(); // Value is 64bits - Will this work on all platforms?
            $data->timescale         = $this->_stream->bigEndianUnsignedLong();
            $data->duration          = $this->_stream->bigEndianUnsignedLong();
            $data->language          = $this->_stream->bigEndianIso639Code();
            
        } else {
            
            $data->creation_time     = $this->_stream->bigEndianUnsignedLong();
            $data->modification_time = $this->_stream->bigEndianUnsignedLong();
            $data->timescale         = $this->_stream->bigEndianUnsignedLong();
            $data->duration          = $this->_stream->bigEndianUnsignedLong();
            $data->language          = $this->_stream->bigEndianIso639Code();
        }
        
        return $data;
    }
}
