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
 * MPEG-4 FTYP atom
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Ftyp extends Woops_Mpeg4_DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'ftyp';
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data                    = new stdClass();
        $data->major_brand       = $this->_stream->read( 4 );
        $data->minor_version     = $this->_stream->bigEndianUnsignedLong();
        $data->compatible_brands = array();
        
        while( !$this->_stream->endOfStream() ) {
            
            $data->compatible_brands[] = $this->_stream->read( 4 );
        }
        
        return $data;
    }
}
