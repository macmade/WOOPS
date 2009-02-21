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
    const CONTINUE                        = 100;
    const SWITCHING_PROTOCOLS             = 101;
    const OK                              = 200;
    const CREATED                         = 201;
    const ACCEPTED                        = 202;
    const NON_AUTHORITATIVE_INFORMATION   = 203;
    const NO_CONTENT                      = 204;
    const RESET_CONTENT                   = 205;
    const PARTIAL_CONTENT                 = 206;
    const MULTIPLE_CHOICES                = 300;
    const MOVED_PERMANENTLY               = 301;
    const FOUND                           = 302;
    const SEE_OTHER                       = 303;
    const NOT_MODIFIED                    = 304;
    const USE_PROXY                       = 305;
    const TEMPORARY_REDIRECT              = 307;
    const BAD_REQUEST                     = 400;
    const UNAUTHORIZED                    = 401;
    const PAYMENT_REQUIRED                = 402;
    const FORBIDDEN                       = 403;
    const NOT_FOUND                       = 404;
    const METHOD_NOT_ALLOWED              = 405;
    const NOT_ACCEPTABLE                  = 406;
    const PROXY_AUTHENTIFICATION_REQUIRED = 407;
    const REQUEST_TIMEOUT                 = 408;
    const CONFLICT                        = 409;
    const GONE                            = 410;
    const LENGTH_REQUIRED                 = 411;
    const PRECONDITION_FAILED             = 412;
    const REQUEST_ENTITY_TOO_LARGE        = 413;
    const REQUEST_URI_TOO_LONG            = 414;
    const UNSUPPORTED_MEDIA_TYPE          = 415;
    const REQUESTED_RAnGE_NOT_SATISFIABLE = 416;
    const EXPECTATION_FAILED              = 417;
    const INTERNAL_SERVER_ERROR           = 500;
    const NOT_IMPLEMENTED                 = 501;
    const BAD_GATEWAY                     = 502;
    const SERVICE_UNAVAILABLE             = 503;
    const GATEWAY_TIMEOUT                 = 504;
    const HTTP_VERSION_NOT_SUPPORTED      = 505;
    const BANDWIDTH_LIMIT_EXCEEDED        = 509;
    
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
     * The HTTP response body (processed)
     */
    protected $_body           = '';

    /**
     * The HTTP response body (raw)
     */
    protected $_rawBody        = '';
    
    /**
     * The version of the HTTP protocol
     */
    protected $_httpVersion    = 1.1;
    
    /**
     * Class constructor
     * 
     * @param   int                             The HTTP response code
     * @param   array                           The HTTP response headers, as key/value pairs
     * @param   string                          The HTTP response body
     * @param   number                          The HTTP protocol version
     * @return  void
     * @throws  Woops_Http_Response_Exception   If the HTTP response code is invalid
     */
    public function __construct( $code, array $headers, $body = '', $httpVersion = 1.1 )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Code and version should be numbers
        $code    = ( int )$code;
        $version = ( float )$version;
        
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
        $this->_rawBody     = $body;
        
        // Process the body, if necessary
        $this->_body        = $this->_processBody();
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
     * Process the body, if needed, accordingly to the 'transfer-encoding'
     * and 'content-encoding' response headers.
     * 
     * @return  string                          The processed body
     * @throws  Woops_Http_Response_Exception   If the transfer-encoding is set as chunked and if the chunked content is invalid
     * @throws  Woops_Http_Response_Exception   If the content-encoding is set as deflate and if the PHP function gzuncompress() is not available
     * @throws  Woops_Http_Response_Exception   If the content-encoding is set as gzip and if the PHP function gzinflate() is not available
     */
    protected function _processBody()
    {
        // Checks if the 'transfer-encoding' header is set as 'chunked'
        if( isset( $this->_headers[ 'transfer-encoding' ] )
            && $this->_headers[ 'transfer-encoding' ] === 'chunked'
        ) {
            
            // New line character
            $CRLF     = self::$_str->CR . self::$_str->LF;
            
            // Gets each line of the chunked content
            $lines    = explode( $CRLF, $this->_rawBody );
            
            // Number of lines
            $linesNum = count( $lines );
            
            // Checks the number of lines
            if( $linesNum < 1  ) {
                
                // Invalid chunked content
                throw new Woops_Http_Response_Exception(
                    'Invalid chunked content',
                    Woops_Http_Response_Exception::EXCEPTION_INVALID_CHUNKED_CONTENT
                );
            }
            
            // Storage
            $body = '';
            
            // Process each line
            for( $i = 0; $i < $linesNum; $i++ ) {
                
                // Size of the chunk
                $size = hexdec( trim( $lines[ $i ] ) );
                
                // If the chunk size is 0, there is no data left
                if( $size === 0 ) {
                    
                    break;
                }
                
                // Checks for the data
                if( !isset( $lines[ $i + 1 ] ) ) {
                    
                    // Invalid chunked content
                    throw new Woops_Http_Response_Exception(
                        'Invalid chunked content',
                        Woops_Http_Response_Exception::EXCEPTION_INVALID_CHUNKED_CONTENT
                    );
                }
                
                // Chunk data
                $data = $lines[ $i + 1 ];
                
                // Checks the chunk size
                if( strlen( $data ) !== $size ) {
                    
                    // Invalid chunked content
                    throw new Woops_Http_Response_Exception(
                        'Invalid chunked content',
                        Woops_Http_Response_Exception::EXCEPTION_INVALID_CHUNKED_CONTENT
                    );
                }
                
                // Adds the data
                $body .= $data
                
                // Process the next chunk
                $i++;
            }
            
        } else {
            
            // Raw body
            $body = $this->_rawBody
        }
        
        // Checks if the 'transfer-encoding' header is set
        if( isset( $this->_headers[ 'content-encoding' ] )
            && $this->_headers[ 'content-encoding' ] === 'deflate'
        ) {
            
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
            
        } elseif( isset( $this->_headers[ 'content-encoding' ] )
            && $this->_headers[ 'content-encoding' ] === 'gzip'
        ) {
            
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
        return $body
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
