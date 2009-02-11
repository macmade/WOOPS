<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
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
final class Woops_Page_Engine implements Woops_Core_Singleton_Interface
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
     * The configuration object
     */
    private $_conf              = NULL;
    
    /**
     * The page getter object
     */
    private $_pageGetter        = NULL;
    
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
     * @return  NULL
     */
    private function __construct()
    {
        $this->_conf              = Woops_Core_Config_Getter::getInstance();
        $this->_pageGetter        = Woops_Page_Getter::getInstance();
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
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
    public function registerPageEngineClass( $className )
    {
        if( !class_exists( $className ) ) {
            
            throw new Woops_Page_Engine_Exception(
                'Cannot register unexisting class \'' . $className . '\' as a page engine',
                Woops_Page_Engine_Exception::EXCEPTION_NO_ENGINE_CLASS
            );
        }
        
        $this->_pageEngines[ $className ] = true;
    }
    
    /**
     * 
     */
    public function getPageObject()
    {}
}