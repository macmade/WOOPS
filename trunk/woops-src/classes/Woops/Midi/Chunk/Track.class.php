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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Midi\Chunk;

/**
 * MIDI track chunk
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi.Chunk
 */
class Track extends \Woops\Midi\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type        = 0x4D54726B;
    
    /**
     * The track events
     */
    protected $_trackEvents = array();
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( \Woops\Midi\Binary\Stream $stream )
    {
        // Calls the parent method
        parent::processData( $stream );
        
        // Current offset
        $offset    = $stream->getOffset();
        
        // Offset for the chunk's end
        $endOffset = $offset + $this->_dataSize;
        
        // Process the chunk's data
        while( $offset < $endOffset )
        {
            // Creates a new track event and stores it
            $trackEvent           = new \Woops\Midi\Track\Event();
            $this->_trackEvents[] = $trackEvent;
            
            // Processes the track event's data
            $trackEvent->processData( $stream );
            
            // Gets the current offset
            $offset = $stream->getOffset();
        }
    }
}
