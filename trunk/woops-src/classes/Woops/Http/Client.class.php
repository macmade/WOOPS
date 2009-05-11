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
namespace Woops\Http;

/**
 * HTTP client class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Http
 */
class Client extends \Woops\Core\Event\Dispatcher
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The available HTTP request methods
     */
    const METHOD_CONNECT                = 'CONNECT';
    const METHOD_DELETE                 = 'DELETE';
    const METHOD_GET                    = 'GET';
    const METHOD_HEAD                   = 'HEAD';
    const METHOD_OPTIONS                = 'OPTIONS';
    const METHOD_POST                   = 'POST';
    const METHOD_PUT                    = 'PUT';
    const METHOD_TRACE                  = 'TRACE';
    
    /**
     * The available HTTP authentication types
     */
    const NONE                          = 'NONE';
    const AUTH_BASIC                    = 'BASIC';
    
    /**
     * The available HTTP protocol versions
     */
    const HTTP_VERSION_1_0              = '1.0';
    const HTTP_VERSION_1_1              = '1.1';
    
    /**
     * The POST encoding types 
     */
    const ENCTYPE_FORM_URL_ENCODED      = 'application/x-www-form-urlencoded';
    const ENCTYPE_MULTIPART_FORM_DATA   = 'multipart/form-data';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic          = false;
    
    /**
     * The string utilities
     */
    protected static $_str              = NULL;
    
    /**
     * The array utilities
     */
    protected static $_array            = NULL;
    
    /**
     * The file types class
     */
    protected static $_fileTypes        = NULL;
    
    /**
     * The environment object
     */
    protected static $_env              = NULL;
    
    /**
     * The newline character (CR-LF)
     */
    protected static $_CRLF             = false;
    
    /**
     * The boundary for the multipart/form-data encoding type
     */
    protected static $_boundary         = '';
    
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
        'NONE'   => true,
        'BASIC'  => true
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
    protected $_requestMethod           = 'GET';
    
    /**
     * The HTTP authentication type
     */
    protected $_authType                = 'NONE';
    
    /**
     * The encoding type
     */
    protected $_encType                 = '';
    
    /**
     * The HTTP authentication username
     */
    protected $_authUser                = '';
    
    /**
     * The HTTP authentication password
     */
    protected $_authPassword            = '';
    
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
     * Whether the connection is established
     */
    protected $_connected               = false;
    
    /**
     * Raw data for the request body
     */
    protected $_rawData                 = '';
    
    /**
     * The data to send through the POST method
     */
    protected $_postData                = array();
    
    /**
     * The files to upload through the POST method
     */
    protected $_files                   = array();
    
    /**
     * Class constructor
     * 
     * @param   string  The URI to connect to
     * @param   string  The request method (one of the METHOD_XXX constant)
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
          . self::WOOPS_VERSION
          . '-'
          . self::WOOPS_VERSION_SUFFIX
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
        self::$_str       = \Woops\Helpers\StringUtilities::getInstance();
        
        // Gets the instance of the string utilities
        self::$_array     = \Woops\Helpers\ArrayUtilities::getInstance();
        
        // Gets the instance of the file types class
        self::$_fileTypes = \Woops\File\Types::getInstance();
        
        // Gets the instance of the environment object
        self::$_env       = \Woops\Core\Env\Getter::getInstance();
        
        // Sets the newline character (CR-LF)
        self::$_CRLF      = self::$_str->CR . self::$_str->LF;
        
        // Sets the boundary for multipart/form-data
        self::$_boundary  = 'WOOPS-' . self::$_str->uniqueId();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Builds the request headers
     * 
     * @return  string  The request headers
     */
    protected function _buildRequestHeaders()
    {
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
        
        // Checks for the 'Keep-Alive' parameter
        if( $this->_keepAlive ) {
            
            // Adds the 'Keep-Alive' header
            $request .= 'Keep-Alive: ' . $this->_keepAlive . self::$_CRLF;
        }
        
        // Adds the connection type
        $request .= 'Connection: ' . $this->_connection . self::$_CRLF;
        
        // Checks if we have cookies
        if( count( $this->_cookies ) ) {
            
            // Adds the cookie header
            $request .= 'Cookie: ' . implode( ';', array_keys( $this->_cookies ) );
        }
        
        // Checks for an authentication type
        if( $this->_authType && $this->_authType !== 'NONE' ) {
            
            // Adds the authorization header
            $request .= 'Authorization: '
                     .  $this->_createAuthenticationHeader
                     . self::$_CRLF;
        }
        
        // Checks if we have a content type
        if( $this->_encType && $this->_encType === self::ENCTYPE_MULTIPART_FORM_DATA ) {
            
            // Adds the content type header, with the multipart boundary
            $request .= 'Content-Type: ' . $this->_encType . '; boundary=' . self::$_boundary . self::$_CRLF;
            
        } elseif( $this->_encType ) {
            
            // Adds the content type header
            $request .= 'Content-Type: ' . $this->_encType . self::$_CRLF;
        }
        
        // Adds the headers
        foreach( $this->_headers as $key => $value ) {
            
            // Adds the header
            $request .= $key . ': ' . $value . self::$_CRLF;
        }
        
        // Returns the request headers
        return $request;
    }
    
    /**
     * Builds the request body (for POST and PUT request methods)
     * 
     * @return  string  The request body
     */
    protected function _buildRequestBody()
    {
        // Do we have raw data?
        if( $this->_rawData ) {
            
            // Returns the raw data
            return $this->_rawData;
        }
        
        // Do we have POST data?
        if( !count( $this->_postData ) ) {
            
            // Nothing to send
            return '';
        }
        
        // Checks the encoding type
        if( $this->_encType === self::ENCTYPE_FORM_URL_ENCODED ) {
            
            // URL encode the POST data
            return http_build_query( $this->_postData, '', '&' );
            
        } elseif( $this->_encType === self::ENCTYPE_MULTIPART_FORM_DATA ) {
            
            // Gets the flat list of the POST data
            $postData = self::$_array->flatten( $this->_postData );
            
            // Storage
            $body = '';
            
            // Process each item of the POST data
            foreach( $postData as $key => $value ) {
                
                // Encodes the current item as multipart
                $body .= $this->_encodeAsMultipart( $key, $value );
            }
            
            // Process each file to upload
            foreach( $this->_files as $name => $infos ) {
                
                $body .= $this->_encodeFileAsMultipart( $name, basename( $infos[ 0 ] ), $infos[ 1 ], $infos[ 2 ]  );
            }
            
            // Adds the boundary
            $body .= '--' . self::$_boundary . self::$_CRLF;
            
            // Return the body
            return $body;
        }
        
        // Unrecognized encoding type - Do not send anything
        return '';
    }
    
    /**
     * Encodes a multipart item (RFC-2387)
     * 
     * @param   string  The name of the item
     * @param   string  The value of the item
     * @return  string  The item encoded as multipart
     */
    protected function _encodeAsMultipart( $name, $value )
    {
        // Creates the multipart item
        $part = '--'
              . self::$_boundary
              . self::$_CRLF
              . 'Content-Disposition: form-data; name="'
              . $name
              . '"'
              . self::$_CRLF
              . self::$_CRLF
              . $value
              . self::$_CRLF;
        
        // Returns the multipart item
        return $part;
    }
    
    /**
     * Encodes a file as a multipart item (RFC-2387)
     * 
     * @param   string  The name of the file (as in the $_FILES array)
     * @param   string  The file name
     * @param   string  The mime-type of the file
     * @param   string  The file content
     * @return  string  The item encoded as multipart
     */
    protected function _encodeFileAsMultipart( $name, $fileName, $mimeType, $data )
    {
        // Creates the multipart item
        $part = '--'
              . self::$_boundary
              . self::$_CRLF
              . 'Content-Disposition: form-data; name="'
              . $name
              . '"; filename="'
              . $fileName
              . '"'
              . self::$_CRLF
              . 'Content-Type: '
              . $mimeType
              . self::$_CRLF
              . self::$_CRLF
              . $data
              . self::$_CRLF;
        
        // Returns the multipart item
        return $part;
    }
    
    /**
     * Creates the value of the authentication header
     * 
     * @return  string  The value of the authentication header
     */
    protected function _createAuthenticationHeader()
    {
        // Checks the autentication type
        if( $this->_authType === self::AUTH_BASIC ) {
            
            // Username and password are encoded in base 64
            return 'Basic ' . base64_encode( $this->_authUser . ':' . $this->_authPassword );
        }
    }
    
    /**
     * Sets the HTTP request URI
     * 
     * @param   string                              The URI
     * @return  Woops\Uniform\Resource\Identifier   The URI object
     * @throws  Woops\Http\Client\Exception         If the connection has already been established
     */
    public function setUri( $uri )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Creates and stores the URI object
        $this->_uri = new \Woops\Uniform\Resource\Identifier( $uri );
        
        // Returns the URI object
        return $this->_uri;
    }
    
    /**
     * Sets the HTTP request method
     * 
     * @param   string                      The HTTP request method (should be one of the METHOD_XXX constant)
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     * @throws  Woops\Http\Client\Exception If the request method is invalid
     */
    public function setRequestMethod( $method )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Converts to uppercase
        $method = strtoupper( $method );
        
        // Checks if the request method is valid
        if( !isset( self::$_requestMethods[ $method ] ) ) {
            
            // Invalid request method
            throw new Client\Exception(
                'Invalid HTTP request method (' . $method . ')',
                Client\Exception::EXCEPTION_INVALID_REQUEST_METHOD
            );
        }
        
        // Sets the request method
        $this->_requestMethod = $method;
        
        // Checks if we have an encoding type, for POST method
        if( $method === self::METHOD_POST && !$this->_encType ) {
            
            // Sets the encoding type
            $this->setEncodingType( self::ENCTYPE_FORM_URL_ENCODED );
        }
    }
    
    /**
     * Sets the encoding type
     * 
     * @param   string  The encoding type
     * @return  void
     */
    public function setEncodingType( $type )
    {
        $this->_encType = ( string )$type;
    }
    
    /**
     * Sets the HTTP authentication type
     * 
     * @param   string                      The HTTP authentication type (should be one of the AUTH_XXX constant)
     * @param   string                      The HTTP authentication username
     * @param   string                      The HTTP authentication password
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     * @throws  Woops\Http\Client\Exception If the authentication type is invalid
     */
    public function setAuthentication( $type, $username, $password )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Converts to uppercase
        $type = strtoupper( $type );
        
        // Checks if the request method is valid
        if( !isset( self::$_authTypes[ $type ] ) ) {
            
            // Invalid request method
            throw new Client\Exception(
                'Invalid HTTP authentication type (' . $type . ')',
                Client\Exception::EXCEPTION_INVALID_AUTH_TYPE
            );
        }
        
        // Sets the authentication type, username and password
        $this->_authType     = $type;
        $this->_authUser     = ( string )$username;
        $this->_authPassword = ( string )$password;
    }
    
    /**
     * Sets the HTTP protocol version
     * 
     * @param   string                      The HTTP protocol version (should be one of the HTTP_VERSION_XXX constant)
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     * @throws  Woops\Http\Client\Exception If the protocol version is invalid
     */
    public function setProtocolVersion( $version )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Converts to floating point
        $version = ( float )$version;
        
        // Checks if the request method is valid
        if( !isset( self::$_protocolVersions[ ( string )$version ] ) ) {
            
            // Invalid request method
            throw new Client\Exception(
                'Invalid HTTP protocol version (' . $version . ')',
                Client\Exception::EXCEPTION_INVALID_PROTOCOL_VERSION
            );
        }
        
        // Sets the protocol version
        $this->_protocolVersion = $version;
    }
    
    /**
     * Sets the connection timeout
     * 
     * @param   int                         The connection timeout
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function setTimeout( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Sets the connection timeout
        $this->_timeout = ( int )$value;
    }
    
    /**
     * Sets the connection type
     * 
     * @param   string                      The connection type (keep-alive, close)
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function setConnectionType( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Sets the connection type
        $this->_connection = ( string )$value;
    }
    
    /**
     * Sets the user-agent
     * 
     * @param   string                      The user agent
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function setUserAgent( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Sets the user-agent
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
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     * @see     setConnection
     */
    public function setKeepAlive( $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
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
     * @param   mixed                       Either a string, or a Woops\Http\Cookie object
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function addCookie( $cookie )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Checks if the passed argument is a cookie object
        if( is_object( $cookie ) && $cookie instanceof Cookie ) {
            
            // Stores the cookie object
            $this->_cookies[ $cookie->getName() ] = $cookie;
            
        } else {
            
            // Gets the cookies
            $cookies = explode( ';', $cookie );
            
            // Process each cookie
            foreach( $cookies as $cookie ) {
                
                // Creates a new cookie object
                $cookie = Cookie::createCookieObject( $cookie );
                
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
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function addHeader( $name, $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
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
     * Sets the raw data for the request body
     * 
     * If the raw data is set, the HTTP client will ignore the POST data set
     * with the addPostData() method and the files added with the addFile()
     * method, so only use this if you exactly know what you are doing, and if
     * there is no other way to do it.
     * 
     * @param   string  The raw data for the request body
     * @param   string  An optionnal encoding type
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function setRawData( $data, $encoding = '' )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Do we have an encoding?
        if( $encoding ) {
            
            // Sets the encoding
            $this->setEncodingType( $encoding );
        }
        
        // Stores the raw data
        $this->_rawData = ( string )$data;
    }
    
    /**
     * Adds data to send throught the POST method
     * 
     * @param   string  The name of the value to set
     * @param   mixed   The value to set (can be a sub-array)
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function addPostData( $name, $value )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Sets the request method to POST
        $this->setRequestMethod( self::METHOD_POST );
        
        // Name must be a string
        $name = ( string )$name;
        
        // Checks if the value is an array
        if( is_array( $value ) ) {
            
            // Does the storage place already exists?
            if( !isset( $this->_postData[ $name ] ) || !is_array( $this->_postData[ $name ] ) ) {
                
                // Creates the storage array
                $this->_postData[ $name ] = array();
            }
            
            // Adds the new data
            $this->_postData[ $name ] = array_merge_recursive( $this->_postData[ $name ], $value );
            
        } else {
            
            // Adds the data
            $this->_postData[ $name ] = $value;
        }
    }
    
    /**
     * Adds a file to send throught the POST method
     * 
     * @param   string                      The name of the file, in the $_FILES array
     * @param   string                      The path of the file to send
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     * @throws  Woops\Http\Client\Exception If the file does not exist
     * @throws  Woops\Http\Client\Exception If the file is not readable
     */
    public function addFile( $name, $path )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Checks if the file exists
        if( !file_exists( $path ) ) {
            
            // The file does not exist
            throw new Client\Exception(
                'No such file (' . $path . ')',
                Client\Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the file is readable
        if( !file_exists( $path ) ) {
            
            // The file is not readable
            throw new Client\Exception(
                'Unreadable file (' . $path . ')',
                Client\Exception::EXCEPTION_FILE_NOT_READABLE
            );
        }
        
        // Sets the request method to POST
        $this->setRequestMethod( self::METHOD_POST );
        
        // Tries to get a mime-type
        $mimeType = self::$_fileTypes->getMimeType( $path );
        
        // Checks for a mime-type
        if( !$mimeType ) {
            
            // Default is octet-stream
            $mimeType = 'application/octet-stream';
        }
        
        // Stores the file name
        $this->_files[ $name ] = array(
            $path,
            $mimeType,
            file_get_contents( $path )
        );
    }
    
    /**
     * Gets the HTTP response
     * 
     * @return  Woops\Http\Response         The HTTP response object
     * @throws  Woops\Http\Client\Exception If the connection was not established
     */
    public function getResponse()
    {
        // Checks the connect flag
        if( !$this->_connected ) {
            
            // No connection
            throw new Client\Exception(
                'The connection has not been established yet',
                Client\Exception::EXCEPTION_NOT_CONNECTED
            );
        }
        
        // Checks if the reponse object already exist
        if( !is_object( $this->_response ) ) {
            
            // Creates the response object
            $this->_response = Response::createResponseObject( $this->_socket );
        }
        
        // Returns the response object
        return $this->_response;
    }
    
    /**
     * Establish a socket connection with the current settings
     * 
     * @return  boolean                     Whether the connection was successfully established
     * @throws  Woops\Http\Client\Exception If the fsockopen() function is not available
     */
    public function connect()
    {
        // Checks if the fsockopen() function is available
        if( !function_exists( 'fsockopen' ) ) {
            
            // Error - No fsockopen()
            throw new Client\Exception(
                'The PHP function fsockopen() is not available',
                Client\Exception::EXCEPTION_NO_FSOCKOPEN
            );
        }
        
        // Dispatch the event to the listeners
        $this->dispatchEvent( Client\Event::EVENT_CONNECT );
        
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
        
        // Checks if we have files to upload
        if( $this->_requestMethod === self::METHOD_POST && count( $this->_files ) ) {
            
            // Sets the encoding type to multipart
            $this->setEncodingType( self::ENCTYPE_MULTIPART_FORM_DATA );
        }
        
        // Writes the request headers in the socket
        fwrite( $this->_socket, $this->_buildRequestHeaders() );
        
        // Creates the request body if necessary
        $body    = ( $this->_requestMethod === self::METHOD_POST || $this->_requestMethod === self::METHOD_PUT ) ? $this->_buildRequestBody() : '';
        
        // Do we have a body?
        if( $body ) {
            
            // Adds the content-length header
            fwrite( $this->_socket, 'Content-Length: ' . strlen( $body ) . self::$_CRLF );
            
            // End of the headers
            fwrite( $this->_socket, self::$_CRLF );
            
            // Writes the request body in the socket
            fwrite( $this->_socket, $body );
            
        } else {
            
            // End of the headers
            fwrite( $this->_socket, self::$_CRLF );
        }
        
        // Connection was established
        return true;
    }
    
    /**
     * Checks if the connection has been established
     * 
     * @return  boolean True if the connection was established
     */
    public function isConnected()
    {
        return $this->_connected;
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
    
    /**
     * Gets the request URI
     * 
     * @return  Woops\Uniform\Resource\Identifier  The URI object
     */
    public function getUri()
    {
        return $this->_uri;
    }
    
    /**
     * Gets the request method
     * 
     * @return  string  The request methods
     */
    public function getRequestMethod()
    {
        return $this->_requestMethod;
    }
    
    /**
     * Gets the encoding type
     * 
     * @return  string  The encoding type
     */
    public function getEncodingType()
    {
        return $this->_encType;
    }
    
    /**
     * Gets the authentication type
     * 
     * @return  string  The authentication type
     */
    public function getAuthType()
    {
        return $this->_authUser;
    }
    
    /**
     * Gets the authentication user
     * 
     * @return  string  The authentication user
     */
    public function getAuthUser()
    {
        return $this->_authType;
    }
    
    /**
     * Gets the authentication password
     * 
     * @return  string  The authentication password
     */
    public function getAuthPassword()
    {
        return $this->_authPassword;
    }
    
    /**
     * Gets the protocol version
     * 
     * @return  float   The protocol version type
     */
    public function getProtocolVersion()
    {
        return $this->_protocolVersion;
    }
    
    /**
     * Gets the user-agent
     * 
     * @return  string  The user-agent
     */
    public function getUserAgent()
    {
        return $this->_userAgent;
    }
    
    /**
     * Gets the connection type
     * 
     * @return  string  The connection type
     */
    public function getConnectionType()
    {
        return $this->_connection;
    }
    
    /**
     * Gets the keep-alive value
     * 
     * @return  int The keep-alive value
     */
    public function getKeepAlive()
    {
        return $this->_keepAlive;
    }
    
    /**
     * Gets the connection timeout
     * 
     * @return  int The connection timeout
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }
    
    /**
     * Gets a header
     * 
     * @param   string  The name of the header
     * @return  mixed   The value of the header if it's set, otherwise false
     */
    public function getHeader( $name )
    {
        return ( isset( $this->_headers[ $name ] ) ) ? $this->_headers[ $name ] : false;
    }
    
    /**
     * Gets the headers
     * 
     * @return  array   An array with all the headers
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    
    /**
     * Gets a cookie
     * 
     * @param   string  The name of the cookie
     * @return  mixed   The value of the header if it's set, otherwise NULL
     */
    public function getCookie( $name )
    {
        return ( isset( $this->_cookies[ $name ] ) ) ? $this->_cookies[ $name ] : NULL;
    }
    
    /**
     * Gets the cookies
     * 
     * @return  array   An array with all the cookies
     */
    public function getCookies()
    {
        return $this->_cookies;
    }
    
    /**
     * Gets the raw data for the request body
     * 
     * @return  string  The raw data for the request body
     */
    public function getRawData()
    {
        return $this->_rawData;
    }
    
    /**
     * Gets the POST data
     * 
     * @return  array   An array with the POST data
     */
    public function getPostData()
    {
        return $this->_postData;
    }
    
    /**
     * Gets the files that will be sent
     * 
     * @return  array   An array with the files (the key is the name (as in the $_FILES array), the first entry is the file path, the second entry the mime-type, and the third entry the file content)
     */
    public function getFiles()
    {
        return $this->_files;
    }
    
    /**
     * Gets a file that will be sent
     * 
     * @param   string  The name of the file, in the $_FILES array
     * @return  array   An array with the file informations (the first entry is the file path, the second entry the mime-type, and the third entry the file content)
     */
    public function getFile( $name )
    {
        return ( isset( $this->_files[ $name ] ) ) ? $this->_files[ $name ] : false;
    }
    
    /**
     * Removes a header
     * 
     * @param   string                      The name of the header
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function removeHeader( $name )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Removes the header
        unset( $this->_headers[ $name ] );
    }
    
    /**
     * Removes all headers
     * 
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function removeHeaders()
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Removes all headers
        $this->_headers = array();
    }
    
    /**
     * Removes a cookie
     * 
     * @param   string                      The name of the cookie
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function removeCookie( $name )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Removes the cookie
        unset( $this->_cookies[ $name ] );
    }
    
    /**
     * Removes all cookies
     * 
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function removeCookies()
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Removes all cookies
        $this->_cookies = array();
    }
    
    /**
     * Removes a file
     * 
     * @param   string                      The name of the file, in the $_FILES array
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function removeFile( $name )
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Removes the file
        unset( $this->_files[ $name ] );
    }
    
    /**
     * Removes all files
     * 
     * @return  void
     * @throws  Woops\Http\Client\Exception If the connection has already been established
     */
    public function removeFiles()
    {
        // Checks the connect flag
        if( $this->_connected ) {
            
            // Connection has been established
            throw new Client\Exception(
                'The connection has already been established',
                Client\Exception::EXCEPTION_CONNECTED
            );
        }
        
        // Removes all files
        $this->_files = array();
    }
}
