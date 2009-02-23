<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
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
            'about'   => 'Allow the &lt;? tag.  Otherwise, only &lt;?php and &lt;script&gt; tags are recognized.<br />NOTE: Using short tags should be avoided when developing applications or libraries that are meant for redistribution, or deployment on PHP servers which are not under your control, because short tags may not be supported on the target server. For portable, redistributable code, be sure not to use short tags.'
        ),
        'asp_tags'                       => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major portability issues.',
            'about'   => 'Allow ASP-style &lt;% %&gt; tags.'
        ),
        'allow_call_time_pass_reference' => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature will be removed in PHP 5.3.',
            'about'   => 'Whether to enable the ability to force arguments to be passed by reference at function call time.<br />This method is deprecated and is likely to be unsupported in future versions of PHP/Zend.  The encouraged method of specifying which arguments should be passed by reference is in the function declaration.  You\'re encouraged to try and turn this option Off and make sure your scripts work properly with it in order to ensure they will work with future versions of the language (you will receive a warning each time you use this feature, and the argument will be passed by value instead of by reference).'
        ),
        'safe_mode'                      => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature will be removed in PHP 5.3.',
            'about'   => 'Whether to enable PHP\'s safe mode. '
        ),
        'register_globals'               => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major security and portability issues, and will be removed in PHP 5.3.',
            'about'   => 'Whether or not to register the EGPCS variables as global variables.  You may want to turn this off if you don\'t want to clutter your scripts\' global scope with user data.  This makes most sense when coupled with track_vars - in which case you can access all of the GPC variables through the $HTTP_*_VARS[], variables.<br /><br />You should do your best to write your scripts so that they do not require register_globals to be on;  Using form variables as globals can easily lead to possible security problems, if the code is not very well thought of.'
        ),
        'register_long_arrays'           => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major portability issues, and will be removed in PHP 5.3.',
            'about'   => 'Whether or not to register the old-style input arrays, HTTP_GET_VARS and friends.  If you\'re not using them, it\'s recommended to turn them off, for performance reasons.'
        ),
        'magic_quotes_gpc'               => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major security and portability issues, and will be removed in PHP 5.3.',
            'about'   => 'Magic quotes for incoming GET/POST/Cookie data.'
        ),
        'magic_quotes_runtime'           => array(
            'value'   => false,
            'success' => 'The {VAR} directive is turned <strong>OFF</strong>.',
            'warning' => 'The {VAR} directive is turned <strong>ON</strong>.<br /><br />This feature can cause major security and portability issues, and will be removed in PHP 5.3.',
            'about'   => 'Magic quotes for runtime-generated data, e.g. data from SQL, from exec(), etc.'
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
            $out[] = '<div class="about">' . $value[ 'about' ] . '</div>';
            $out[] = '</div>';
        }
        
        return implode( chr( 10 ), $out );
    }
}
