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
 * Getter class for the WOOPS pages
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Page
 */
final class Woops_Page_Getter
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The configuration object
     */
    private $_conf            = NULL;
    
    /**
     * The environment object
     */
    private $_env             = NULL;
    
    /**
     * The request object
     */
    private $_request         = NULL;
    
    /**
     * The database object
     */
    private $_db              = NULL;
    
    /**
     * The string utilities
     */
    private $_str             = NULL;
    
    /**
     * The ID of the current page
     */
    private $_pageId          = 0;
    
    /**
     * The name of the current language
     */
    private $_langName        = '';
    
    /**
     * The database row for the current page
     */
    private $_page            = array();
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    private function __construct()
    {
        $this->_conf     = Woops_Core_Config_Getter::getInstance();
        $this->_env      = Woops_Core_Env_Getter::getInstance();
        $this->_request  = Woops_Core_Request_Getter::getInstance();
        $this->_db       = Woops_Database_Layer::getInstance();
        $this->_str      = Woops_String_Utils::getInstance();
        
        $this->_pageId   = $this->_getPageId();
        $this->_langName = $this->_getLanguage();
        $this->_page     = $this->_getPage( $this->_pageId, $this->_langName );
        $this->_template = $this->_getTemplate( $this->_page->id_templates );
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
     * @return  Woops_Page_Getter   The unique instance of the class
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
    private function _getPageId()
    {
        $id = ( int )$this->_request->woopsPageId;
        
        if( !$id || !$this->_db->getRecord( 'pageinfos', $id ) ) {
            
            $homeRecords = $this->_db->getRecordsByFields(
                'pageinfos',
                array(
                    'home' => 1
                )
            );
            
            if( !count( $homeRecords ) ) {
                
                throw new Woops_Page_Getter_Exception(
                    'No home page is defined in the database',
                    Woops_Page_Getter_Exception::EXCEPTION_NO_HOMEPAGE
                );
            }
            
            $homePage = array_shift( $homeRecords );
            
            $id       = $homePage->id_pageinfos;
        }
        
        return $id;
    }
    
    /**
     * 
     */
    private function _getLanguage()
    {
        $lang = $this->_request->woopsLanguage;
        
        if( !$lang ) {
            
            $lang = $this->_conf->getVar( 'lang', 'defaultLanguage' );
        }
        
        if( !$lang ) {
            
            throw new Woops_Page_Getter_Exception(
                'The WOOPS default language is not configured',
                Woops_Page_Getter_Exception::EXCEPTION_NO_DEFAULT_LANG
            );
        }
        
        return $lang;
    }
    
    /**
     * 
     */
    private function _getPage( $id, $lang )
    {
        $page = $this->_db->getRecordsByFields(
            'pageheaders',
            array(
                'id_pageinfos' => $id,
                'lang'         => $lang
            )
        );
        
        if( !count( $page ) ) {
            
            $page = $this->_db->getRecordsByFields(
                'pageheaders',
                array(
                    'id_pageinfos' => $id,
                    'lang'         => $this->_conf->getVar( 'lang', 'defaultLanguage' )
                )
            );
        }
        
        if( !count( $page ) ) {
            
            throw new Woops_Page_Getter_Exception(
                'Cannot find a page record for page ID ' . $id,
                Woops_Page_Getter_Exception::EXCEPTION_NO_PAGE
            );
        }
        
        return array_shift( $page );
    }
    
    /**
     * 
     */
    private function _getTemplate( $id )
    {
        $template = $this->_db->getRecord( 'templates', $id );
        
        if( !is_object( $template ) ) {
            
            throw new Woops_Page_Getter_Exception(
                'Cannot find a template record for page ID ' . $this->_pageId,
                Woops_Page_Getter_Exception::EXCEPTION_NO_TEMPLATE
            );
        }
        
        if( $template->id_parent ) {
            
            $parent                   = $this->_getTemplate( $template->id_parent );
            $template->file           = $parent->file;
            $template->engine         = $parent->engine;
        }
        
        return $template;
    }
    
    /**
     * 
     */
    public function getTitle()
    {
        return $this->_page->title;
    }
    
    /**
     * 
     */
    public function getMenuTitle()
    {
        return $this->_page->menutitle;
    }
    
    /**
     * 
     */
    public function getLanguage()
    {
        return $this->_page->lang;
    }
    
    /**
     * 
     */
    public function getKeywords()
    {
        return $this->_page->keywords;
    }
    
    /**
     * 
     */
    public function getDescription()
    {
        return $this->_page->description;
    }
    
    /**
     * 
     */
    public function getTemplate()
    {
        return $this->_template->file;
    }
    
    /**
     * 
     */
    public function getEngine()
    {
        $this->_template->engine;
    }
    
    /**
     * 
     */
    public function getEngineOptions()
    {
        $this->_template->engine_options;
    }
}
