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

// Includes the configuration file
require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'config.inc.php' );

// Includes the initialization script
require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'woops-src' . DIRECTORY_SEPARATOR . 'init.inc.php' );

// Gets the current page
$PAGE = Woops_Page_Getter::getInstance();

// Prints the current page
print $PAGE;
