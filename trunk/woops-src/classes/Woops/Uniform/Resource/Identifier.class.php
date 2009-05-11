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
namespace Woops\Uniform\Resource;

/**
 * URI class (RFC-3986)
 * 
 * A Uniform Resource Identifier (URI) provides a simple and extensible means
 * for identifying a resource.  This specification of URI syntax and semantics
 * is derived from concepts introduced by the World Wide Web global information
 * initiative, whose use of these identifiers dates from 1990 and is described
 * in "Universal Resource Identifiers in WWW" - RFC1630.
 * 
 * The generic URI syntax consists of a hierarchical sequence of components
 * referred to as the scheme, authority, path, query, and fragment.
 * 
 * <code>
 * URI       = scheme ":" hier-part [ "?" query ] [ "#" fragment ]
 * 
 * hier-part = "//" authority path-abempty
 *           / path-absolute
 *           / path-rootless
 *           / path-empty
 * </code>
 * 
 * The scheme and path components are required, though the path may be empty
 * (no characters).  When authority is present, the path must either be empty
 * or begin with a slash ("/") character.  When authority is not present, the
 * path cannot begin with two slash characters ("//").  These restrictions
 * result in five different ABNF rules for a path, only one of which will
 * match any given URI reference.
 * 
 * The following are two example URIs and their component parts:
 * 
 * <code>
 *  foo://example.com:8042/over/there?name=ferret#nose
 *  \_/   \______________/\_________/ \_________/ \__/
 *   |           |            |            |        |
 * scheme     authority      path        query   fragment
 *   |   _____________________|__
 *  / \ /                        \
 *  urn:example:animal:ferret:nose
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Uniform.Resource
 */
