<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Abstract for the WOOPS page engine classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Page.Engine
 */
abstract class Woops_Page_Engine_Base extends Woops_Core_Aop_Advisor
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    abstract public function writePage();
    
    /**
     * 
     */
    abstract public function loadEngine( stdClass $options );
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic    = false;
    
    /**
     * The page getter object
     */
    protected static $_pageGetter = NULL;
    
    /**
     * The environment object
     */
    protected static $_env        = NULL;
    
    /**
     * The configuration object
     */
    protected static $_conf       = NULL;
    
    /**
     * The string utilities
     */
    protected static $_str        = NULL;
    
    /**
     * 
     */
    final public function __construct()
    {
        // Calls the parent constructor
        parent::__construct();
        
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the WOOPS page getter
        self::$_pageGetter = Woops_Page_Getter::getInstance();
        
        // Gets the instance of the environment object
        self::$_env        = Woops_Core_Env_Getter::getInstance();
        
        // Gets the instance of the configuration object
        self::$_conf       = Woops_Core_Config_Getter::getInstance();
        
        // Gets the instance of the string utilities
        self::$_str        = Woops_String_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic  = true;
    }
}
