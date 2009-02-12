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
 * CMS page engine
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Cms.Page
 */
class Woops_Mod_Cms_Page_Engine extends Woops_Page_Engine_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
    public function __toString()
    {
        return ( string )$this->_page;
    }
    
    /**
     * 
     */
    public function loadEngine( stdClass $options )
    {
        $this->_tagMap   = ( isset( $options->tagMap )   && is_array( $options->tagMap ) )   ? $options->tagMap   : array();
        $this->_keepHead = ( isset( $options->keepHead ) && is_array( $options->keepHead ) ) ? $options->keepHead : array();
        
        $this->_page     = new Woops_Xhtml_Page();
        $this->_template = $this->_parseTemplate();
        
        $head = $this->_template->getTag( 'head' );
        $body = $this->_page->getBody();
        
        foreach( $this->_keepHead as $key => $value ) {
            
            if( $headPart = $head->getTag( $value[ 0 ], $value[ 1 ] ) ) {
                
                $this->_page->addHeadNode( $headPart );
            }
            
        }
        
        foreach( $this->_template->getTag( 'body' ) as $bodyNode ) {
            
            if( is_object( $bodyNode ) ) {
                
                $body->addChildNode( $bodyNode );
                
            } else {
                
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
    private function _parseTemplate()
    {
        $templateDir    = self::$_env->getPath( 'ressources/templates/' );
        $templateDirRel = self::$_env->getWebPath( 'ressources/templates/' );
        
        if( !$templateDir ) {
            
            throw new Woops_Mod_Cms_Page_Engine_Exception(
                'The templates directory does not exist',
                Woops_Mod_Cms_Page_Engine_Exception::EXCEPTION_NO_TEMPLATE_DIR
            );
        }
        
        $path = $templateDir . self::$_pageGetter->getTemplate();
        
        if( !file_exists( $path ) ) {
            
            throw new Woops_Mod_Cms_Page_Engine_Exception(
                'The template file for page ID ' . $this->_pageId . ' does not exist (' . $this->_template->file . ')',
                Woops_Mod_Cms_Page_Engine_Exception::EXCEPTION_NO_TEMPLATE_FILE
            );
        }
        
        $parser = new Woops_Xhtml_Parser( $path, $templateDirRel );
        
        return $parser->getXhtmlObject();
    }
    
    /**
     * 
     */
    public function getXhtmlPage()
    {
        return $this->_page;
    }
}
