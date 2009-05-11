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
namespace Woops\Mpeg4;

/**
 * MPEG-4 fullbox atom abstract
 * 
 * This abstract class is the base class for all MPEG-4 atom classes based on the fullbox model.
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class FullBox( unsigned int( 32 ) boxtype, unsigned int( 8 ) v, bit( 24 ) f ) extends Box( boxtype )
 * { 
 *      unsigned int( 8 ) version = v;
 *      bit( 24 ) flags = f;
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4
 */
abstract class FullBox extends DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Process the atom flags
     * 
     * @param   int     The raw (unprocessed) flags
     * @return  void
     */
    abstract protected function _processFlags( $rawFlags );
    
    /**
     * Process the atom data
     * 
     * This method will only take care of the first 32 bits of the atom data.
     * 8 first bits are for the atom version, and the last 24 ones are for the
     * atom class. Child classes must call this method (parent::getProcessedData)
     * in order to gets the version and the flags. The remaining data has to be
     * processed by the child class
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Storage for the atom data
        $data          = new \stdClass();
        
        // Gets the first 32 bits from the atom data
        $unpackData    = $this->_stream->bigEndianUnsignedLong();
        
        // Atom version - 8 first bits - Used to know how to handle the data
        $data->version = $unpackData & 0xFF000000;
        
        // Atom flags - 24 last bits
        $flags         = $unpackData & 0x00FFFFFF;
        
        // Process te atom flags - The method is called from the child class
        $data->flags   = $this->_processFlags( $flags );
        
        // Return the data object
        return $data;
    }
}
