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

/**
 * Handler for the 'woops-cms-block' XHTML processing instructions
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Cms.Block.ProcessingInstruction
 */
class Woops_Mod_Cms_Block_ProcessingInstruction_Handler implements Woops_Xhtml_ProcessingInstruction_Handler_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic    = false;
    
    /**
     * 
     */
    protected static $_modManager = NULL;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
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
        // Gets the instance of the module manager
        self::$_modManager= Woops_Core_Module_Manager::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * 
     */
    function process( stdClass $options )
    {
        $content            = new Woops_Xhtml_Tag( 'div' );
        $content[ 'class' ] = str_replace( '.', '-', $options->name );
        
        $content->comment( 'Start of CMS block: ' . $options->name );
        
        try {
            
            $block        = self::$_modManager->getBlock( 'cms', $options->name );
            
            $blockOptions = clone( $options );
            
            unset( $blockOptions->name );
            
            $block->getBlockContent( $content, $blockOptions );
            
        } catch( Woops_Core_Module_Manager_Exception $e ) {
            
            $code = $e->getCode();
            
            if(    $code === Woops_Core_Module_Manager_Exception::EXCEPTION_NO_BLOCK
                || $code === Woops_Core_Module_Manager_Exception::EXCEPTION_NO_BLOCK_TYPE
                || $code === Woops_Core_Module_Manager_Exception::EXCEPTION_MODULE_NOT_LOADED
            ) {
                
                $error            = $content->div->strong;
                $error[ 'style' ] = 'color: #FF0000;';
                
                $error->addTextData( '[BLOCK ERROR: ' . $options->name . ']' );
                
                $content->div     = $e->getMessage();
                
            } else {
                
                throw $e;
            }
        }
        
        $content->comment( 'End of CMS block: ' . $options->name );
        
        return $content;
    }
}
