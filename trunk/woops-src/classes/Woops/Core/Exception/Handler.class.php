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
namespace Woops\Core\Exception;

/**
 * WOOPS exception handler class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Exception
 */
final class Handler extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    public static function handleException( \Exception $e )
    {
        self::getInstance()->_handleException( $e );
    }
    
    /**
     * 
     */
    private function _handleException( \Exception $e )
    {
        if( !is_subclass_of( $e, 'Woops\Core\Exception\Base' ) )
        {
            $e = new \Woops\Core\Php\Exception
            (
                'Exception of type ' . get_class( $e ) . ': ' . $e->getMessage(),
                $e->getCode(), $e->getTrace()
            );
        }
        
        $report = \Woops\Core\Config\Getter::getInstance()->getVar( 'error', 'report' );
        
        if( $report === 'development' )
        {
            print $e->getInfos();
            exit();
        }
        elseif( $report === 'production' )
        {
            print $e;
            exit();
        }
        else
        {
            exit();
        }
    }
}
