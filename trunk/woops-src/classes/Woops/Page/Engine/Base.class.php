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
namespace Woops\Page\Engine;

/**
 * Abstract for the WOOPS page engine classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Page.Engine
 */
abstract class Base extends \Woops\Core\Aop\Advisor
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    abstract public function writePage();
    
    /**
     * 
     */
    abstract public function loadEngine( \stdClass $options );
    
    /**
     * Whether the static variables are set or not
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
        self::$_pageGetter = \Woops\Page\Getter::getInstance();
        
        // Gets the instance of the environment object
        self::$_env        = \Woops\Core\Env\Getter::getInstance();
        
        // Gets the instance of the configuration object
        self::$_conf       = \Woops\Core\Config\Getter::getInstance();
        
        // Gets the instance of the string utilities
        self::$_str        = \Woops\Helpers\StringUtilities::getInstance();
        
        // Static variables are set
        self::$_hasStatic  = true;
    }
}