class Identifier extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The registered URI schemes (IANA)
     */
    const SCHEME_AAA             = 'aaa';
    const SCHEME_AAAS            = 'aaas';
    const SCHEME_ACAP            = 'acap';
    const SCHEME_CAP             = 'cap';
    const SCHEME_CID             = 'cid';
    const SCHEME_CRID            = 'crid';
    const SCHEME_DATA            = 'data';
    const SCHEME_DAV             = 'dav';
    const SCHEME_DICT            = 'dict';
    const SCHEME_DNS             = 'dns';
    const SCHEME_FAX             = 'fax';
    const SCHEME_FILE            = 'file';
    const SCHEME_FTP             = 'ftp';
    const SCHEME_GO              = 'go';
    const SCHEME_GOPHER          = 'gopher';
    const SCHEME_H323            = 'h323';
    const SCHEME_HTTP            = 'http';
    const SCHEME_HTTPS           = 'https';
    const SCHEME_IAX             = 'iax';
    const SCHEME_ICAP            = 'icap';
    const SCHEME_IM              = 'im';
    const SCHEME_IMAP            = 'imap';
    const SCHEME_INFO            = 'info';
    const SCHEME_IPP             = 'ipp';
    const SCHEME_IRIS            = 'iris';
    const SCHEME_IRIS_BEEP       = 'iris.beep';
    const SCHEME_IRIS_XPC        = 'iris.xpc';
    const SCHEME_IRIS_XPCS       = 'iris.xpcs';
    const SCHEME_IRIS_LWZ        = 'iris.lwz';
    const SCHEME_LDAP            = 'ldap';
    const SCHEME_MAILTO          = 'mailto';
    const SCHEME_MID             = 'mid';
    const SCHEME_MODEM           = 'modem';
    const SCHEME_MSRP            = 'msrp';
    const SCHEME_MSRPS           = 'msrps';
    const SCHEME_MTQP            = 'mtqp';
    const SCHEME_MUPDATE         = 'mupdate';
    const SCHEME_NEWS            = 'news';
    const SCHEME_NFS             = 'nfs';
    const SCHEME_NNTP            = 'nntp';
    const SCHEME_OPAQUELOCKTOKEN = 'opaquelocktoken';
    const SCHEME_POP             = 'pop';
    const SCHEME_PRES            = 'pres';
    const SCHEME_RTSP            = 'rtsp';
    const SCHEME_SERVICE         = 'service';
    const SCHEME_SHTTP           = 'shttp';
    const SCHEME_SIEVE           = 'sieve';
    const SCHEME_SIP             = 'sip';
    const SCHEME_SIPS            = 'sips';
    const SCHEME_SNMP            = 'snmp';
    const SCHEME_SOAP_BEEP       = 'soap.beep';
    const SCHEME_SOAP_BEEPS      = 'soap.beeps';
    const SCHEME_TAG             = 'tag';
    const SCHEME_TEL             = 'tel';
    const SCHEME_TELNET          = 'telnet';
    const SCHEME_TFTP            = 'tftp';
    const SCHEME_THISMESSAGE     = 'thismessage';
    const SCHEME_TIP             = 'tip';
    const SCHEME_TV              = 'tv';
    const SCHEME_URN             = 'urn';
    const SCHEME_VEMMI           = 'vemmi';
    const SCHEME_XMLRPC_BEEP     = 'xmlrpc.beep';
    const SCHEME_XMLRPC_BEEPS    = 'xmlrpc.beeps';
    const SCHEME_XMPP            = 'xmpp';
    const SCHEME_Z39_50R         = 'z39.50r';
    const SCHEME_Z39_50S         = 'z39.50s';
    
    /**
     * The registered URI schemes (IANA)
     */
    protected static $_schemes = array(
        'aaa'             => true, // Diameter Protocol - RFC-3588
        'aaas'            => true, // Diameter Protocol with Secure Transport - RFC-3588
        'acap'            => true, // application configuration access protocol - RFC-2244
        'cap'             => true, // Calendar Access Protocol - RFC-4324
        'cid'             => true, // content identifier - RFC-2392
        'crid'            => true, // TV-Anytime Content Reference Identifier - RFC-4078
        'data'            => true, // data - RFC-2397
        'dav'             => true, // dav - RFC-4918
        'dict'            => true, // dictionary service protocol - RFC-2229
        'dns'             => true, // Domain Name System - RFC-4501
        'fax'             => true, // fax - RFC-3966
        'file'            => true, // Host-specific file names - RFC-1738
        'ftp'             => true, // File Transfer Protocol - RFC-1738
        'go'              => true, // go - RFC-3368
        'gopher'          => true, // The Gopher Protocol - RFC-4266
        'h323'            => true, // H.323 - RFC-3508
        'http'            => true, // Hypertext Transfer Protocol - RFC-2616
        'https'           => true, // Hypertext Transfer Protocol Secure - RFC-2818
        'iax'             => true, // Inter-Asterisk eXchange Version 2
        'icap'            => true, // Internet Content Adaptation Protocol - RFC-3507
        'im'              => true, // Instant Messaging - RFC-3860
        'imap'            => true, // internet message access protocol - RFC-5092
        'info'            => true, // Information Assets with Identifiers in Public Namespaces - RFC-4452
        'ipp'             => true, // Internet Printing Protocol - RFC-3510
        'iris'            => true, // Internet Registry Information Service - RFC-3981
        'iris.beep'       => true, // iris.beep - RFC-3983
        'iris.xpc'        => true, // iris.xpc - RFC-4992
        'iris.xpcs'       => true, // iris.xpcs - RFC-4992
        'iris.lwz'        => true, // iris.lwz - RFC-4993
        'ldap'            => true, // Lightweight Directory Access Protocol - RFC-4516
        'mailto'          => true, // Electronic mail address - RFC-2368
        'mid'             => true, // message identifier - RFC-2392
        'modem'           => true, // modem - RFC-3966
        'msrp'            => true, // Message Session Relay Protocol - RFC-4975
        'msrps'           => true, // Message Session Relay Protocol Secure - RFC-4975
        'mtqp'            => true, // Message Tracking Query Protocol - RFC-3887
        'mupdate'         => true, // Mailbox Update (MUPDATE) Protocol - RFC-3656
        'news'            => true, // USENET news
        'nfs'             => true, // network file system protocol - RFC-2224
        'nntp'            => true, // USENET news using NNTP access
        'opaquelocktoken' => true, // opaquelocktokent - RFC-4918
        'pop'             => true, // Post Office Protocol v3 - RFC-2384
        'pres'            => true, // Presence - RFC-3859
        'rtsp'            => true, // real time streaming protocol - RFC-2326
        'service'         => true, // service location - RFC-2609
        'shttp'           => true, // Secure Hypertext Transfer Protocol - RFC-2660
        'sieve'           => true, // ManageSieve Protocol
        'sip'             => true, // session initiation protocol - RFC-3261
        'sips'            => true, // secure session initiation protocol - RFC-3261
        'snmp'            => true, // Simple Network Management Protocol - RFC-4088
        'soap.beep'       => true, // soap.beep - RFC-3288
        'soap.beeps'      => true, // soap.beeps - RFC-3288
        'tag'             => true, // tag - RFC-4151
        'tel'             => true, // telephone - RFC-3966
        'telnet'          => true, // Reference to interactive sessions - RFC-4248
        'tftp'            => true, // Trivial File Transfer Protocol - RFC-3617
        'thismessage'     => true, // multipart/related relative reference resolution - RFC-2557
        'tip'             => true, // Transaction Internet Protocol - RFC-2371
        'tv'              => true, // TV Broadcasts - RFC-2838
        'urn'             => true, // Uniform Resource Names (click for registry) - RFC-2141
        'vemmi'           => true, // versatile multimedia interface - RFC-2122
        'xmlrpc.beep'     => true, // xmlrpc.beep - RFC-3529
        'xmlrpc.beeps'    => true, // xmlrpc.beeps - RFC-3529
        'xmpp'            => true, // Extensible Messaging and Presence Protocol - RFC-5122
        'z39.50r'         => true, // Z39.50 Retrieval - RFC-2056
        'z39.50s'         => true  // Z39.50 Session - RFC-2056
    );
    
    /**
     * The default ports number for the available schemes (IANA)
     */
    protected static $_ports   = array(
        'aaa'             => 3868,
        'aaas'            => 3868,
        'acap'            => 674,
        'cap'             => 1026,
        'dict'            => 2628,
        'ftp'             => 21,
        'gopher'          => 70,
        'http'            => 80,
        'https'           => 443,
        'iax'             => 4569,
        'icap'            => 1344,
        'imap'            => 143,
        'ipp'             => 631,
        'iris.beep'       => 702,
        'ldap'            => 389,
        'mtqp'            => 1038,
        'mupdate'         => 3905,
        'news'            => 2009,
        'nfs'             => 2049,
        'nntp'            => 119,
        'pop'             => 110,
        'rtsp'            => 554,
        'sip'             => 5060,
        'sips'            => 5061,
        'snmp'            => 161,
        'soap.beep'       => 605,
        'soap.beeps'      => 605,
        'telnet'          => 23,
        'tftp'            => 69,
        'tip'             => 3372,
        'vemmi'           => 575,
        'xmlrpc.beep'     => 602,
        'xmlrpc.beeps'    => 602,
        'xmpp'            => 5269,
        'z39.50r'         => 210,
        'z39.50s'         => 210
    );
    
    /**
     * The URI scheme
     */
    protected $_scheme         = '';
    
    /**
     * The URI host
     */
    protected $_host           = '';
    
    /**
     * The URI port number
     */
    protected $_port           = 0;
    
    /**
     * The URI username
     */
    protected $_user           = '';
    
    /**
     * The URI user password
     */
    protected $_pass           = '';
    
    /**
     * The URI path
     */
    protected $_path           = '';
    
    /**
     * The URI query
     */
    protected $_query          = '';
    
    /**
     * THe URI fragment
     */
    protected $_fragment       = '';
    
    /**
     * The URI query, as key/value pairs
     */
    protected $_queryParts    = array();
    
    /**
     * Class constructor
     * 
     * @param   string                                      The URI
     * @return  void
     * @throws  Woops\Uniform\Resource\Identifier\Exception If the URI is invalid
     * @throws  Woops\Uniform\Resource\Identifier\Exception If the URI scheme is invalid
     * @see     _setQueryParts
     */
    public function __construct( $uri = '' )
    {
        // Checks if an URI is given
        if( $uri ) {
            
            // Parses the URI
            $infos = parse_url( ( string )$uri );
            
            // Checks for a scheme
            if( !isset( $infos[ 'scheme' ] ) ) {
                
                // Invalid URI
                throw new Identifier\Exception(
                    'Invalid URI (' . $uri . ')',
                    Identifier\Exception::EXCEPTION_INVALID_URI
                );
            }
            
            // Checks for a valid scheme
            if( !isset( self::$_schemes[ $infos[ 'scheme' ] ] ) ) {
                
                // Invalid scheme
                throw new Identifier\Exception(
                    'Invalid URI scheme (' . $infos[ 'scheme' ] . ')',
                    Identifier\Exception::EXCEPTION_INVALID_SCHEME
                );
            }
            
            // Stores the scheme
            $this->_scheme = $infos[ 'scheme' ];
            
            // Stores the other informations
            $this->_host     = ( isset( $infos[ 'host' ] ) )     ? $infos[ 'host' ]     : '';
            $this->_port     = ( isset( $infos[ 'port' ] ) )     ? $infos[ 'port' ]     : 0;
            $this->_user     = ( isset( $infos[ 'user' ] ) )     ? $infos[ 'user' ]     : '';
            $this->_pass     = ( isset( $infos[ 'pass' ] ) )     ? $infos[ 'pass' ]     : '';
            $this->_path     = ( isset( $infos[ 'path' ] ) )     ? $infos[ 'path' ]     : '';
            $this->_query    = ( isset( $infos[ 'query' ] ) )    ? $infos[ 'query' ]    : '';
            $this->_fragment = ( isset( $infos[ 'fragment' ] ) ) ? $infos[ 'fragment' ] : '';
            
            // Creates the query parts array
            $this->_setQueryParts();
        }
    }
    
    /**
     * Gets the complete URI as a string
     * 
     * @return  string  The complete URI
     * @see     getAuthority
     */
    public function __toString()
    {
        // Checks for a scheme
        if( !$this->_scheme ) {
            
            // Nothing to return
            return '';
        }
        
        // Starts the URI
        $uri = $this->_scheme . ':';
        
        // Checks if we have a host
        if( $this->_host ) {
            
            // Builds the authority
            $uri .= '//' . $this->getAuthority();
        }
        
        // Checks if we have a path
        if( $this->_path ) {
            
            // Adds the path
            $uri .= $this->_path;
        }
        
        // Checks if we have a query
        if( $this->_query ) {
            
            // Adds the query
            $uri .= '?' . $this->_query;
        }
        
        // Checks if we have a fragment
        if( $this->_fragment ) {
            
            // Adds the fragment
            $uri .= '#' . $this->_fragment;
        }
        
        // Returns the URI
        return $uri;
    }
    
    /**
     * Sets the query parts array from the query string
     * 
     * @return  void
     */
    protected function _setQueryParts()
    {
        // Checks if we have a query
        if( $this->_query ) {
            
            // Gets each part
            $parts = explode( '&', $this->_query );
            
            // Process each part
            foreach( $parts as $part ) {
                
                // Gets the name and the value
                $subParts = explode( '=', $part );
                
                // Adds the query element
                $this->_queryParts[ $subParts[ 0 ] ] = ( isset( $subParts[ 1 ] ) ) ? $subParts[ 1 ] : '';
            }
        }
    }
    
    /**
     * Sets the URI scheme
     * 
     * @param   string                                      The URI scheme
     * @return  void
     * @throws  Woops\Uniform\Resource\Identifier\Exception If the URI scheme is invalid
     */
    public function setScheme( $value )
    {
        // Checks for a valid scheme
        if( !isset( self::$_schemes[ $value ] ) ) {
            
            // Invalid scheme
            throw new Identifier\Exception(
                'Invalid URI scheme (' . $value . ')',
                Identifier\Exception::EXCEPTION_INVALID_SCHEME
            );
        }
        
        // Stores the scheme
        $this->_scheme = ( string )$value;
    }
    
    /**
     * Sets the URI host
     * 
     * @param   string  The URI host
     * @return  void
     */
    public function setHost( $value )
    {
        $this->_host = ( string )$value;
    }
    
    /**
     * Sets the URI port number
     * 
     * @param   int The URI port number
     * @return  void
     */
    public function setPort( $value )
    {
        $this->_port = ( int )$value;
    }
    
    /**
     * Sets the URI username
     * 
     * @param   string  The URI username
     * @return  void
     */
    public function setUser( $value )
    {
        $this->_user = ( string )$value;
    }
    
    /**
     * Sets the URI user password
     * 
     * @param   string  The URI user password
     * @return  void
     */
    public function setPass( $value )
    {
        $this->_pass = ( string )$value;
    }
    
    /**
     * Sets the URI authority
     * 
     * According to RFC-3986, th URI authority can contains a username, a user
     * password, a host, and a port number. Only the host is required.
     * 
     * For instance:
     * 
     * <code>
     * user:password@host:port
     * </code
     * 
     * @param   string  The URI authority
     * @return  void
     */
    public function setAuthority( $value )
    {
        // Value has to be a string
        $value = ( string )$value;
        
        // Checks for a user part
        if( $userPos = strpos( $value, '@' ) ) {
            
            // Gets the user infos
            $userInfos   = explode( ':', substr( $value, 0, $userPos ) );
            $value       = substr( $value, $userPos + 1 );
            
            // Adds the username
            $this->_user = $userInfos[ 0 ];
            
            // Adds the user password, if present
            $this->_pass = ( isset( $userInfos[ 1 ] ) ) ? $userInfos[ 1 ] : '';
        }
        
        // Gets the host and port parts
        $parts = explode( ':', $value );
        
        // Stores the host
        $this->_host = $parts[ 0 ];
        
        // Stores the port number, if present
        $this->_port = ( isset( $parts[ 1 ] ) ) ? $parts[ 1 ] : 0;
    }
    
    /**
     * Sets the URI path
     * 
     * @param   string  The URI path
     * @return  void
     */
    public function setPath( $value )
    {
        $this->_path = ( string )$value;
    }
    
    /**
     * Sets the URI query
     * 
     * @param   mixed   Either a query string (without the leading '?') or an array as key/value pairs
     * @return  void
     * @see     _setQueryParts
     */
    public function setQuery( $value )
    {
        // Checks if the passed argument is an array
        if( is_array( $value ) ) {
            
            // Stores the query parts array
            $this->_queryParts = $value;
            
            // Storage
            $query             = '';
            
            // Process each entry of the query parts array
            foreach( $value as $queryName => $queryValue ) {
                
                // Adds the current query element
                $query .= $queryName . '=' . $queryValue . '&';
            }
            
            // Stores the query string
            $this->_query = substr( $query, -1 );
            
        } else {
            
            // Stores the query
            $this->_query = ( string )$value;
            
            // Creates the query parts array
            $this->_setQueryParts();
        }
    }
    
    /**
     * Sets the URI fragment
     * 
     * @param   string  The URI fragment
     * @return  void
     */
    public function setFragment( $value )
    {
        $this->_fragment = ( string )$value;
    }
    
    /**
     * Gets the URI scheme
     * 
     * @return  string  The URI scheme
     */
    public function getScheme()
    {
        return $this->_scheme;
    }
    
    /**
     * Gets the URI host
     * 
     * @return  string  The URI host
     */
    public function getHost()
    {
        return $this->_host;
    }
    
    /**
     * Gets the URI username
     * 
     * @return  string  The URI username
     */
    public function getUser()
    {
        return $this->_user;
    }
    
    /**
     * Gets the URI user password
     * 
     * @return  string  The URI user password
     */
    public function getPass()
    {
        return $this->_pass;
    }
    
    /**
     * Gets the URI port number
     * 
     * @param   boolean If no port is set, add the default port for the scheme, if available
     * @return  string  The URI port number
     */
    public function getPort( $addDefaultPort = true )
    {
        // Checks if the port number is set
        if( !$this->_port && $addDefaultPort && isset( self::$_ports[ $this->_scheme ] ) ) {
            
            // Return the default port
            return self::$_ports[ $this->_scheme ];
        }
        
        // Returns the port number
        return $this->_port;
    }
    
    /**
     * Gets the URI authority
     * 
     * @return  string  The URI authority
     */
    public function getAuthority()
    {
        // Storage
        $authority = '';
        
        // Checks if we have a host
        if( $this->_host ) {
            
            // Checks for a user part
            if( $this->_user && $this->_pass ) {
                
                // Adds the username and password
                $authority .= $this->_user . ':' . $this->_pass . '@';
                
            } elseif( $this->_user ) {
                
                // Adds the username
                $authority .= $this->_user . '@';
            }
            
            // Adds the host
            $authority .= $this->_host;
            
            // Checks if we have a port number
            if( $this->_port ) {
                
                // Adds the port number
                $authority .= ':' . $this->_port;
            }
        }
        
        // Returns the URI authority
        return $authority;
    }
    
    /**
     * Gets the URI path
     * 
     * @return  string  The URI path
     */
    public function getPath()
    {
        return $this->_path;
    }
    
    /**
     * Gets the URI query
     * 
     * @return  string  The URI query
     */
    public function getQuery()
    {
        return $this->_query;
    }
    
    /**
     * Gets the URI query parts
     * 
     * @return  array   The URI query parts, as key/value pairs
     */
    public function getQueryParts()
    {
        return $this->_queryParts;
    }
    
    /**
     * Gets the URI fragment
     * 
     * @return  string  The URI fragment
     */
    public function getFragment()
    {
        return $this->fragment;
    }
}
