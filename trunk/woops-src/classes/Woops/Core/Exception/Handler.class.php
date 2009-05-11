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
final class Handler extends \Woops\Core\Object implements \Woops\Core\Singleton\ObjectInterface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return void
     */
    private function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops\Core\Singleton\Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new \Woops\Core\Singleton\Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            \Woops\Core\Singleton\Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops\Core\Exception\Handler    The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
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
        if( !is_subclass_of( $e, '\Woops\Core\Exception\Base' ) ) {
            
            $e = new \Woops\Core\Php\Exception(
                'Exception of type ' . get_class( $e ) . ': ' . $e->getMessage(),
                $e->getCode(), $e->getTrace()
            );
        }
        
        $report = \Woops\Core\Config\Getter::getInstance()->getVar( 'error', 'report' );
        
        if( $report === 'development' ) {
            
            print $e->getInfos();
            exit();
            
        } elseif( $report === 'production' ) {
            
            print $e;
            exit();
            
        } else {
            
            exit();
        }
    }
}
