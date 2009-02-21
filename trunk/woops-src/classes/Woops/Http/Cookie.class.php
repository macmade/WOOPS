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
 * HTTP cookie class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Http
 */
class Woops_Http_Cookie
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The cookie's name
     */
    protected $_name     = '';
    
    /**
     * The cookie's value
     */
    protected $_value    = '';
    
    /**
     * The cookie's expiration date
     */
    protected $_expires  = 0;
    
    /**
     * The cookie's path
     */
    protected $_path     = '/';
    
    /**
     * The cookie's domain
     */
    protected $_domain   = '';
    
    /**
     * Whether the cookie is secure
     */
    protected $_secure   = false;
    
    /**
     * Whether the cookie is secure
     */
    protected $_httpOnly = false;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct( $name, $value = '', $expires = 0, $path = '/', $domain = '', $secure = false, $httpOnly = false )
    {
        $this->_name     = ( string )$name;
        $this->_value    = ( string )$value;
        $this->_expires  = ( string )$expires;
        $this->_path     = ( string )$path;
        $this->_domain   = ( string )$domain;
        $this->_secure   = ( string )$secure;
        $this->_httpOnly = ( string )$httpOnly;
    }
    
    /**
     * 
     */
    public function __toString()
    {
        $cookie = $this->_name . '=' . urlencode( $this->value ) . ';';
        
        if( $this->_expires ) {
            
            $cookie .= ' expires=' . date( 'r', $this->_expires ) . ';';
        }
        
        if( $this->_path ) {
            
            $cookie .= ' path=' . $this->_path . ';';
        }
        
        if( $this->_domain ) {
            
            $cookie .= ' path=' . $this->_domain . ';';
        }
        
        if( $this->_secure ) {
            
            $cookie .= ' secure;';
        }
        
        if( $this->_httpOnly ) {
            
            $cookie .= ' HttpOnly';
        }
        
        return $cookie;
    }
    
    /**
     * 
     */
    public static function createCookieObject( $str )
    {
        $equal = strpos( $str, '=' );
    
        if( !$equal ) {
            
            throw new Woops_Http_Cookie_Exception(
                'Invalid cookie: \'' . $str . '\'',
                Woops_Http_Cookie_Exception::EXCEPTION_BAD_COOKIE
            );
        }
        
        $name    = trim( substr( $str, 0, $equal ) );
        $options = trim( substr( $str, $equal + 1 ) );
        $parts   = explode( ';', $options );
        $value   = trim( array_shift( $parts ) );
        
        $cookie  = new self( $name, $value );
        
        foreach( $parts as $part ) {
            
            $equal = strpos( $part, '=' );
            
            if( !$equal ) {
                
                $name = trim( $part );
                
            } else {
                
                $name  = trim( substr( $part, 0, $equal ) );
                $value = trim( substr( $part, $equal + 1 ) );
            }
            
            switch( $name ) {
                
                case 'expires';
                    
                    $cookie->setExpires( strtotime( $value ) );
                    break;
                    
                case 'path';
                    
                    $cookie->setPath( $value );
                    break;
                    
                case 'domain';
                    
                    $cookie->setDomain( $value );
                    break;
                    
                case 'secure';
                    
                    $cookie->setSecure( true );
                    break;
                    
                case 'HttpOnly';
                    
                    $cookie->setHttpOnly( true );
                    break;
                
                default:
                    
                    break;
            }
        }
        
        return $cookie;
    }
    
    /**
     * 
     */
    public function setCookie()
    {
        return setcookie(
            $this->_name,
            $this->_value,
            $this->_expires,
            $this->_path,
            $this->_domain,
            $this->_secure,
            $this->_httpOnly
        );
    }
    
    /**
     * 
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * 
     */
    public function getExpires()
    {
        return $this->_expires;
    }
    
    /**
     * 
     */
    public function getPath()
    {
        return $this->_path;
    }
    
    /**
     * 
     */
    public function getDomain()
    {
        return $this->_domain;
    }
    
    /**
     * 
     */
    public function getSecure()
    {
        return $this->_secure;
    }
    
    /**
     * 
     */
    public function getHttpOnly()
    {
        return $this->_httpOnly;
    }
    
    /**
     * 
     */
    public function setValue( $value )
    {
        $this->_value = ( string )$value;
    }
    
    /**
     * 
     */
    public function setExpires( $value )
    {
        $this->_expires = ( int )$value;
    }
    
    /**
     * 
     */
    public function setPath( $value )
    {
        $this->_path = ( string )$value;
    }
    
    /**
     * 
     */
    public function setDomain( $value )
    {
        $this->_domain = ( string )$value;
    }
    
    /**
     * 
     */
    public function setSecure( $value )
    {
        $this->_secure = ( boolean )$value;
    }
    
    /**
     * 
     */
    public function setHttpOnly( $value )
    {
        $this->_httpOnly = ( boolean )$value;
    }
}
