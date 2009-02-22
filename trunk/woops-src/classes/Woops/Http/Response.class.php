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
 * HTTP response class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Http
 */
class Woops_Http_Response
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The HTTP reponse codes
     */
    const CODE_CONTINUE                        = 100;
    const CODE_SWITCHING_PROTOCOLS             = 101;
    const CODE_OK                              = 200;
    const CODE_CREATED                         = 201;
    const CODE_ACCEPTED                        = 202;
    const CODE_NON_AUTHORITATIVE_INFORMATION   = 203;
    const CODE_NO_CONTENT                      = 204;
    const CODE_RESET_CONTENT                   = 205;
    const CODE_PARTIAL_CONTENT                 = 206;
    const CODE_MULTIPLE_CHOICES                = 300;
    const CODE_MOVED_PERMANENTLY               = 301;
    const CODE_FOUND                           = 302;
    const CODE_SEE_OTHER                       = 303;
    const CODE_NOT_MODIFIED                    = 304;
    const CODE_USE_PROXY                       = 305;
    const CODE_TEMPORARY_REDIRECT              = 307;
    const CODE_BAD_REQUEST                     = 400;
    const CODE_UNAUTHORIZED                    = 401;
    const CODE_PAYMENT_REQUIRED                = 402;
    const CODE_FORBIDDEN                       = 403;
    const CODE_NOT_FOUND                       = 404;
    const CODE_METHOD_NOT_ALLOWED              = 405;
    const CODE_NOT_ACCEPTABLE                  = 406;
    const CODE_PROXY_AUTHENTIFICATION_REQUIRED = 407;
    const CODE_REQUEST_TIMEOUT                 = 408;
    const CODE_CONFLICT                        = 409;
    const CODE_GONE                            = 410;
    const CODE_LENGTH_REQUIRED                 = 411;
    const CODE_PRECONDITION_FAILED             = 412;
    const CODE_REQUEST_ENTITY_TOO_LARGE        = 413;
    const CODE_REQUEST_URI_TOO_LONG            = 414;
    const CODE_UNSUPPORTED_MEDIA_TYPE          = 415;
    const CODE_REQUESTED_RAnGE_NOT_SATISFIABLE = 416;
    const CODE_EXPECTATION_FAILED              = 417;
    const CODE_INTERNAL_SERVER_ERROR           = 500;
    const CODE_NOT_IMPLEMENTED                 = 501;
    const CODE_BAD_GATEWAY                     = 502;
    const CODE_SERVICE_UNAVAILABLE             = 503;
    const CODE_GATEWAY_TIMEOUT                 = 504;
    const CODE_HTTP_VERSION_NOT_SUPPORTED      = 505;
    const CODE_BANDWIDTH_LIMIT_EXCEEDED        = 509;
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic = false;
    
    /**
     * The string utilities
     */
    protected static $_str     = NULL;
    
    /**
     * The HTTP response codes, with their messages
     */
    protected static $_codes   = array(
        
        // Informational
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        // Client error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server error
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );

    /**
     * The HTTP response code
     */
    protected $_code           = 0;

    /**
     * The HTTP response headers
     */
    protected $_headers        = array();

    /**
     * The HTTP response body
     */
    protected $_body           = '';

    /**
     * The raw HTTP response body
     */
    protected $_rawBody        = '';
    
    /**
     * The version of the HTTP protocol
     */
    protected $_httpVersion    = 1.1;
    
    /**
     * The cookies (from the 'Set-Cookie' header)
     */
    protected $_cookies        = array();
    
    /**
     * Class constructor
     * 
     * The constructor cannot be called from outside this class. Please use the
     * createResponseObject() static method to get an instance.
     * 
     * @param   int                             The HTTP response code
     * @param   array                           The HTTP response headers, as key/value pairs
     * @param   string                          The HTTP raw response body
     * @param   string                          The HTTP response body
     * @param   number                          The HTTP protocol version
     * @return  void
     * @throws  Woops_Http_Response_Exception   If the HTTP response code is invalid
     */
    protected function __construct( $code, array $headers, $rawBody, $body, $httpVersion )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Code and version should be numbers
        $code        = ( int )$code;
        $httpVersion = ( float )$httpVersion;
        
        // Ensures the response code is valid
        if( !isset( self::$_codes[ $code ] ) ) {
            
            // Invalid HTTP response code
            throw new Woops_Http_Response_Exception(
                'Invalid HTTP code (' . $code . ')',
                Woops_Http_Response_Exception::EXCEPTION_INVALID_CODE
            );
        }
        
        // Stores the response informations
        $this->_code        = $code;
        $this->_headers     = $headers;
        $this->_httpVersion = $httpVersion;
        $this->_rawBody     = $rawBody;
        $this->_body        = $body;
        
        // Checks for the 'Set-Cookie' header
        if( isset( $headers[ 'Set-Cookie' ] ) ) {
            
            // Process each cookie
            foreach( $headers[ 'Set-Cookie' ] as $cookie ) {
                
                // Creates a cookie object
                $cookie                               = Woops_Http_Cookie::createCookieObject( trim( $cookie ) );
                
                // Stores the cookie object
                $this->_cookies[ $cookie->getName() ] = $cookie;
            }
        }
    }
    
    /**
     * Returns the HTTP response as a string
     * 
     * @return  string  The full HTTP response
     */
    public function __toString()
    {
        // New line character
        $CRLF     = self::$_str->CR . self::$_str->LF;
        
        // Status line
        $response = 'HTTP/'
                  . $this->_httpVersion
                  . ' '
                  . $this->_code
                  . ' '
                  . self::$_codes[ $this->_code ]
                  . $CRLF;
        
        // Process the headers
        foreach( $this->_headers as $key => $value ) {
            
            // Adds the current header
            $response .= $key
                      .  ': '
                      . $value
                      . $CRLF;
        }
        
        // End of the headers
        $response .= $CRLF;
        
        // Adds the body
        $response .= $this->_rawBody;
        
        // Returns the response as a string
        return $response;
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
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Creates a response object from a string
     * 
     * @param   resource                        The HTTP socket from which to read
     * @return  Woops_Http_Response             The HTTP response object
     * @throws  Woops_Http_Response_Exception   If the HTTP status line is invalid
     * @throws  Woops_Http_Response_Exception   If the transfer-encoding is set and is not recognized
     */
    public static function createResponseObject( $socket )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Checks if we have a resource
        if( !is_resource( $socket ) ) {
            
            // Invalid status line
            throw new Woops_Http_Response_Exception(
                'Passed argument must be a valid resource',
                Woops_Http_Response_Exception::EXCEPTION_INVALID_RESOURCE
            );
        }
        
        // New line character
        $CRLF    = self::$_str->CR . self::$_str->LF;
        
        // Checks if we have data to read
        if( feof( $socket ) ) {
            
            // Nothing to read
            throw new Woops_Http_Response_Exception(
                'No more data to read in the resource',
                Woops_Http_Response_Exception::EXCEPTION_NO_DATA
            );
        }
        
        // Gets the status line
        $status       = fgets( $socket );
        
        // Status parts
        $statusParts  = explode( ' ', $status );
        
        // Checks the status parts
        if( count( $statusParts ) < 2 ) {
            
            // Invalid status line
            throw new Woops_Http_Response_Exception(
                'Invalid HTTP status line (' . $status . ')',
                Woops_Http_Response_Exception::EXCEPTION_INVALID_HTTP_STATUS
            );
        }
        
        // HTTP version and code
        $version = str_replace( 'HTTP/', '', $statusParts[ 0 ] );
        $code    = $statusParts[ 1 ];
        
        // Storage for the headers
        $headers = array();
        
        // Reads each line of the socket to find the headers
        while( !feof( $socket ) ) {
            
            // Reads a line
            $line = fgets( $socket );
            
            // Checks for the end of the headers
            if( $line === $CRLF ) {
                
                // Ends of the headers
                break;
            }
            
            // Gets the header name and value
            $header = explode( ':', $line );
            
            // Name and value of the header
            $name   = trim( $header[ 0 ] );
            $value  = ( isset( $header[ 1 ] ) ) ? trim( $header[ 1 ] ) : '';
            
            // Special processing for the 'Set-Cookie' header, which can appears multiple times
            if( $name === 'Set-Cookie' ) {
                
                // Checks if the storage array exists
                if( !isset( $headers[ $name ] ) ) {
                    
                    // Creates the storage array for the cookies
                    $headers[ $name ] = array();
                }
                
                // Adds the cookie
                $headers[ $name ][] = $value;
                
            } else {
                
                // Adds the current header
                $headers[ $name ] = $value;
            }
        }
        
        // Storage for the response body
        $body    = '';
        $rawBody = '';
        
        // Checks if the transfer encoding is set to chunked or if we have a content length
        if( isset( $headers[ 'Transfer-Encoding' ] ) && $headers[ 'Transfer-Encoding' ] === 'chunked' ) {
            
            // Reads until the end of the socket
            while( !feof( $socket ) ) {
                
                // Gets the chunk size
                $chunkSize = fgets( $socket );
                
                // Data to read
                $left = hexdec( trim( $chunkSize ) );
                
                // Reads until the end of the socket, or until the end of the chunk length
                while( !feof( $socket ) && $left > 0 ) {
                    
                    // Reads from the socket
                    $data     = fread( $socket, $left );
                    
                    // Adds the data
                    $body    .= $data;
                    $rawBody .= $chunkSize . $data;
                    
                    // Decrease the number of bytes to read
                    $left    -= strlen( $data );
                }
            }
            
        } elseif( isset( $headers[ 'Transfer-Encoding' ] ) ) {
            
            // Unrecognized transfer encoding
            throw new Woops_Http_Response_Exception(
                'Invalid transfer encoding (' . $headers[ 'Content-Encoding' ] . ')',
                Woops_Http_Response_Exception::EXCEPTION_INVALID_TRANSFER_ENCODING
            );
            
        } elseif( isset( $headers[ 'Content-Length' ] ) ) {
            
            // Data to read
            $left = $headers[ 'Content-Length' ];
            
            // Reads until the end of the socket, or until the end of the content length
            while( !feof( $socket ) && $left > 0 ) {
                
                // Reads from the socket
                $data     = fread( $socket, $left );
                
                // Adds the data
                $body    .= $data;
                $rawBody .= $data;
                
                // Decrease the number of bytes to read
                $left    -= strlen( $data );
            }
            
        } else {
            
            // Reads until the end of the socket
            while( !feof( $socket ) ) {
                
                // Reads from the socket
                $data    .= fread( $socket, 8192 );
                
                // Adds the data
                $body    .= $data;
                $rawBody .= $data;
            }
        }
        
        // Checks if the body is encoded
        if( isset( $headers[ 'Content-Encoding' ] ) ) {
            
            // Decodes the body
            $body = self::_decodeBody( $body, $headers[ 'Content-Encoding' ] );
        }
        
        // Creates the response object
        $response = new self(
            $code,
            $headers,
            $rawBody,
            $body,
            $version
        );
        
        // Returns the response object
        return $response;
    }
    
    /**
     * Process the body, if needed, accordingly to the 'content-encoding'
     * response headers.
     * 
     * @param   string                          The body
     * @param   string                          The value of the 'Content-Encoding' header
     * @return  string                          The processed body
     * @throws  Woops_Http_Response_Exception   If the transfer-encoding is set as chunked and if the chunked content is invalid
     * @throws  Woops_Http_Response_Exception   If the content-encoding is set as deflate and if the PHP function gzuncompress() is not available
     * @throws  Woops_Http_Response_Exception   If the content-encoding is set as gzip and if the PHP function gzinflate() is not available
     */
    protected static function _decodeBody( $body, $encoding )
    {
        // Checks if encoding type
        if( $encoding === 'deflate' ) {
            
            // Checks if the gzuncompress() function is available
            if( !function_exists( 'gzuncompress' ) ) {
                
                // Error - Cannot process the body
                throw new Woops_Http_Response_Exception(
                    'The PHP function \'gzuncompress()\' is not available',
                    Woops_Http_Response_Exception::EXCEPTION_NO_GZUNCOMPRESS
                );
            }
            
            // Uncompress the body
            $body = gzuncompress( $body );
            
        } elseif( $encoding === 'gzip' ) {
            
            // Checks if the gzuncompress() function is available
            if( !function_exists( 'gzinflate' ) ) {
                
                // Error - Cannot process the body
                throw new Woops_Http_Response_Exception(
                    'The PHP function \'gzinflate()\' is not available',
                    Woops_Http_Response_Exception::EXCEPTION_NO_GZINFLATE
                );
            }
            
            // Inflates the body
            $body = gzinflate( substr( $body, 10 ) );
        }
        
        // Returns the processed body
        return $body;
    }
    
    /**
     * Gets the HTTP response code
     * 
     * @return  int The HTTP response code
     */
    public function getCode()
    {
        return $this->_code;
    }
    
    /**
     * Gets the HTTP response message
     * 
     * @return  string  The HTTP response message
     */
    public function getMessage()
    {
        return self::$_codes[ $this->_code ];
    }
    
    /**
     * Gets the HTTP response headers
     * 
     * @return  array   The HTTP response headers
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    
    /**
     * Gets an HTTP response header
     * 
     * @param   name    The name of the response header
     * @return  mixed   The content of the response header if it exists, otherwise false
     */
    public function getHeader( $name )
    {
        return ( isset( $this->_headers[ $name ] ) ) ? $this->_headers[ $name ] : false;
    }
    
    /**
     * Gets the processed HTTP response body
     * 
     * @return  string  The processed HTTP response body
     */
    public function getBody()
    {
        return $this->_body;
    }
    
    /**
     * Gets the raw (unprocessed) HTTP response body
     * 
     * @return  string  The raw (unprocessed) HTTP response body
     */
    public function getRawBody()
    {
        return $this->_rawBody;
    }
    
    /**
     * Gets the HTTP protocol version
     * 
     * @return  float   The HTTP protocol version
     */
    public function getHttpVersion()
    {
        return $this->_httpVersion;
    }
    
    /**
     * Gets a cookie (from the 'Set-Cookie' header)
     * 
     * @param   string  The name of the cookie
     * @return  mixed   An instance of the Woops_Http_Cookie class if the cookie exists, otherwise NULL
     */
    public function getCookie( $name )
    {
        return ( isset( $this->_cookies[ $name ] ) ) ? $this->_cookies[ $name ] : NULL;
    }
    
    /**
     * Gets the cookies (from the 'Set-Cookie' header)
     * 
     * @return  array   An array with instances of the Woops_Http_Cookie class
     */
    public function getCookies()
    {
        return $this->_cookies;
    }
    
    /**
     * Sets a cookies (from the 'Set-Cookie' header)
     * 
     * @param   string  The name of the cookie
     * @return  boolean Wether the cookie has been set
     */
    public function setCookie( $name )
    {
        // Checks if the cookie exists
        if( isset( $this->_cookies[ $name ] ) ) {
            
            // Sets the cookie
            return $this->_cookies[ $name ];
        }
    }
    
    /**
     * Sets all the cookies (from the 'Set-Cookie' header)
     * 
     * @return  void
     */
    public function setCookies()
    {
        // Process all the cookies
        foreach( $this->_cookies as $cookie ) {
            
            // Sets the cookie
            $cookie->setCookie();
        }
    }
    
    /**
     * Checks if the HTTP response is informational
     * 
     * @return  boolean Wether the HTTP response is informational
     */
    public function isInformational()
    {
        return ( $this->_code >= 100 ) && ( $this->_code < 200 );
    }
    
    /**
     * Checks if the HTTP response is successful
     * 
     * @return  boolean Wether the HTTP response is successful
     */
    public function isSuccess()
    {
        return ( $this->_code >= 200 ) && ( $this->_code < 300 );
    }
    
    /**
     * Checks if the HTTP response is a redirection
     * 
     * @return  boolean Wether the HTTP response is a redirection
     */
    public function isRedirection()
    {
        return ( $this->_code >= 300 ) && ( $this->_code < 400 );
    }
    
    /**
     * Checks if the HTTP response is a client error
     * 
     * @return  boolean Wether the HTTP response is a client error
     */
    public function isClientError()
    {
        return ( $this->_code >= 400 ) && ( $this->_code < 500 );
    }
    
    /**
     * Checks if the HTTP response is a server error
     * 
     * @return  boolean Wether the HTTP response is a server error
     */
    public function isServerError()
    {
        return ( $this->_code >= 500 );
    }
}
