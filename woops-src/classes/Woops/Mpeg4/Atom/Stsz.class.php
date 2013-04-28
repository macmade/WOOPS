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
 * MPEG-4 STSZ atom
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Stsz extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'stsz';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data              = parent::getProcessedData();
        $data->sample_size = $this->_stream->bigEndianUnsignedLong();
        $data->entry_count = $this->_stream->bigEndianUnsignedLong();
        $data->entries     = array();
        
        if( $data->sample_size === 0 ) {
            
            while( !$this->_stream->endOfStream() ) {
                
                $entry             = new stdClass();
                $entry->entry_size = $this->_stream->bigEndianUnsignedLong();
                $data->entries[]   = $entry;
            }
        }
        
        return $data;
    }
}
