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
require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'woops-src' . DIRECTORY_SEPARATOR . 'init.inc.php' );

// Gets the current page
$PAGE = Woops_Page_Engine::getInstance()->getPageObject();

// Prints the current page
print $PAGE->writePage();
