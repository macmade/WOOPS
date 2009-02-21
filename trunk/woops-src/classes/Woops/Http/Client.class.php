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
 * HTTP client class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Http
 */
class Woops_Http_Client
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available HTTP request methods
     */
    const METHOD_CONNECT = 'CONNECT';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_GET     = 'GET';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_TRACE   = 'TRACE';
    
    /**
     * The available HTTP authentication types
     */
    const AUTH_BASIC     = 'BASIC';
    const AUTH_DIGEST    = 'DIGEST';
    
    /**
     * The available HTTP protocol versions
     */
    const HTTP_VERSION_1_0        = '1.0';
    const HTTP_VERSION_1_1        = '1.1';
    
    /**
     * The available HTTP request methods
     */
    protected static $_requestMethods   = array(
        'CONNECT' => true,
        'DELETE'  => true,
        'GET'     => true,
        'HEAD'    => true,
        'OPTIONS' => true,
        'POST'    => true,
        'PUT'     => true,
        'TRACE'   => true
    );
    
    /**
     * The available HTTP authentication types
     */
    protected static $_authTypes        = array(
        'BASIC'  => true,
        'DIGEST' => true
    );
    
    /**
     * The available HTTP protocol versions
     */
    protected static $_protocolVersions = array(
        '1.0' => true,
        '1.1' => true
    );
    
    /**
     * The HTTP request URI
     */
    protected $_uri                     = NULL;
    
    /**
     * The HTTP response object
     */
    protected $_response                = NULL;
    
    /**
     * The HTTP request method
     */
    protected $_requestMethod           = '';
    
    /**
     * The HTTP authentication type
     */
    protected $_authType                = '';
    
    /**
     * The HTTP protocol version
     */
    protected $_protocolVersion         = 1.1;
    
    /**
     * The user-agent
     */
    protected $_userAgent               = __CLASS__;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct( $uri = '', $method = '' )
    {
        // Checks for a URI
        if( $uri ) {
            
            // Sets the URI
            $this->setUri( $uri );
        }
        
        // Checks for a request method
        if( $method ) {
            
            // Sets the request method
            $this->setRequestMethod( $method );
        }
    }
    
    /**
     * Sets the HTTP request URI
     * 
     * @param   string  The URI
     * @return  void
     */
    public function setUri( $uri )
    {
        $this->_uri = new Woops_Uniform_Ressource_Identifier( $uri );
    }
    
    /**
     * Sets the HTTP request method
     * 
     * @param   string                      The HTTP request method (should be one of the Woops_Http_Client::METHOD_XXX constant)
     * @return  void
     * @throws  Woops_Http_Client_Exception If the request method is invalid
     */
    public function setRequestMethod( $method )
    {
        // Converts to uppercase
        $method = strtoupper( $method );
        
        // Checks if the request method is valid
        if( !isset( self::$_requestMethods[ $method ] ) ) {
            
            // Invalid request method
            throw new Woops_Http_Client_Exception(
                'Invalid HTTP request method (' . $method . ')',
                Woops_Http_Client_Exception::EXCEPTION_INVALID_REQUEST_METHOD
            );
        }
        
        // Sets the request method
        $this->_requestMethod = $method;
    }
    
    /**
     * Sets the HTTP authentication type
     * 
     * @param   string                      The HTTP authentication type (should be one of the Woops_Http_Client::AUTH_XXX constant)
     * @return  void
     * @throws  Woops_Http_Client_Exception If the authentication type is invalid
     */
    public function setAuthType( $type )
    {
        // Converts to uppercase
        $type = strtoupper( $type );
        
        // Checks if the request method is valid
        if( !isset( self::$_authTypes[ $type ] ) ) {
            
            // Invalid request method
            throw new Woops_Http_Client_Exception(
                'Invalid HTTP authentication type (' . $type . ')',
                Woops_Http_Client_Exception::EXCEPTION_INVALID_AUTH_TYPE
            );
        }
        
        // Sets the request method
        $this->_authType = $type;
    }
    
    /**
     * Sets the HTTP protocol version
     * 
     * @param   string                      The HTTP protocol version (should be one of the Woops_Http_Client::HTTP_VERSION_XXX constant)
     * @return  void
     * @throws  Woops_Http_Client_Exception If the protocol version is invalid
     */
    public function setProtocolVersion( $version )
    {
        // Converts to floating point
        $version = ( float )$version;
        
        // Checks if the request method is valid
        if( !isset( self::$_protocolVersions[ ( string )$version ] ) ) {
            
            // Invalid request method
            throw new Woops_Http_Client_Exception(
                'Invalid HTTP protocol version (' . $version . ')',
                Woops_Http_Client_Exception::EXCEPTION_INVALID_PROTOCOL_VERSION
            );
        }
        
        // Sets the request method
        $this->_protocolVersion = $version;
    }
    
    /**
     * Sets the user-agent
     * 
     * @param   string  The user agent
     * @return  void
     */
    public function setUserAgent( $value )
    {
        $this->_userAgent = ( string )$value;
    }
    
    /**
     * Gets the HTTP response
     * 
     * @return  Woops_Http_Response The HTTP response object
     */
    public function getResponse()
    {
        return $this->_response;
    }
}
