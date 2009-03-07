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

/**
 * MPEG-4 XML atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class XMLBox extends FullBox( 'xml ', version = 0, 0 )
 * {
 *      string xml;
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Xml extends Woops_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'xml ';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
        return new stdClass();
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Gets the processed data from the parent (fullbox)
        $data = parent::getProcessedData();
        
        // Tries the get the byte order mark
        $bom  = $this->_stream->bigEndianUnsignedShort();
        
        // Checks for the byte order mark
        if( ( $bom & 0xFEFF ) === $bom ) {
            
            // UTF-16 XML
            $data->xml = substr( $this->_stream->getRemainingData(), 0, -1 );
            
        } else {
            
            // UTF-8 XML
            $this->_stream->seek( -2, Woops_Mpeg4_Binary_Stream::SEEK_CUR );
            $data->xml = substr( $this->_stream->getRemainingData(), 0, -1 );
        }
        
        // Return the processed data
        return $data;
    }
}
