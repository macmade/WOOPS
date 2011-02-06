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
 * MPEG-4 STTS atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class TimeToSampleBox extends FullBox( 'stts', version = 0, 0 )
 * {
 *      unsigned int( 32 ) entry_count;
 *      int i;
 *      
 *      for( i = 0; i < entry_count; i++ ) {
 *          
 *          unsigned int( 32 )  sample_count;
 *          unsigned int( 32 )  sample_delta;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Stts extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'stts';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $flags )
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
        $data              = parent::getProcessedData();
        
        // Number of entries
        $data->entry_count = $this->_stream->bigEndianUnsignedLong();
        
        // Storage for the entries
        $data->entries     = array();
        
        // Process each entry
        while( !$this->_stream->endOfStream() )
        {
            // Storage for the current entry
            $entry               = new \stdClass();
            
            // Entry data
            $entry->sample_count = $this->_stream->bigEndianUnsignedLong();
            $entry->sample_delta = $this->_stream->bigEndianUnsignedLong();
            
            // Stores the current entry
            $data->entries[]     = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
