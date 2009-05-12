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
namespace Woops\Midi\Track;

/**
 * MIDI track event
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi.Track
 */
class Event extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The MIDI event types
     */
    const EVENT_NOTE_OFF           = 0x08;
    const EVENT_NOTE_ON            = 0x09;
    const EVENT_NOTE_AFTERTOUCH    = 0x0A;
    const EVENT_CONTROLLER         = 0x0B;
    const EVENT_PROGRAM_CHANGE     = 0x0C;
    const EVENT_CHANNEL_AFTERTOUCH = 0x0D;
    const EVENT_PITCH_BEND         = 0x0E;
    const EVENT_META               = 0xFF;
    const EVENT_SYSTEM_EXCLUSIVE   = 0xF0;
    
    /**
     * The MIDI event types, with their corresponding PHP classname
     */
    protected static $_types = array(
        0x08 => 'Woops\Midi\Event\Note\Off',
        0x09 => 'Woops\Midi\Event\Note\On',
        0x0A => 'Woops\Midi\Event\Note\AfterTouch',
        0x0B => 'Woops\Midi\Event\Controller',
        0x0C => 'Woops\Midi\Event\ProgramChange',
        0x0D => 'Woops\Midi\Event\Channel\AfterTouch',
        0x0E => 'Woops\Midi\Event\PitchBend',
        0xFF => 'Woops\Midi\Event\Meta',
        0xF0 => 'Woops\Midi\Event\SystemExclusive'
    );
    
    /**
     * The delta-time
     */
    protected $_deltaTime    = 0;
    
    /**
     * The MIDI event
     */
    protected $_event        = NULL;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( \Woops\Midi\Binary\Stream $stream )
    {
        // Gets the delta-time
        $this->_deltaTime = $stream->variableLengthQuantity();
        
        // Gets the first event byte
        $eventByte        = $stream->unsignedChar();
        
        // Gets the event type
        $eventType        = $eventByte >> 4;
        
        if( $eventType === 0x0F ) {
            
            $eventType = $eventByte;
        }
        
        try {
            
            $event = $this->setEvent( $eventType );
            $event->processData( $stream );
            
        } catch( Event\Exception $e ) {
            
            if( $e->getCode() !== Event\Exception::EXCEPTION_INVALID_EVENT_TYPE ) {
                
                throw $e;
            }
        }
    }
    
    /**
     * 
     */
    public function setEvent( $type )
    {
        $type = ( int )$type;
        
        if( !isset( self::$_types[ $type ] ) ) {
            
            throw new Event\Exception(
                'Invalid MIDI event type (' . $type . ')',
                Event\Exception::EXCEPTION_INVALID_EVENT_TYPE
            );
        }
        
        $eventClass   = self::$_types[ $type ];
        
        $this->_event = new $eventClass();
        
        return $this->_event;
    }
}
