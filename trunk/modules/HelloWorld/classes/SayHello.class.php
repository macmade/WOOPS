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
namespace Woops\Mod\HelloWorld;

/**
 * Hello world CMS block
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.HelloWorld
 */
class SayHello extends \Woops\Mod\Cms\Block
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    public function getBlockContent( \Woops\Xhtml\Tag $content, \stdClass $options )
    {
        $content->div->strong = $this->_lang->hello;
        $content->div->small  = sprintf( $this->_lang->method, __METHOD__ );
    }
}
