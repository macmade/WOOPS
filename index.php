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

// Includes the initialization script
require_once(
    dirname( __FILE__ )
  . DIRECTORY_SEPARATOR
  . 'woops-src'
  . DIRECTORY_SEPARATOR
  . 'init.inc.php'
);

// Gets the environment object
$ENV = Woops_Core_Env_Getter::getInstance();

// Checks if the WOOPS configuration file exists
if( !$ENV->getPath( 'config/woops.ini.php' ) ) {
    
    // Redirects to the WOOPS installer
    header( 'Location: ' . $ENV->getSourceWebPath( 'scripts/install/' ) );
}

// Gets the current page
$PAGE = Woops_Page_Engine::getInstance()->getPageObject();

// Prints the current page
print $PAGE->writePage();

// Cleanup
unset( $ENV, $PAGE );
