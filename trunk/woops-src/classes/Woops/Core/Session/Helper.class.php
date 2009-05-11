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
namespace Woops\Core\Session;

/**
 * Session helper class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Session
 */
class Helper extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The session ID for the WOOPS
     */
    const SESSION_ID          = 'WOOPS-SESSION';
    
    /**
     * Whether the WOOPS session is started
     */
    protected $_started = '';
    
    /**
     * 
     */
    public function __get( $name )
    {
        return $this->getSessionData( $name );
    }
    
    /**
     * 
     */
    public function __set( $name, $value )
    {
        $this->setSessionData( $name, $value );
    }
    
    /**
     * 
     */
    public function __isset( $name )
    {
        $this->start();
        
        $isset = isset( $_SESSION[ $name ] );
        
        $this->close();
        
        return $isset;
    }
    
    /**
     * 
     */
    public function __unset( $name )
    {
        $this->start();
        
        unset( $_SESSION[ $name ] );
        
        $this->close();
    }
    
    /**
     * 
     */
    public function start()
    {
        if( !$this->_started ) {
            
            session_id( self::SESSION_ID );
            session_start();
            $this->_started = true;
        }
    }
    
    /**
     * 
     */
    public function close()
    {
        if( $this->_started ) {
            
            session_write_close();
            $this->_started = false;
            $_SESSION = array();
        }
    }
    
    /**
     * 
     */
    public function destroy()
    {
        if( $this->_started ) {
            
            session_destroy();
            $this->_started = false;
            $_SESSION = array();
        }
    }
    
    /**
     * 
     */
    public function getSessionData( $key )
    {
        $this->start();
        
        if( isset( $_SESSION[ $key ] ) ) {
            
            return $_SESSION[ $key ];
            
        } else {
            
            return false;
        }
        
        $this->close();
    }
    
    /**
     * 
     */
    public function setSessionData( $key, $value )
    {
        $this->start();
        
        $_SESSION[ $key ] = $value;
        
        $this->close();
    }
}
