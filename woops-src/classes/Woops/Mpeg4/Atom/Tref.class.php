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
 * MPEG-4 TREF atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class TrackReferenceBox extends Box( 'tref' )
 * {}
 * 
 * aligned( 8 ) class TrackReferenceTypeBox ( unsigned int( 32 ) reference_type ) extends Box( reference_type )
 * { 
 *      unsigned int( 32 ) track_IDs[]; 
 * }
 * </code>
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Tref extends Woops_Mpeg4_DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'tref';
    
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
        $data          = new stdClass();
        $data->entries = array();
        
        // Process each entry
        while( !$this->_stream->endOfStream() ) {
            
            // Length of the current entry
            $entryLength           = $this->_stream->bigEndianUnsignedLong();
            
            // Storage for the current entry
            $entry                 = new stdClass();
            
            // Reference type
            $entry->reference_type = $this->_stream->read( 4 );
            
            // Storage for the track IDs
            $entry->track_IDs      = array();
            
            // Process each track ID
            for( $i = 8; $i < $entryLength; $i +=4 ) {
                
                // Gets the track ID
                $entry->track_IDs[] = $this->_stream->bigEndianUnsignedLong();
            }
            
            // Stores the current entry
            $data->entries[] = $entry;
        }
        
        // Returns the processed data
        return $data;
    }
}
