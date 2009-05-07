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

/**
 * WOOPS page engine class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Page
 */
final class Woops_Page_Engine extends Woops_Core_Object implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Page_Engine   The unique instance of the class
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
            
            throw new Woops_Page_Engine_Exception(
                'Cannot register unexisting class \'' . $className . '\' as a page engine',
                Woops_Page_Engine_Exception::EXCEPTION_NO_ENGINE_CLASS
            );
        }
        
        if( !is_subclass_of( $className, 'Woops_Page_Engine_Base' ) ) {
            
            throw new Woops_Page_Engine_Exception(
                'Cannot register class \'' . $className . '\' as a page engine, since it does not extends the \'Woops_Page_Engine_Base\' abstract class',
                Woops_Page_Engine_Exception::EXCEPTION_INVALID_ENGINE_CLASS
            );
        }
        
        $this->_pageEngines[ $className ] = true;
    }
    
    /**
     * 
     */
    public function getPageObject()
    {
        if( !is_object( $this->_pageEngine ) ) {
            
            if( !is_object( $this->_pageGetter ) ) {
                
                $this->_pageGetter = Woops_Page_Getter::getInstance();
            }
            
            $engineClass = $this->_pageGetter->getEngine();
            
            if( !isset( $this->_pageEngines[ $engineClass ] ) ) {
                
                throw new Woops_Page_Engine_Exception(
                    'The page engine \'' . $engineClass . '\' is not a registered WOOPS page engine',
                    Woops_Page_Engine_Exception::EXCEPTION_ENGINE_NOT_REGISTERED
                );
            }
            
            if( !is_subclass_of( $engineClass, 'Woops_Page_Engine_Base' ) ) {
                
                throw new Woops_Page_Engine_Exception(
                    'The page engine \'' . $engineClass . '\' is not a valid WOOPS page engine, since it does extends the \'Woops_Page_Engine_Base\' abstract class',
                    Woops_Page_Engine_Exception::EXCEPTION_ENGINE_NOT_VALID
                );
            }
            
            $this->_pageEngine = new $engineClass();
            
            $engineOptions     = unserialize( $this->_pageGetter->getEngineOptions() );
            
            if( !is_object( $engineOptions ) ) {
                
                $engineOptions = new stdClass();
            }
            
            $this->_pageEngine->loadEngine( $engineOptions );
        }
        
        return $this->_pageEngine;
    }
}
