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
 * MPEG-4 VMHD atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class VideoMediaHeaderBox extends FullBox( 'vmhd', version = 0, 1 )
 * {
 *      template unsigned int( 16 ) graphicsmode = 0;
 *      template unsigned int( 16 )[ 3 ] opcolor = { 0, 0, 0 };
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Vmhd extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'vmhd';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
        return new \stdClass();
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
        
        $data->graphicsmode   = $this->_stream->bigEndianUnsignedShort();
        $data->opcolor        = new \stdClass();
        $data->opcolor->red   = $this->_stream->bigEndianUnsignedShort();
        $data->opcolor->green = $this->_stream->bigEndianUnsignedShort();
        $data->opcolor->blue  = $this->_stream->bigEndianUnsignedShort();
        
        // Return the processed data
        return $data;
    }
}
