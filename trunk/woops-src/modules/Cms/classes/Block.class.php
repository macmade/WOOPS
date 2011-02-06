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
namespace Woops\Mod\Cms;

/**
 * Abstract for the CMS blocks
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Cms
 */
abstract class Block extends \Woops\Core\Module\Block
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    abstract public function getBlockContent( \Woops\Xhtml\Tag $content, \stdClass $options );
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic               = false;
    
    /**
     * Whether the jQuery framework has been included
     */
    private static $_hasJQuery               = false;
    
    /**
     * Whether the jQuery UI framework has been included
     */
    private static $_hasJQueryUi             = false;
    
    /**
     * The webtoolkit scripts that have been included
     */
    private static $_webtoolkitLoadedScripts = array();
    
    /**
     * The jQuery plugins that have been included
     */
    private static $_jQueryLoadedPlugins     = array();
    
    /**
     * The dependancies for the jQuery plugins
     */
    private static $_jQueryPluginsDeps       = array
    (
        'accordion' => array
        (
            'dimensions'
        )
    );
    
    /**
     * The database object
     */
    protected static $_db                    = NULL;
    
    /**
     * The XHTML page object
     */
    protected static $_page                  = NULL;
    
    /**
     * The CSS prefix for the current class
     */
    protected $_cssPrefix                    = '';
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        // Calls the parent constructor
        parent::__construct();
        
        // Checks if the static variables are set
        if( !self::$_hasStatic )
        {
            // Sets the static variables
            self::_setStaticVars();
        }
        
        $this->_cssPrefix = str_replace( '\\', '-', $this->_modClass ) . '-';
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        self::$_db        = \Woops\Database\Layer::getInstance()->getEngine();
        self::$_page      = \Woops\Page\Engine::getInstance()->getPageObject()->getXhtmlPage();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * 
     */
    private function _addJs( $path )
    {
        if( $webPath = self::$_env->getSourceWebPath( $path ) )
        {
            self::$_page->addJavaScriptSource( $webPath, false, 'utf-8' );
        }
    }
    
    /**
     * Includes the jQuery JS framework
     * 
     * @return  void
     */
    protected function _includeJQuery()
    {
        // Only includes the script once
        if( self::$_hasJQuery === false )
        {
            // Adds the JS script
            $this->_addJs( 'deps/javascript/jquery/jquery.js' );
            
            // Script has been included
            self::$_hasJQuery = true;
        }
    }
    
    /**
     * Includes the jQuery UI JS framework
     * 
     * @return  void
     */
    protected function _includeJQueryUi()
    {
        // Only includes the script once
        if( self::$_hasJQueryUi === false )
        {
            // Adds the JS script
            $this->_addJs( 'deps/javascript/jquery-ui/jquery-ui.js' );
            
            // Script has been included
            self::$_hasJQueryUi = true;
        }
    }
    
    /**
     * Includes a Webtoolkit script
     * 
     * Available scripts are:
     * - base64
     * - crc32
     * - md5
     * - sha1
     * - sha256
     * - url
     * - utf8
     * 
     * @param   string  The name of the script to include
     * @return  void
     * @see     Oop_Core_ClassManager::getModuleRelativePath
     */
    protected function _includeWebtoolkitScript( $script )
    {
        // Only includes the script once
        if( !isset( self::$_webtoolkitLoadedScripts[ $script ] ) )
        {
            // Adds the JS script
            $this->_addJs( 'deps/javascript/webtoolkit/' . $script .'.js' );
            
            // Script has been included
            self::$_webtoolkitLoadedScripts[ $script ] = true;
        }
    }
    
    /**
     * Includes a jQuery plugin
     * 
     * Available plugins are:
     * - accordion
     * - dimensions
     * 
     * @param   string  The name of the plugin to include
     * @return  void
     */
    protected function _includeJQueryPlugin( $plugin )
    {
        // Only includes the script once
        if( !isset( self::$_jQueryLoadedPlugins[ $plugin ] ) )
        {
            // Checks for dependancies
            if( isset( self::$_jQueryPluginsDeps[ $plugin ] ) )
            {
                // Process each dependancy
                foreach( self::$_jQueryPluginsDeps[ $plugin ] as $deps )
                {
                    // Includes the plugin
                    $this->_includeJQueryPlugin( $deps );
                }
            }
            
            // Adds the JS script
            $this->_addJs( 'deps/javascript/jquery/jquery.' . $script .'.js' );
            
            // Script has been included
            self::$_jQueryLoadedPlugins[ $plugin ] = true;
        }
    }
    
    /**
     * 
     */
    protected function _cssClass( \Woops\Xhtml\Tag $tag, $name )
    {
        $tag[ 'class' ] = $this->_cssPrefix . $name;
    }
}
