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
 * Woops filesystem check (PHP 4 class, as we want this to run on all boxes)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 */
class Woops_Check_Filesystem
{
    var $dirs = array(
        'cache'                => array( 'status' => '', 'message' => '', 'writecheck' => false ),
        'cache/classes'        => array( 'status' => '', 'message' => '', 'writecheck' => true ),
        'cache/config'         => array( 'status' => '', 'message' => '', 'writecheck' => true ),
        'resources'            => array( 'status' => '', 'message' => '', 'writecheck' => false ),
        'resources/templates'  => array( 'status' => '', 'message' => '', 'writecheck' => false ),
        'uploads'              => array( 'status' => '', 'message' => '', 'writecheck' => true ),
        'temp'                 => array( 'status' => '', 'message' => '', 'writecheck' => true )
    );
    
    function Woops_Check_Filesystem()
    {
        $rootDir = substr( $_SERVER[ 'SCRIPT_FILENAME' ], 0, -41 );
        
        foreach( $this->dirs as $key => $value ) {
            
            $key = str_replace( '/', DIRECTORY_SEPARATOR, $key );
            
            $status = $this->checkDir( $rootDir . $key, $value[ 'writecheck' ] );
            
            if( $status == 'ERROR' ) {
                
                $this->dirs[ $key ][ 'message' ] = 'This directory does not exist.<br /><br />Full path is: ' . $rootDir . $key;
                
            } elseif( $status == 'WARNING' ) {
                
                $this->dirs[ $key ][ 'message' ] = 'This directory is not writeable.<br /><br />Full path is: ' . $rootDir . $key;
                
            } elseif( $status == 'SUCCESS' && $value[ 'writecheck' ] ) {
                
                $this->dirs[ $key ][ 'message' ] = 'This directory exists and is writeable.';
                
            } elseif( $status == 'SUCCESS' ) {
                
                $this->dirs[ $key ][ 'message' ] = 'This directory exists.';
            }
            
            $this->dirs[ $key ][ 'status' ] = $status;
        }
    }
    
    function getStatus()
    {
        $out = array();
        
        foreach( $this->dirs as $key => $value ) {
            
            $status  = $value[ 'status' ];
            
            $out[] = '<div class="check-' . strtolower( $status ) . '">';
            $out[] = '<h4>' . $key . '</h4>';
            $out[] = '<div class="status">Status: ' . $status . '</div>';
            $out[] = '<div class="message">' . $value[ 'message' ] . '</div>';
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
}
