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

// Includes the initialization script
require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'init.inc.php' );

// Plain text content
header( 'Content-Type: text/plain' );

// WOOPS environment object
$ENV    = Woops_Core_Env_Getter::getInstance();

// URL of the test file
$URL    = ( ( $ENV->HTTPS ) ? 'https://' : 'http://' )
        . $ENV->HTTP_HOST
        . $ENV->getSourceWebPath( 'tests/http-client/test.php' );

// Creates an HTTP client
$CLIENT = new Woops_Http_Client( $URL );

// Adds some POST data
$CLIENT->addPostData(
    'test',
    array(
        'foo' => true,
        'bar' => array(
            'fooBar' => true
        )
    )
);

// Uploads a file
$CLIENT->addFile(
    'someFile',
    $ENV->getPath( 'woops-mod://Cms/resources/templates/woops-default/lixado.png' )
);

// Establish the connection
$CLIENT->connect();

// Writes the HTTP response body
print $CLIENT->getResponse()->getBody();

// Separator
print chr( 10 ) . '-----' . chr( 10 ) . chr( 10 );

// Prints the cookies from the response
print 'Cookies from the HTTP response: ';
print_r( $CLIENT->getResponse()->getCookies() );

// Aborts the script
exit();
