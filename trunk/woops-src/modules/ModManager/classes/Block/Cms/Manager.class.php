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
 * Hello world XHTML block
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.ModManager.Block.Cms
 */
class Woops_Mod_ModManager_Block_Cms_Manager extends Woops_Mod_Cms_Block
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    public function getBlockContent( Woops_Xhtml_Tag $content, stdClass $options )
    {
        $content->h2    = $this->_lang->available;
        
        $manager        = Woops_Core_Module_Manager::getInstance();
        
        $modules        = $manager->getAvailableModules();
        $loaded         = $manager->getLoadedModules();
        
        ksort( $modules );
        
        foreach( $modules as $name => $path ) {
            
            $modDiv              = $content->div;
            $nameDiv             = $modDiv->div;
            $nameDiv->strong     = $name;
            
            if( isset( $loaded[ $name ] ) ) {
                
                $loadState            = $nameDiv->span;
                $loadState[ 'style' ] = 'color: #00FF00';
                $loadState->addTextData( ' [LOADED]' );
            }
            
            $modDiv->div = $path[ 1 ];
        }
    }
}
