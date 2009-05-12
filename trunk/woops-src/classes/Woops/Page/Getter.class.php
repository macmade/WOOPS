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
 * Getter class for the WOOPS pages
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Page
 */
class Getter extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The configuration object
     */
    protected $_conf            = NULL;
    
    /**
     * The environment object
     */
    protected $_env             = NULL;
    
    /**
     * The request object
     */
    protected $_request         = NULL;
    
    /**
     * The database object
     */
    protected $_db              = NULL;
    
    /**
     * The string utilities
     */
    protected $_str             = NULL;
    
    /**
     * The ID of the current page
     */
    protected $_pageId          = 0;
    
    /**
     * The name of the current language
     */
    protected $_langName        = '';
    
    /**
     * The database row for the current page
     */
    protected $_page            = array();
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    protected function __construct()
    {
        $this->_conf     = \Woops\Core\Config\Getter::getInstance();
        $this->_env      = \Woops\Core\Env\Getter::getInstance();
        $this->_request  = \Woops\Core\Request\Getter::getInstance();
        $this->_db       = \Woops\Database\Layer::getInstance()->getEngine();
        $this->_str      = \Woops\Helper\StringUtilities::getInstance();
        
        $this->_pageId   = $this->_getPageId();
        $this->_langName = $this->_getLanguage();
        $this->_page     = $this->_getPage( $this->_pageId, $this->_langName );
        $this->_template = $this->_getTemplate( $this->_page->id_templates );
    }
    
    /**
     * 
     */
    protected function _getPageId()
    {
        $id = ( int )$this->_request->pid;
        
        if( !$id || !$this->_db->getRecord( 'pageinfos', $id ) ) {
            
            $homeRecords = $this->_db->getRecordsByFields(
                'pageinfos',
                array(
                    'home' => 1
                )
            );
            
            if( !count( $homeRecords ) ) {
                
                throw new Getter\Exception(
                    'No home page is defined in the database',
                    Getter\Exception::EXCEPTION_NO_HOMEPAGE
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
    protected function _getLanguage()
    {
        $lang = $this->_request->lang;
        
        if( !$lang ) {
            
            $lang = $this->_conf->getVar( 'lang', 'defaultLanguage' );
        }
        
        if( !$lang ) {
            
            throw new Getter\Exception(
                'The WOOPS default language is not configured',
                Getter\Exception::EXCEPTION_NO_DEFAULT_LANG
            );
        }
        
        return $lang;
    }
    
    /**
     * 
     */
    protected function _getPage( $id, $lang )
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
            
            throw new Getter\Exception(
                'Cannot find a page record for page ID ' . $id,
                Getter\Exception::EXCEPTION_NO_PAGE
            );
        }
        
        return array_shift( $page );
    }
    
    /**
     * 
     */
    protected function _getTemplate( $id )
    {
        $template = $this->_db->getRecord( 'templates', $id );
        
        if( !is_object( $template ) ) {
            
            throw new Getter\Exception(
                'Cannot find a template record for page ID ' . $this->_pageId,
                Getter\Exception::EXCEPTION_NO_TEMPLATE
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
    public function getPageId()
    {
        return $this->_pageId;
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
        return $this->_template->engine;
    }
    
    /**
     * 
     */
    public function getEngineOptions()
    {
        return $this->_template->engine_options;
    }
}
