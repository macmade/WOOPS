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
namespace Woops\Core\Module;

/**
 * Abstract for the module blocks
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Module
 */
abstract class Base extends \Woops\Core\Aop\Advisor
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic       = false;
    
    /**
     * 
     */
    private static $_moduleVariables = array();
    
    /**
     * 
     */
    private static $_modManager      = NULL;
    
    /**
     * 
     */
    protected static $_conf          = NULL;
    
    /**
     * 
     */
    protected static $_request       = NULL;
    
    /**
     * 
     */
    protected static $_env           = NULL;
    
    /**
     * 
     */
    protected static $_str           = NULL;
    
    /**
     * 
     */
    protected $_lang                 = NULL;
    
    /**
     * 
     */
    protected $_modClass             = '';
    
    /**
     * 
     */
    protected $_modName              = '';
    
    /**
     * 
     */
    protected $_modPath              = '';
    
    /**
     * 
     */
    protected $_modRelPath           = '';
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        // Call the parent constructor
        parent::__construct();
        
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        $this->_modClass   = get_class( $this );
        $this->_modName    = substr( $this->_modClass, 10, strpos( $this->_modClass, '\\', 10 ) - 10 );
        $this->_modPath    = self::$_modManager->getModulePath( $this->_modName );
        $this->_modRelPath = self::$_modManager->getModuleRelativePath( $this->_modName );
        
        try {
            
            $this->_lang       = \Woops\Core\Lang\Getter::getInstance(
                $this->_modPath
              . 'lang'
              . DIRECTORY_SEPARATOR
              . str_replace( 'Woops\Mod\\' . $this->_modName . '\\', '', $this->_modClass )
              . '.'
            );
            
        } catch( \Woops\Core\Lang\Getter\Exception $e ) {
            
            if( $e->getCode() === \Woops\Core\Lang\Getter\Exception::EXCEPTION_NO_LANG_FILE ) {
                
                $this->_lang = \Woops\Core\Lang\Getter::getDefaultInstance();
                
            } else {
                
                throw $e;
            }
        }
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        self::$_conf            = \Woops\Core\Config\Getter::getInstance();
        self::$_modManager      = \Woops\Core\Module\Manager::getInstance();
        self::$_request         = \Woops\Core\Request\Getter::getInstance();
        self::$_env             = \Woops\Core\Env\Getter::getInstance();
        self::$_str             = \Woops\Helpers\StringUtilities::getInstance();
        self::$_moduleVariables = self::$_request->getWoopsVar( 'mod' );
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * 
     */
    protected function _getModuleVar( $name )
    {
        if( isset( self::$_moduleVariables[ $this->_modName ][ $name ] ) ) {
            
            return self::$_moduleVariables[ $this->_modName ][ $name ];
        }
        
        return false;
    }
}
