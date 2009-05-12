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
namespace Woops\Core\Event;

/**
 * Abstract for all classes that dispatch events
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Event
 */
abstract class Dispatcher extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The object's event listeners
     */
    private $_listeners = array();
    
    /**
     * 
     */
    public function dispatchEvent( $type )
    {
        // Name of the specific event class, for the current object
        $eventClass = get_class( $this ) . '\Event';
        
        // Checks if a specific event class exists
        if( !class_exists( '\\' . $eventClass ) ) {
            
            // Generic event
            $eventClass = 'Woops\Core\Event';
        }
        
        // Dispatch the event object
        $this->dispatchEventObject( new $eventClass( $type, $this ) );
    }
    
    /**
     * 
     */
    public function dispatchEventObject( \Woops\Core\Event $event )
    {
        // Gets the event type
        $type = $event->getType();
        
        // Process each registered event type
        foreach( $this->_listeners as $eventType => $priorities ) {
            
            // Checks the event type
            if( $type & $eventType ) {
                
                // Process each priority
                foreach( $priorities as $priority => $listeners ) {
                    
                    // Process each listener
                    foreach( $listeners as $listener ) {
                        
                        // Invokes the listener
                        $listener->invoke( array( $event ) );
                        
                        // Checks if we have to stop the event propagation
                        if( !$event->isPropagating() ) {
                            
                            // Do not invokes the remaining listeners
                            return;
                        }
                    }
                }
            }
        }
    }
    
    /**
     * 
     */
    public function addEventListener( $eventType, $callback, $priority = 0 )
    {
        // Creates the callback for the event listener
        $listener   = new \Woops\Core\Callback( $callback );
        $listenerId = $listener->getObjectHash();
        
        // Ensures we have integers
        $eventType  = ( int )$eventType;
        $priority   = ( int )$priority;
        
        // Checks if the event type exists
        if( !isset( $this->_listeners[ $eventType ] ) ) {
            
            // Creates the storage array for the requested event
            $this->_listeners[ $eventType ] = array();
        }
        
        // Checks if the priority exists
        if( !isset( $this->_listeners[ $eventType ][ $priority ] ) ) {
            
            // Creates the storage array for the requested priority
            $this->_listeners[ $eventType ][ $priority ] = array();
            
            // Sorts the priorities
            krsort( $this->_listeners[ $eventType ] );
        }
        
        // Stores the callback for the event listener
        $this->_listeners[ $eventType ][ $priority ][ $listenerId ] = $listener;
        
        // Returns the listener ID
        return $listenerId;
    }
    
    /**
     * 
     */
    public function hasEventListener( $eventType )
    {
        return ( boolean )( isset( $this->_listeners[ ( int )$eventType ] ) && count( $this->_listeners[ ( int )$eventType ] ) );
    }
    
    /**
     * 
     */
    public function removeEventListener( $eventType, $listenerId )
    {
        // Ensures we have an integer
        $eventType = ( int )$eventType;
        
        // Checks if the event type exists
        if( isset( $this->_listeners[ $eventType ] ) ) {
            
            // Process each priority
            foreach( $this->_listeners[ $eventType ] as $priority => &$listeners ) {
                
                // Removes the listener
                unset( $listeners[ $listenerId ] );
                
                // Checks if we still have event listeners in the current priority
                if( !count( $this->_listeners[ $eventType ][ $priority ] ) ) {
                    
                    // No more event listeners, removes the storage array
                    unset( $this->_listeners[ $eventType ][ $priority ] );
                }
            }
            
            // Checks if we still have event listeners
            if( !count( $this->_listeners[ $eventType ] ) ) {
                
                // No more event listeners, removes the storage array
                unset( $this->_listeners[ $eventType ] );
            }
        }
    }
}
