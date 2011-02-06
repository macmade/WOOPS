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
namespace Woops\Mod\Cms\Page;

/**
 * CMS page engine
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Cms.Page
 */
class Engine extends \Woops\Page\Engine\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    protected $_page     = NULL;
    
    /**
     * 
     */
    protected $_template = NULL;
    
    /**
     * 
     */
    protected $_tagMap   = array();
    
    /**
     * 
     */
    protected $_keepHead = array();
    
    /**
     * 
     */
    private function _parseTemplate()
    {
        $path = self::$_env->getPath( self::$_pageGetter->getTemplate() );
        
        if( !$path )
        {
            throw new Engine\Exception
            (
                'The template file for page ID ' . self::$_pageGetter->getPageId() . ' does not exist (' . self::$_pageGetter->getTemplate() . ')',
                Engine\Exception::EXCEPTION_NO_TEMPLATE_FILE
            );
        }
        
        $parser = new \Woops\Xhtml\Parser( $path, dirname( self::$_env->getWebPath( self::$_pageGetter->getTemplate() ) ) . '/' );
        
        return $parser->getXhtmlObject();
    }
    
    /**
     * 
     */
    public function writePage()
    {
        return ( string )$this->_page;
    }
    
    /**
     * 
     */
    public function loadEngine( \stdClass $options )
    {
        $this->_tagMap   = ( isset( $options->tagMap )   && is_array( $options->tagMap ) )   ? $options->tagMap   : array();
        $this->_keepHead = ( isset( $options->keepHead ) && is_array( $options->keepHead ) ) ? $options->keepHead : array();
        
        $this->_page     = new \Woops\Xhtml\Page();
        $this->_template = $this->_parseTemplate();
        
        $head = $this->_template->getTag( 'head' );
        $body = $this->_page->getBody();
        
        foreach( $this->_keepHead as $key => $value )
        {
            if( $headPart = $head->getTag( $value[ 0 ], $value[ 1 ] ) )
            {
                $this->_page->addHeadNode( $headPart );
            }
            
        }
        
        foreach( $this->_template->getTag( 'body' ) as $bodyNode )
        {
            if( is_object( $bodyNode ) )
            {
                $body->addChildNode( $bodyNode );
            }
            else
            {
                $body->addTextData( $bodyNode );
            }
        }
        
        $charset              = self::$_conf->getModuleVar( 'Cms', 'pageEngine', 'charset' );
        $doctype              = self::$_conf->getModuleVar( 'Cms', 'pageEngine', 'doctype' );
        $insertXmlDeclaration = self::$_conf->getModuleVar( 'Cms', 'pageEngine', 'insertXmlDeclaration' );
        $insertDoctype        = self::$_conf->getModuleVar( 'Cms', 'pageEngine', 'insertDoctype' );
        
        $this->_page->setCharset( $charset );
        $this->_page->setDocType( $doctype );
        $this->_page->insertXmlDeclaration( $insertXmlDeclaration );
        $this->_page->insertDocType( $insertDoctype );
        
        $this->_page->setTitle( self::$_pageGetter->getTitle() );
        $this->_page->setLanguage( self::$_pageGetter->getLanguage() );
    }
    
    /**
     * 
     */
    public function getXhtmlPage()
    {
        return $this->_page;
    }
}
