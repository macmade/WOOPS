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
 * MPEG-4 HDLR atom
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Hdlr extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'hdlr';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data               = parent::getProcessedData();
        $this->_stream->seek( 4, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        $data->handler_type = $this->_stream->read( 4 );
        $this->_stream->seek( 12, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
        $data->name         = substr( $this->_stream->getRemainingData(), 0, -1 );
        
        return $data;
    }
}
