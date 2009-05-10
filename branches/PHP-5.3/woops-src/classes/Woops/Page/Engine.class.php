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
namespace Woops\Page;

/**
 * WOOPS page engine class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Page
 */
final class Engine extends \Woops\Core\Event\Dispatcher implements \Woops\Core\Singleton\ObjectInterface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance   = NULL;
    
    /**
     * The page getter object
     */
    private $_pageGetter        = NULL;
    
    /**
     * The active page engine
     */
    private $_pageEngine        = NULL;
    
    /**
     * The registered page engine classes
     */
    private $_pageEngines       = array();
    
    /**
     * The default page engine
     */
    private $_defaultPageEngine = '';
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    private function __construct()
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
        throw new \Woops\Core\Singleton\Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            \Woops\Core\Singleton\Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops\Page\Engine   The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * 
     */
    public function registerPageEngine( $className )
    {
        if( !class_exists( $className ) ) {
            
            throw new Engine\Exception(
                'Cannot register unexisting class \'' . $className . '\' as a page engine',
                Engine\Exception::EXCEPTION_NO_ENGINE_CLASS
            );
        }
        
        if( !is_subclass_of( $className, 'Woops\Page\Engine\Base' ) ) {
            
            throw new Engine\Exception(
                'Cannot register class \'' . $className . '\' as a page engine, since it does not extends the \'Woops\Page\Engine\Base\' abstract class',
                Engine\Exception::EXCEPTION_INVALID_ENGINE_CLASS
            );
        }
        
        // Dispatch the event to the listeners
        $this->dispatchEvent( Engine\Event::EVENT_ENGINE_REGISTER );
        
        $this->_pageEngines[ $className ] = true;
    }
    
    /**
     * 
     */
    public function getPageObject()
    {
        if( !is_object( $this->_pageEngine ) ) {
            
            if( !is_object( $this->_pageGetter ) ) {
                
                $this->_pageGetter = Getter::getInstance();
            }
            
            $engineClass = $this->_pageGetter->getEngine();
            
            if( !isset( $this->_pageEngines[ $engineClass ] ) ) {
                
                throw new Engine\Exception(
                    'The page engine \'' . $engineClass . '\' is not a registered WOOPS page engine',
                    Engine\Exception::EXCEPTION_ENGINE_NOT_REGISTERED
                );
            }
            
            if( !is_subclass_of( $engineClass, 'Woops\Page\Engine\Base' ) ) {
                
                throw new Engine\Exception(
                    'The page engine \'' . $engineClass . '\' is not a valid WOOPS page engine, since it does extends the \'Woops\Page\Engine\Base\' abstract class',
                    Engine\Exception::EXCEPTION_ENGINE_NOT_VALID
                );
            }
            
            $this->_pageEngine = new $engineClass();
            
            $engineOptions     = unserialize( $this->_pageGetter->getEngineOptions() );
            
            if( !is_object( $engineOptions ) ) {
                
                $engineOptions = new \stdClass();
            }
            
            $this->_pageEngine->loadEngine( $engineOptions );
            
            // Dispatch the event to the listeners
            $this->dispatchEventObject( new Engine\Event( Engine\Event::EVENT_ENGINE_LOAD, $this->_pageEngine ) );
        }
        
        return $this->_pageEngine;
    }
}
