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
 * Hello world CMS block
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.HelloWorld
 */
class Woops_Mod_HelloWorld_SayHello extends Woops_Mod_Cms_Block
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
        $content->div->strong = $this->_lang->hello;
        $content->div->small  = sprintf( $this->_lang->method, __METHOD__ );
    }
}
