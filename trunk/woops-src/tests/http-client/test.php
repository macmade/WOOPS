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

// Plain text content
header( 'Content-Type: text/plain' );

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
