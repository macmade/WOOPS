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
 * Handler for the 'woops-block-xhtml' XHTML processing instructions
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.XhtmlPageEngine.Xhtml.Block.ProcessingInstruction
 */
class Woops_Mod_XhtmlPageEngine_Xhtml_Block_ProcessingInstruction_Handler implements Woops_Xhtml_ProcessingInstruction_Handler_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic    = false;
    
    /**
     * 
     */
    protected static $_modManager = NULL;
    
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
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  NULL
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
        $content = new Woops_Xhtml_Tag( 'div' );
        
        $content->comment( 'Start of xhtml block: ' . $options->name );
        
        try {
            
            $block        = self::$_modManager->getBlock( 'xhtml', $options->name );
            
            $blockOptions = clone( $options );
            
            unset( $blockOptions->name );
            
            $block->getBlockContent( $content, $blockOptions );
            
        } catch( Exception $e ) {
            
            $error            = $content->div->strong;
            $error[ 'style' ] = 'color: #FF0000;';
            
            $error->addTextData( '[MISSING BLOCK: ' . $options->name . ']' );
            
            $content->div     = $e->getMessage();
        }
        
        $content->comment( 'End of xhtml block: ' . $options->name );
        
        return $content;
    }
}
