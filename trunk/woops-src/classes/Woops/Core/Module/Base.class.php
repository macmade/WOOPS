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
 * Abstract for the module blocks
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Module
 */
abstract class Woops_Core_Module_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic  = false;
    
    /**
     * 
     */
    private static $_modManager = NULL;
    
    /**
     * 
     */
    protected static $_conf     = NULL;
    
    /**
     * 
     */
    protected static $_request  = NULL;
    
    /**
     * 
     */
    protected static $_env      = NULL;
    
    /**
     * 
     */
    protected static $_str      = NULL;
    
    /**
     * 
     */
    protected $_lang            = NULL;
    
    /**
     * 
     */
    protected $_modClass        = '';
    
    /**
     * 
     */
    protected $_modName         = '';
    
    /**
     * 
     */
    protected $_modPath         = '';
    
    /**
     * 
     */
    protected $_modRelPath      = '';
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    public function __construct()
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        $this->_modClass   = get_class( $this );
        $this->_modName    = substr( $this->_modClass, 10, strpos( $this->_modClass, '_', 10 ) - 10 );
        $this->_modPath    = self::$_modManager->getModulePath( $this->_modName );
        $this->_modRelPath = self::$_modManager->getModuleRelativePath( $this->_modName );
        
        try {
            
            $this->_lang       = Woops_Core_Lang_Getter::getInstance(
                $this->_modPath
              . 'lang'
              . DIRECTORY_SEPARATOR
              . str_replace( 'Woops_Mod_' . $this->_modName . '_', '', $this->_modClass )
              . '.'
            );
            
         } catch( Exception $e ) {
            
            if( $e->getCode() === Woops_Core_Lang_Getter_Exception::EXCEPTION_NO_LANG_FILE ) {
                
                $this->_lang = Woops_Core_Lang_Getter::getDefaultInstance();
                
            } else {
                
                throw $e;
            }
         }
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  NULL
     */
    private static function _setStaticVars()
    {
        self::$_conf       = Woops_Core_Config_Getter::getInstance();
        self::$_modManager = Woops_Core_Module_Manager::getInstance();
        self::$_request    = Woops_Core_Request_Getter::getInstance();
        self::$_env        = Woops_Core_Env_Getter::getInstance();
        self::$_str        = Woops_String_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
}
