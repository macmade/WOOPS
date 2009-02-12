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
 * Abstract for the XHTML blocks
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.XhtmlPageEngine.Xhtml
 */
abstract class Woops_Mod_XhtmlPageEngine_Xhtml_Block extends Woops_Core_Module_Block
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    abstract public function getBlockContent( Woops_Xhtml_Tag $content, stdClass $options );
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic  = false;
    
    /**
     * 
     */
    protected static $_db       = NULL;
    
    /**
     * 
     */
    protected $_cssPrefix       = '';
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    public function __construct()
    {
        // Calls the parent constructor
        parent::__construct();
        
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        $this->_cssPrefix = $this->_modName . '-' . $this->_blockName . '-';
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  NULL
     */
    private static function _setStaticVars()
    {
        self::$_db        = Woops_Database_Layer::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * 
     */
    protected function _cssClass( Woops_Xhtml_Tag $tag, $name )
    {
        $tag[ 'class' ] = $this->_cssPrefix . $name;
    }
}
