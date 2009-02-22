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
    const METHOD_CONNECT   = 'CONNECT';
    const METHOD_DELETE    = 'DELETE';
    const METHOD_GET       = 'GET';
    const METHOD_HEAD      = 'HEAD';
    const METHOD_OPTIONS   = 'OPTIONS';
    const METHOD_POST      = 'POST';
    const METHOD_PUT       = 'PUT';
    const METHOD_TRACE     = 'TRACE';
    
    /**
     * The available HTTP authentication types
     */
    const AUTH_BASIC       = 'BASIC';
    const AUTH_DIGEST      = 'DIGEST';
    
    /**
     * The available HTTP protocol versions
     */
    const HTTP_VERSION_1_0 = '1.0';
    const HTTP_VERSION_1_1 = '1.1';
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic          = false;
    
    /**
     * The string utilities
     */
    private static $_str                = NULL;
    
    /**
     * The environment object
     */
    private static $_env                = NULL;
    
    /**
     * The newline character (CR-LF)
     */
    private static $_CRLF               = false;
    
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
    protected $_userAgent               = '';
    
    /**
     * The HTTP request headers
     */
    protected $_headers                 = array();
    
    /**
     * The HTTP cookies
     */
    protected $_cookies                 = array();
    
    /**
     * The connection type
     */
    protected $_connection              = 'close';
    
    /**
     * The value of the Keep-Alive header (only if the connection is set to keep-alive)
     */
    protected $_keepAlive               = 300;
    
    /**
     * The connection timeout
     */
    protected $_timeout                 = 10;
    
    /**
     * The socket
     */
    protected $_socket                  = NULL;
    
    /**
     * The connection error number
     */
    protected $_errNo                   = 0;
    
    /**
     * The connection error message
     */
    protected $_errStr                  = '';
    
    /**
     * Wether the connection is established
     */
    protected $_connected               = false;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct( $uri, $method = self::METHOD_GET )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Sets the user-agent
        $this->setUserAgent(
            'WOOPS/'
          . Woops_Core_Informations::WOOPS_VERSION
          . '-'
          . Woops_Core_Informations::WOOPS_VERSION_SUFFIX
        );
        
        // Checks if the GZ functions are available
        if( function_exists( 'gzinflate' ) && function_exists( 'gzuncompress' ) ) {
            
            // Sets the 'Accept-Encoding' header
            $this->addHeader( 'Accept-Encoding', 'gzip,deflate' );
            
        } elseif( function_exists( 'gzinflate' ) ) {
            
            // Sets the 'Accept-Encoding' header
            $this->addHeader( 'Accept-Encoding', 'gzip' );
            
        } elseif( function_exists( 'gzuncompress' ) ) {
            
            // Sets the 'Accept-Encoding' header
            $this->addHeader( 'Accept-Encoding', 'deflate' );
            
        } else {
            
            // Sets the 'Accept-Encoding' header
            $this->addHeader( 'Accept-Encoding', 'identity' );
        }
        
        // Gets the configuration values from the server environment
        $protocol  = self::$_env->SERVER_PROTOCOL;
        $accept    = self::$_env->HTTP_ACCEPT;
        $language  = self::$_env->HTTP_ACCEPT_LANGUAGE;
        $encoding  = self::$_env->HTTP_ACCEPT_ENCODING;
        $charset   = self::$_env->HTTP_ACCEPT_CHARSET;
        
        // Checks for a specific protocol
        if( $protocol && substr( $protocol, 0, 5 ) === 'HTTP/' ) {
            
            // Sets the protocol version
            $this->setProtocolVersion( substr( $protocol, 5 ) );
        }
        
        // Checks for a accept option
        if( $accept ) {
            
            // Sets the accept header
            $this->addHeader( 'Accept', $accept );
        }
        
        // Checks for a language option
        if( $language ) {
            
            // Sets the language header
            $this->addHeader( 'Language', $language );
        }
        
        // Checks for a encoding option
        if( $encoding ) {
            
            // Sets the encoding header
            $this->addHeader( 'Encoding', $encoding );
        }
        
        // Checks for a charset option
        if( $charset ) {
            
            // Sets the charset header
            $this->addHeader( 'Charset', $charset );
        }
        
        // Sets the URI
        $this->setUri( $uri );
        
        // Sets the request method
        $this->setRequestMethod( $method );
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str    = Woops_String_Utils::getInstance();
        
        // Gets the instance of the environment object
        self::$_env    = Woops_Core_Env_Getter::getInstance();
        
        // Sets the newline character (CR-LF)
        self::$_CRLF   = self::$_str->CR . self::$_str->LF;
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Sets the HTTP request URI
     * 
     * @param   string                              The URI
     * @return  Woops_Uniform_Ressource_Identifier  The URI object
     * @throws  Woops_Http_Client_Exception         If the connection has already been established
     */
    public function setUri( $uri )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Creates and stores the URI object
        $this->_uri = new Woops_Uniform_Ressource_Identifier( $uri );
        
        // Returns the URI object
        return $this->_uri;
    }
    
    /**
     * Sets the HTTP request method
     * 
     * @param   string                      The HTTP request method (should be one of the Woops_Http_Client::METHOD_XXX constant)
     * @return  void
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     * @throws  Woops_Http_Client_Exception If the request method is invalid
     */
    public function setRequestMethod( $method )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
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
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     * @throws  Woops_Http_Client_Exception If the authentication type is invalid
     */
    public function setAuthType( $type )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
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
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     * @throws  Woops_Http_Client_Exception If the protocol version is invalid
     */
    public function setProtocolVersion( $version )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
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
     * Sets the connection timeout
     * 
     * @param   int                         The connection timeout
     * @return  void
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     */
    public function setTimeout( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
        $this->_timeout = ( int )$value;
    }
    
    /**
     * Sets the connection type
     * 
     * @param   string                      The connection type (keep-alive, close)
     * @return  void
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     */
    public function setConnection( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
        $this->_connection = ( string )$value;
    }
    
    /**
     * Sets the user-agent
     * 
     * @param   string                      The user agent
     * @return  void
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     */
    public function setUserAgent( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
        $this->_userAgent = ( string )$value;
    }
    
    /**
     * Sets the value of the Keep-Alive header
     * 
     * When called, this method will automatically sets the connection to
     * 'keep-alive'.
     * 
     * @param   int                         The value of the Keep-Alive header
     * @return  void
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     * @see     setConnection
     */
    public function setKeepAlive( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Sets the value
        $this->_keepAlive = ( int )$value;
        
        // Connection is set to 'keep-alive'
        $this->setConnection( 'keep-alive' );
    }
    
    /**
     * Adds a cookie
     * 
     * @param   mixed                       Either a string, or a Woops_Http_Cookie object
     * @return  void
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     */
    public function addCookie( $cookie )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Checks if the passed argument is a cookie object
        if( is_object( $cookie ) && $cookie instanceof Woops_Http_Cookie ) {
            
            // Stores the cookie object
            $this->_cookies[ $cookie->getName() ] = $cookie;
            
        } else {
            
            // Gets the cookies
            $cookies = explode( ';', $cookie );
            
            // Process each cookie
            foreach( $cookies as $cookie ) {
                
                // Creates a new cookie object
                $cookie = Woops_Http_Cookie::createCookieObject( $cookie );
                
                // Stores the cookie object
                $this->_cookies[ $cookie->getName() ] = $cookie;
            }
        }
    }
    
    
    /**
     * Adds a request header
     * 
     * @param   string                      The header's name
     * @param   string                      The header's value
     * @return  void
     * @throws  Woops_Http_Client_Exception If the connection has already been established
     */
    public function addHeader( $name, $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Woops_Http_Client_Exception(
                'The connection has already been established',
                Woops_Http_Client_Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Removes white space
        $name  = trim( $name );
        $value = trim( $value );
        
        // Checks the header name
        switch( $name ) {
            
            // User agent
            case 'User-Agent':
                
                // Sets the user-agent
                $this->setUserAgent( $value );
                break;
                
            // Host
            case 'Host':
                
                // Sets the host
                $this->_uri->setHost( $value );
                break;
                
            // Cookie
            case 'Cookie':
                
                // Sets the host
                $this->addCookie( $value );
                break;
                
            // Connection
            case 'Connection':
                
                // Sets the connection type
                $this->setConnection( $value );
                break;
            
            // Adds the header
            default:
                
                $this->_headers[ $name ] = $value;
                break;
        }
    }
    
    /**
     * Adds request headers
     * 
     * @param   array   The headers to add, as key/value pairs
     * @return  void
     * @see     addHeader
     */
    public function addHeaders( array $headers )
    {
        // Process each header
        foreach( $headers as $key => $value ) {
            
            // Adds the current header
            $this->addHeader( $key, $value );
        }
    }
    
    /**
     * Gets the request URI
     * 
     * @return  Woops_Uniform_Ressource_Identifier  The URI object
     */
    public function getUri()
    {
        return $this->_uri;
    }
    
    /**
     * Gets the HTTP response
     * 
     * @return  Woops_Http_Response         The HTTP response object
     * @throws  Woops_Http_Client_Exception If the connection was not established
     */
    public function getResponse()
    {
        // Checks the connect flag
        if( !$this->_connected ) {
            
            // No connection
            throw new Woops_Http_Client_Exception(
                'The connection has not been established yet',
                Woops_Http_Client_Exception::EXCEPTION_NOT_CONNECTED
            );
        }
        
        // Checks if the reponse object already exist
        if( !is_object( $this->_response ) ) {
            
            // Creates the response object
            $this->_response = Woops_Http_Response::createResponseObject( $this->_socket );
        }
        
        // Returns the response object
        return $this->_response;
    }
    
    /**
     * Establish a socket connection with the current settings
     * 
     * @return  boolean                     Wether the connection was successfully established
     * @throws  Woops_Http_Client_Exception If the fsockopen() function is not available
     */
    public function connect()
    {
        // Checks if the fsockopen() function is available
        if( !function_exists( 'fsockopen' ) ) {
            
            // Error - No fsockopen()
            throw new Woops_Http_Client_Exception(
                'The PHP function fsockopen() is not available',
                Woops_Http_Client_Exception::EXCEPTION_NO_FSOCKOPEN
            );
        }
        
        // Creates a socket
        $this->_socket = fsockopen(
            $this->_uri->getHost(),
            $this->_uri->getPort(),
            $this->_errNo,
            $this->_errStr,
            $this->_timeout
        );
        
        // Checks for the socket
        if( !$this->_socket ) {
            
            // Connection error
            return false;
        }
        
        // Sets the connect flag
        $this->_connected = true;
        
        // Starts the request
        $request          = $this->_requestMethod
                          . ' '
                          . $this->_uri->getPath()
                          . ' HTTP/'
                          . $this->_protocolVersion
                          . self::$_CRLF;
        
        // Adds the host name, if we are in HTTP 1.1
        if( $this->_protocolVersion === 1.1 ) {
            
            $request .= 'Host: ' . $this->_uri->getHost() . self::$_CRLF;
        }
        
        // Adds the user agent
        $request .= 'User-Agent: ' . $this->_userAgent . self::$_CRLF;
        
        // Adds the headers
        foreach( $this->_headers as $key => $value ) {
            
            // Adds the header
            $request .= $key . ': ' . $value . self::$_CRLF;
        }
        
        // Adds the connection type
        $request .= 'Connection: ' . $this->_connection . self::$_CRLF;
        
        // Checks if we have cookies
        if( count( $this->_cookies ) ) {
            
            // Adds the cookie header
            $request .= 'Cookie: ' . implode( ';', array_keys( $this->_cookies ) );
        }
        
        // End of the headers
        $request .= self::$_CRLF;
        
        // Writes the request in the socket
        fwrite( $this->_socket, $request );
    }
    
    /**
     * Gets the error number
     * 
     * @return  int The error number
     */
    public function getErrorCode()
    {
        return $this->_errNo;
    }
    
    /**
     * Gets the error message
     * 
     * @return  int The error message
     */
    public function getErrorMessage()
    {
        return $this->_errStr;
    }
}
