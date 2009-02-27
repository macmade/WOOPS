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

/**
 * Woops PHP environment check (PHP 4 class, as we want this to run on all boxes)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 */
class Woops_Check_Environment
{
    var $hasErrors   = false;
    
    var $hasWarnings = false;
    
    var $checks      = array(
        'phpVersion' => array(
            'title'   => 'PHP version',
            'status'  => '',
            'success' => 'The running PHP version (<strong>{VERSION}</strong>) is able to run WOOPS.',
            'warning' => '',
            'error'   => 'The running PHP version (<strong>{VERSION}</strong>) is too low. The minimal required version is <strong>5.2.0</strong>.',
            'replace' => array()
        ),
        'zendCompat'  => array(
            'title'   => 'Zend Engine 1 compatibility',
            'status'  => '',
            'success' => 'The compatibility mode with Zend Engine 1 (PHP 4) is disabled.',
            'warning' => '',
            'error'   => 'The compatibility mode with Zend Engine 1 (PHP 4) is enabled.<br /><br />WOOPS cannot run with this setting turned on, as this will change the behavior of the object model. Please disable it.',
            'replace' => array()
        ),
        'errorReporting' => array(
            'title'   => 'Error reporting level',
            'status'  => '',
            'success' => 'The error reporting level is set to the maximum value.',
            'warning' => 'The error reporting level is too low.<br /><br />WOOPS sets the error reporting level at it\'s maximum value (E_ALL | E_STRICT, which is 8191). As every PHP error (even a simple notice) will result as a fatal error, when using WOOPS, please ensure this is not a problem for you.<br /><br />The current error reporting level is <strong>{LEVEL}</strong>.',
            'error'   => '',
            'replace' => array()
        ),
        'spl' => array(
            'title'   => 'SPL - Standard PHP Library',
            'status'  => '',
            'success' => 'The SPL classes and functions are available.',
            'warning' => '',
            'error'   => 'The SPL classes and functions are not available on your PHP installation.<br /><br />Please compile PHP with the SPL support.',
            'replace' => array()
        ),
        'reflection' => array(
            'title'   => 'Reflection',
            'status'  => '',
            'success' => 'The reflection classes are available.',
            'warning' => '',
            'error'   => 'The reflection classes are not available on your PHP installation.<br /><br />Please compile PHP with the reflection support.',
            'replace' => array()
        ),
        'simpleXml' => array(
            'title'   => 'Simple XML',
            'status'  => '',
            'success' => 'The Simple XML classes and functions are available.',
            'warning' => '',
            'error'   => 'The Simple XML classes and functions are not available on your PHP installation.<br /><br />Please compile PHP with the Simple XML support.',
            'replace' => array()
        ),
        'pdo' => array(
            'title'   => 'PDO - PHP Data Object',
            'status'  => '',
            'success' => 'The PDO class is available.<br /><br />Available drivers are: <strong>{DRIVERS}</strong>.',
            'warning' => 'The PDO class is not available on your PHP installation.<br /><br />That means you won\'t be able to use the default WOOPS database abstraction layer engine, but another one, like ADODB (included in thw WOOPS sources). Please ensure this is not a problem for you.',
            'error'   => '',
            'replace' => array()
        ),
        'fsockOpen' => array(
            'title'   => 'Socket connection',
            'status'  => '',
            'success' => 'The PHP fsockopen() function is available.',
            'warning' => '',
            'error'   => 'The PHP fsockopen() function is not available. This is required in order to enable the generation of AOP classes in the class cache.',
            'replace' => array()
        ),
    );
    
    function Woops_Check_Environment()
    {
        foreach( $this->checks as $key => $value ) {
            
            $checkMethod                      = 'check' . ucfirst( $key );
            $status                           = $this->$checkMethod( $this->checks[ $key ][ 'replace' ] );
            $this->checks[ $key ][ 'status' ] = $status;
            
            if( $status === 'ERROR' ) {
                
                $this->hasErrors = true;
                
            } elseif( $status === 'WARNING' ) {
                
                $this->hasWarnings = true;
            }
        }
    }
    
    function getStatus()
    {
        $out = array();
        
        foreach( $this->checks as $key => $value ) {
            
            $status  = $value[ 'status' ];
            $message = $value[ strtolower( $status ) ];
            
            foreach( $value[ 'replace' ] as $pattern => $replace ) {
                
                $message = str_replace( '{' . $pattern . '}', $replace, $message );
            }
            
            $out[] = '<div class="check-' . strtolower( $status ) . '">';
            $out[] = '<h4>' . $value[ 'title' ] . '</h4>';
            $out[] = '<div class="status">Status: ' . $status . '</div>';
            $out[] = '<div class="message">' . $message . '</div>';
            $out[] = '</div>';
        }
        
        return implode( chr( 10 ), $out );
    }
    
    function checkPhpVersion( &$replace )
    {
        $version              = phpversion();
        $replace[ 'VERSION' ] = $version;
        
        if( version_compare( $version, '5.2.0', '<' ) ) {
            
            return 'ERROR';
        }
        
        return 'SUCCESS';
    }
    
    function checkZendCompat()
    {
        return ( ini_get( 'zend.ze1_compatibility_mode' ) ) ? 'ERROR' : 'SUCCESS';
    }
    
    function checkErrorReporting( &$replace )
    {
        $reporting          = error_reporting();
        $replace[ 'LEVEL' ] = $reporting;
        
        if( defined( 'E_STRICT' ) && $reporting == ( E_ALL | E_STRICT ) ) {
            
            return 'SUCCESS';
        }
        
        return 'WARNING';
    }
    
    function checkSpl( &$replace )
    {
        return ( function_exists( 'spl_autoload' ) && is_callable( 'spl_autoload' ) ) ? 'SUCCESS' : 'ERROR';
    }
    
    function checkReflection( &$replace )
    {
        return ( class_exists( 'Reflection' ) ) ? 'SUCCESS' : 'ERROR';
    }
    
    function checkSimpleXml( &$replace )
    {
        return ( class_exists( 'SimpleXMLElement' ) ) ? 'SUCCESS' : 'ERROR';
    }
    
    function checkPdo( &$replace )
    {
        if( class_exists( 'PDO' ) ) {
            
            $replace[ 'DRIVERS' ] = implode( ', ', pdo_drivers() );
            
            return 'SUCCESS';
        }
        
        return 'WARNING';
    }
    
    function checkFsockOpen( &$replace )
    {
        return ( function_exists( 'fsockopen' ) && is_callable( 'fsockopen' ) ) ? 'SUCCESS' : 'ERROR';
    }
}
