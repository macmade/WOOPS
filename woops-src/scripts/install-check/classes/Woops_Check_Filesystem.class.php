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
 * Woops filesystem check (PHP 4 class, as we want this to run on all boxes)
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 */
class Woops_Check_Filesystem
{
    var $hasErrors   = false;
    
    var $hasWarnings = false;
    
    var $files       = array(
        'cache'                    => array( 'status' => '', 'message' => '', 'writecheck' => false, 'type' => 'directory' ),
        'cache/classes'            => array( 'status' => '', 'message' => '', 'writecheck' => true,  'type' => 'directory' ),
        'config'                   => array( 'status' => '', 'message' => '', 'writecheck' => true,  'type' => 'directory' ),
        'resources'                => array( 'status' => '', 'message' => '', 'writecheck' => false, 'type' => 'directory' ),
        'resources/templates'      => array( 'status' => '', 'message' => '', 'writecheck' => false, 'type' => 'directory' ),
        'uploads'                  => array( 'status' => '', 'message' => '', 'writecheck' => true,  'type' => 'directory' ),
        'temp'                     => array( 'status' => '', 'message' => '', 'writecheck' => true,  'type' => 'directory' ),
        'woops-src/config.ini.php' => array( 'status' => '', 'message' => '', 'writecheck' => false, 'type' => 'file' )
    );
    
    function Woops_Check_Filesystem()
    {
        // Checks if we have a Windows path, separated by slashes (PHP CGI with Apache under Windows)
        if( substr( $_SERVER[ 'SCRIPT_FILENAME' ], 1, 2 ) === ':/' ) {
            
            // Gets the path parts
            $pathInfo = explode( '/', $_SERVER[ 'SCRIPT_FILENAME' ] );
            
        } else {
            
            // Gets the path parts
            $pathInfo = explode( DIRECTORY_SEPARATOR, $_SERVER[ 'SCRIPT_FILENAME' ] );
        }
        
        array_pop( $pathInfo );
        array_pop( $pathInfo );
        array_pop( $pathInfo );
        array_pop( $pathInfo );
        
        $rootDir = implode( DIRECTORY_SEPARATOR, $pathInfo ) . DIRECTORY_SEPARATOR;
        
        foreach( $this->files as $key => $value ) {
            
            $realPath = str_replace( '/', DIRECTORY_SEPARATOR, $key );
            
            $status   = ( $value[ 'type' ] === 'file' ) ? $this->checkFile( $rootDir . $realPath, $value[ 'writecheck' ] ) : $this->checkDir( $rootDir . $realPath, $value[ 'writecheck' ] );
            
            if( $status == 'ERROR' ) {
                
                $this->files[ $key ][ 'message' ] = 'This ' . $value[ 'type' ] . ' does not exist.<br /><br />Full path is: ' . $rootDir . $key;
                
                $this->hasErrors                 = true;
                
            } elseif( $status == 'WARNING' ) {
                
                $this->files[ $key ][ 'message' ] = 'This ' . $value[ 'type' ] . ' is not writeable.<br /><br />Full path is: ' . $rootDir . $key;
                
                $this->hasWarnings               = true;
                
            } elseif( $status == 'SUCCESS' && $value[ 'writecheck' ] ) {
                
                $this->files[ $key ][ 'message' ] = 'This ' . $value[ 'type' ] . ' exists and is writeable.';
                
            } elseif( $status == 'SUCCESS' ) {
                
                $this->files[ $key ][ 'message' ] = 'This ' . $value[ 'type' ] . ' exists.';
            }
            
            $this->files[ $key ][ 'status' ] = $status;
        }
    }
    
    function getStatus()
    {
        $out = array();
        
        foreach( $this->files as $key => $value ) {
            
            $status  = $value[ 'status' ];
            
            $out[] = '<div class="box-' . strtolower( $status ) . '">';
            $out[] = '<h4>' . $key . '</h4>';
            $out[] = '<div class="status">Status: ' . $status . '</div>';
            $out[] = '<div>' . $value[ 'message' ] . '</div>';
            $out[] = '</div>';
        }
        
        return implode( chr( 10 ), $out );
    }
    
    function checkDir( $path, $writeCheck )
    {
        if( !file_exists( $path ) || !is_dir( $path ) ) {
            
            return 'ERROR';
        }
        
        if( $writeCheck && !is_writable( $path ) ) {
            
            return 'WARNING';
        }
        
        return 'SUCCESS';
    }
    
    function checkFile( $path, $writeCheck )
    {
        if( !file_exists( $path ) || !is_file( $path ) ) {
            
            return 'ERROR';
        }
        
        if( $writeCheck && !is_writable( $path ) ) {
            
            return 'WARNING';
        }
        
        return 'SUCCESS';
    }
}
