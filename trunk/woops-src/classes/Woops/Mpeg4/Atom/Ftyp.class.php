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
 * MPEG-4 FTYP atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Ftyp extends \Woops\Mpeg4\DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'ftyp';
    
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        $data                    = new \stdClass();
        $data->major_brand       = $this->_stream->read( 4 );
        $data->minor_version     = $this->_stream->bigEndianUnsignedLong();
        $data->compatible_brands = array();
        
        while( !$this->_stream->endOfStream() ) {
            
            $data->compatible_brands[] = $this->_stream->read( 4 );
        }
        
        return $data;
    }
}
