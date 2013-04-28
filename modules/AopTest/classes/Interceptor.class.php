<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * AOP test interceptor
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mod.AopTest
 */
class Woops_Mod_AopTest_Interceptor extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    public static function interceptBefore( Woops_Xhtml_Tag $content, stdClass $options )
    {
        $message            = $content->div->pre;
        $message[ 'class' ] = 'small';
        
        $message->addTextData( 'AOP before call interception: ' . __METHOD__ );
    }
    
    /**
     * 
     */
    public static function interceptAfter( Woops_Xhtml_Tag $content, stdClass $options )
    {
        $message            = $content->div->pre;
        $message[ 'class' ] = 'small';
        
        $message->addTextData( 'AOP after call interception: ' . __METHOD__ );
    }
}
