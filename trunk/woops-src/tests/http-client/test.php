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

// Includes the initialization script
require_once(
    __DIR__
  . DIRECTORY_SEPARATOR
  . '..'
  . DIRECTORY_SEPARATOR
  . '..'
  . DIRECTORY_SEPARATOR
  . 'init.inc.php'
);

// File encoding
declare( ENCODING = 'UTF-8' );

// Plain text content
header( 'Content-Type: text/plain' );

// Creates some cookies
$FOO = new Woops\Http\Cookie( 'foo', 'WOOPS-test-cookie-1' );
$BAR = new Woops\Http\Cookie( 'bar', 'WOOPS-test-cookie-2' );

// Sets the cookies
$FOO->set();
$BAR->set();

// Prints the POST data
print 'POST data ($_POST): ';
print_r( $_POST );

// Separator
print chr( 10 ) . '-----' . chr( 10 ) . chr( 10 );

// Prints the uploaded files
print 'Uploaded files ($_FILES): ';
print_r( $_FILES );

// Aborts the script
exit();
