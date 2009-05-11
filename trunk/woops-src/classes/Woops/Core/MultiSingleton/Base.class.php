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
namespace Woops\Core\MultiSingleton;

/**
 * Base class for the multi-singleton classes
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
     * The instance name
     */
    private $_instanceName     = '';
    
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
     * @throws  Woops\Core\MultiSingleton\Exception  Always, as the class cannot be cloned (singleton)
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
     * @param   string                                       The instance name
     * @return  Woops\Core\MultiSingleton\ObjectInterface    The unique instance of the class
     * @see     __construct
     */
    public static function getInstance( $instanceName )
    {
        // Ensures we have a string
        $instanceName = ( string )$instanceName;
        
        // Gets the name of the class for which to get the unique instance
        $class = get_called_class();
        
        // Checks if the unique instance has already been created
        if( !isset( self::$_instances[ $class ][ $instanceName ] ) ) {
            
            // Checks if the instances array exists for the called class
            if( !isset( self::$_instances[ $class ] ) ) {
                
                // Creates the class instances array
                self::$_instances[ $class ] = array();
            }
            
            // Creates the unique instance
            self::$_instances[ $class ][ $instanceName ]                = new $class();
            self::$_instances[ $class ][ $instanceName ]->_instanceName = $instanceName;
            
            // Dispatch the event to the listeners
            self::$_instances[ $class ][ $instanceName ]->dispatchEventObject(
                new Event(
                    Event::EVENT_CONSTRUCT,
                    self::$_instances[ $class ][ $instanceName ]
                )
            );
        }
        
        // Returns the unique instance
        return self::$_instances[ $class ][ $instanceName ];
    }
    
    /**
     * Gets the number of instances for a class
     * 
     * @param   string  The name of the class
     * @return  int     The number of instances
     */
    public static function getNumberOfInstances( $class )
    {
        // Ensures we have a string
        $class = ( string )$class;
        
        // Returns the number of instances
        return ( isset( self::$_instances[ $class ] ) ) ? count( self::$_instances[ $class ] ) : 0;
    }
    
    /**
     * Gets the instance name
     * 
     * @return  string  The instance name
     */
    public function getInstanceName()
    {
        return $this->_instanceName;
    }
}
