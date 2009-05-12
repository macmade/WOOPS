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

# $Id: Interface.class.php 434 2009-02-24 15:19:13Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Core\Singleton;

/**
 * Base class for the singleton classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Singleton
 */
abstract class Base extends \Woops\Core\Event\Dispatcher implements ObjectInterface
{
    /**
     * The singelton instances
     */
    private static $_instances = array();
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return void
     */
    protected function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops\Core\Singleton\Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Exception(
            'Class ' . get_class( $this ) . ' cannot be cloned',
            Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops\Core\Singleton\ObjectInterface    The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Gets the name of the class for which to get the unique instance
        $class = get_called_class();
        
        // Checks if the unique instance has already been created
        if( !isset( self::$_instances[ $class ] ) ) {
            
            // Creates the unique instance
            self::$_instances[ $class ] = new $class();
            
            // Dispatch the event to the listeners
            self::$_instances[ $class ]->dispatchEventObject(
                new Event(
                    Event::EVENT_CONSTRUCT,
                    self::$_instances[ $class ]
                )
            );
        }
        
        // Returns the unique instance
        return self::$_instances[ $class ];
    }
}
