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
 * Woops PHP environment check (PHP 4 class, as we want this to run on all boxes)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 */
class Woops_Check_Configuration
{
    var $checks = array(
        'short_open_tag'                 => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major portability issues.',
        ),
        'asp_tags'                       => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major portability issues.',
        ),
        'allow_call_time_pass_reference' => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature will be removed in PHP 5.3.',
        ),
        'safe_mode'                      => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature will be removed in PHP 5.3.',
        ),
        'register_globals'               => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major security and portability issues, and will be removed in PHP 5.3.',
        ),
        'magic_quotes_gpc'               => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major security and portability issues, and will be removed in PHP 5.3.',
        ),
        'magic_quotes_runtime'           => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major security and portability issues, and will be removed in PHP 5.3.',
        ),
    );
    
    function Woops_Check_Configuration()
    {
        foreach( $this->checks as $key => $value ) {
            
            if( ini_get( $key ) != $value[ 'value' ] ) {
                
                $this->checks[ $key ][ 'status' ] = 'WARNING';
                
            } else {
                
                $this->checks[ $key ][ 'status' ] = 'SUCCESS';
            }
            
        }
    }
    
    function getStatus()
    {
        $out = array();
        
        foreach( $this->checks as $key => $value ) {
            
            $status  = $value[ 'status' ];
            $message = str_replace( '{VAR}', '<strong>' . $key . '</strong>', $value[ strtolower( $status ) ] );
            
            $out[] = '<div class="check-' . strtolower( $status ) . '">';
            $out[] = '<h4>' . $key . '</h4>';
            $out[] = '<div class="status">Status: ' . $status . '</div>';
            $out[] = '<div class="message">' . $message . '</div>';
            $out[] = '</div>';
        }
        
        return implode( chr( 10 ), $out );
    }
}
