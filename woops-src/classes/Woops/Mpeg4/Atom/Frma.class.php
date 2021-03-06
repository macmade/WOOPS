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
 * MPEG-4 FRMA atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class OriginalFormatBox( codingname ) extends Box ( 'frma' )
 * {
 *      unsigned int( 32 ) data_format = codingname;
 * }
 * </code>
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Frma extends Woops_Mpeg4_DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'frma';
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Data storage
        $data = new stdClass();
        
        // Data format
        $data->data_format = $this->_stream->read( 4 );
        
        // Returns the processed data
        return $data;
    }
}
