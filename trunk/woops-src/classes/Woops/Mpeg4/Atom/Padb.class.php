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
 * MPEG-4 PADB atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class PaddingBitsBox extends FullBox( 'padb', version = 0, 0 )
 * {
 *      unsigned int( 32 ) sample_count;
 *      int i;
 *      
 *      for( i = 0; i < ( ( sample_count + 1 ) / 2 ); i++ ) {
 *          
 *          bit( 1 ) reserved = 0;
 *          bit( 3 ) pad1;
 *          bit( 1 ) reserved = 0;
 *          bit( 3 ) pad2;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Padb extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'padb';
    
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
        $data               = parent::getProcessedData();
        
        // Gets the number of samples
        $data->sample_count = $this->_stream->bigEndianUnsignedLong();
        
        // Storage for the samples
        $data->samples = array();
        
        // Process each priority
        for( $i = 0; $i < ( $data->sample_count + 1 ) / 2; $i++ )
        {
            // Storage for the current entry
            $entry           = new \stdClass();
            
            // Gets the raw data for the entry
            $entryData       = $this->_stream->unsignedChar();
            
            // Process the entry data
            $entry->pad1     = $entryData & 0x70; // Mask is 0111 0000 
            $entry->pad2     = $entryData & 0x07; // Mask is 0000 0111
            
            // Stores the current entry
            $data->samples[] = $entry;
        }
        
        // Return the processed data
        return $data;
    }
}
