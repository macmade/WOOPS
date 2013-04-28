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
 * HTTP cookie class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Http
 */
class Woops_Http_Cookie extends Woops_Core_Object
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
     * The cookie's expiration date (timestamp)
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
     * @param   string  The cookie's name
     * @param   string  The cookie's value
     * @param   int     The cookie's expiration date, as a timestamp
     * @param   string  The cookie's path
     * @param   string  The cookie's domain
     * @param   boolean Whether the cookie is secured
     * @param   boolean Whether the cookie is accessible only through the HTTP protocol
     * @return  void
     */
    public function __construct( $name, $value = '', $expires = 0, $path = '/', $domain = '', $secure = false, $httpOnly = false )
    {
        // Stores the cookie's parameters
        $this->_name     = ( string )$name;
        $this->_value    = ( string )$value;
        $this->_expires  = ( string )$expires;
        $this->_path     = ( string )$path;
        $this->_domain   = ( string )$domain;
        $this->_secure   = ( string )$secure;
        $this->_httpOnly = ( string )$httpOnly;
    }
    
    /**
     * Returns a string representation of the HTTP cookie
     * 
     * @return  string  The string representation of the HTTP cookie
     */
    public function __toString()
    {
        // Name and value of the cookie
        $cookie = $this->_name . '=' . urlencode( $this->_value ) . ';';
        
        // Checks for an expiration date
        if( $this->_expires ) {
            
            // Adds the expiration date (as in RFC-2822)
            $cookie .= ' expires=' . date( 'r', $this->_expires ) . ';';
        }
        
        // Checks for a path
        if( $this->_path ) {
            
            // Adds the path
            $cookie .= ' path=' . $this->_path . ';';
        }
        
        // Checks for a domain
        if( $this->_domain ) {
            
            // Adds the domain
            $cookie .= ' domain=' . $this->_domain . ';';
        }
        
        // Checks if the cookie is secure
        if( $this->_secure ) {
            
            // Adds the secure option
            $cookie .= ' secure;';
        }
        
        // Checks if the cookie is available only through the HTTP protocol
        if( $this->_httpOnly ) {
            
            // Adds the HTTP only option
            $cookie .= ' HttpOnly;';
        }
        
        // Returns the cookie string
        return $cookie;
    }
    
    /**
     * Creates a cookie object from a string
     * 
     * @param   string                      The cookie string
     * @return  Woops_Http_Cookie           The cookie object
     * @throws  Woops_Http_Cookie_Exception If the cookie string cannot be parsed
     */
    public static function createCookieObject( $str )
    {
        // Finds the position of the first '=' character
        $equal = strpos( $str, '=' );
        
        // Checks for the '=' character
        if( !$equal ) {
            
            // Invalid cookie - No '=' character
            throw new Woops_Http_Cookie_Exception(
                'Invalid cookie: \'' . $str . '\'',
                Woops_Http_Cookie_Exception::EXCEPTION_BAD_COOKIE
            );
        }
        
        // Gets the cookie's name
        $name    = trim( substr( $str, 0, $equal ) );
        
        // Gets the cookie options
        $options = trim( substr( $str, $equal + 1 ) );
        
        // Gets the cookie's options' parts
        $parts   = explode( ';', $options );
        
        // Gets the cookie valie
        $value   = trim( array_shift( $parts ) );
        
        // Creates the cookie object
        $cookie  = new self( $name, $value );
        
        // Process each part
        foreach( $parts as $part ) {
            
            // Position of the '=' character
            $equal = strpos( $part, '=' );
            
            // Checks for the '=' character
            if( !$equal ) {
                
                // Option without a value
                $name = trim( $part );
                
            } else {
                
                // Gets the name and the value of the option
                $name  = trim( substr( $part, 0, $equal ) );
                $value = trim( substr( $part, $equal + 1 ) );
            }
            
            // Checks the option name
            switch( $name ) {
                
                // Expiration date
                case 'expires';
                    
                    // Sets the expiration date (as a timestamp)
                    $cookie->setExpires( strtotime( $value ) );
                    break;
                    
                // Path
                case 'path';
                    
                    // Sets the cookie's path
                    $cookie->setPath( $value );
                    break;
                    
                // Domain
                case 'domain';
                    
                    // Sets the cookie's domain
                    $cookie->setDomain( $value );
                    break;
                    
                // Secure option
                case 'secure';
                    
                    // The cookie is secure
                    $cookie->setSecure( true );
                    break;
                    
                // HTTP only option
                case 'HttpOnly';
                    
                    // The cookie is accessible only through the HTTP protocol
                    $cookie->setHttpOnly( true );
                    break;
                
                // Unknown option
                default:
                    
                    break;
            }
        }
        
        // Returns the cookie object
        return $cookie;
    }
    
    /**
     * Sets the cookie
     * 
     * This method will call the PHP setcookie() function, with all the
     * parameters set on the current instance.
     * 
     * @return  boolean Whether the cookie has been set
     */
    public function set()
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
     * Gets the cookie's name
     * 
     * @return  string  The cookie's name
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Gets the cookie's value
     * 
     * @return  string  The cookie's value
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * Gets the cookie's expiration date
     * 
     * @param   boolean Whether to return a timestamp (false - by default), or a RFC-2822 date (true)
     * @return  mixed   The cookie's expiration date
     */
    public function getExpires( $asDate = false )
    {
        return ( $asDate ) ? date( 'r', $this->_expires ) : $this->_expires;
    }
    
    /**
     * Gets the cookie's path
     * 
     * @return  string  The cookie's path
     */
    public function getPath()
    {
        return $this->_path;
    }
    
    /**
     * Gets the cookie's domain
     * 
     * @return  string  The cookie's domain
     */
    public function getDomain()
    {
        return $this->_domain;
    }
    
    /**
     * Gets the cookie's secure option
     * 
     * @return  boolean True if the cookie is secured, otherwise false
     */
    public function getSecure()
    {
        return $this->_secure;
    }
    
    /**
     * Gets the cookie's HTTP only option
     * 
     * @return  boolean True if the cookie is available only through the HTTP protocol, otherwise false
     */
    public function getHttpOnly()
    {
        return $this->_httpOnly;
    }
    
    /**
     * Sets the cookie's value
     * 
     * @param   string  The cookie's value
     * @return  void
     */
    public function setValue( $value )
    {
        $this->_value = ( string )$value;
    }
    
    /**
     * Sets the cookie's expiration date
     * 
     * @param   mixed   The cookie's expiration date
     * @param   boolean Whether the date is a timestamp (false - by default), or a RFC-2822 date (true)
     * @return  void
     */
    public function setExpires( $value, $asDate = false )
    {
        $this->_expires = ( $asDate ) ? strtotime( $value ) : ( int )$value;
    }
    
    /**
     * Sets the cookie's path
     * 
     * @param   string  The cookie's path
     * @return  void
     */
    public function setPath( $value )
    {
        $this->_path = ( string )$value;
    }
    
    /**
     * Sets the cookie's domain
     * 
     * @param   string  The cookie's domain
     * @return  void
     */
    public function setDomain( $value )
    {
        $this->_domain = ( string )$value;
    }
    
    /**
     * Sets the cookie's secure option
     * 
     * @param   boolean Whether the cookie is secure
     * @return  void
     */
    public function setSecure( $value )
    {
        $this->_secure = ( boolean )$value;
    }
    
    /**
     * Sets the cookie's HTTP only option
     * 
     * @param   boolean Whether the cookie is available only through the HTTP protocol
     * @return  void
     */
    public function setHttpOnly( $value )
    {
        $this->_httpOnly = ( boolean )$value;
    }
}
