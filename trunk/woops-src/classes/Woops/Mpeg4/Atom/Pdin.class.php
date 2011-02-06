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
 * MPEG-4 PDIN atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class ProgressiveDownloadInfoBox extends FullBox( 'pdin', version = 0, 0 )
 * {
 *      for ( i = 0; ; i++ ) {
 *          
 *          unsigned int( 32 ) rate;
 *          unsigned int( 32 ) initial_delay;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Pdin extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'pdin';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
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
        $data          = parent::getProcessedData();
        
        // Storage for the entries
        $data->entries = array();
        
        // Process each entry
        while( !$this->_stream->endOfStream() )
        {
            // Storage for the current entry
            $entry                = new \stdClass();
            
            // Process the current entry
            $entry->rate          = $this->_stream->bigEndianUnsignedLong();
            $entry->initial_delay = $this->_stream->bigEndianUnsignedLong();
            
            // Stores the current entry
            $data->entries[]      = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
