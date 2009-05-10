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
  . 'woops-src'
  . DIRECTORY_SEPARATOR
  . 'init.inc.php'
);

// File encoding
declare( ENCODING = 'UTF-8' );

// Gets the environment object
$ENV = Woops\Core\Env\Getter::getInstance();

// Checks if the WOOPS configuration file exists
if( !$ENV->getPath( 'config/woops.ini.php' ) ) {
    
    // Redirects to the WOOPS installer
    header( 'Location: ' . $ENV->getSourceWebPath( 'scripts/install/' ) );
}

// Gets the current page
$PAGE = Woops\Page\Engine::getInstance()->getPageObject();

// Prints the current page
print $PAGE->writePage();

// Cleanup
unset( $ENV, $PAGE );
