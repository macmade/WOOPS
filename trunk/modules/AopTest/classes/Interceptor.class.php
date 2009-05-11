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
namespace Woops\Mod\AopTest;

/**
 * AOP test interceptor
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.AopTest
 */
class Interceptor extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    public static function interceptBefore( \Woops\Xhtml\Tag $content, \stdClass $options )
    {
        $message            = $content->div->pre;
        $message[ 'class' ] = 'small';
        
        $message->addTextData( 'AOP before call interception: ' . __METHOD__ );
    }
    
    /**
     * 
     */
    public static function interceptAfter( \Woops\Xhtml\Tag $content, \stdClass $options )
    {
        $message            = $content->div->pre;
        $message[ 'class' ] = 'small';
        
        $message->addTextData( 'AOP after call interception: ' . __METHOD__ );
    }
}
