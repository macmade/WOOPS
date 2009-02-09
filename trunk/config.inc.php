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

// Creates the WOOPS configuration object, with its sections
$WOOPS_CONF           = new stdClass();
$WOOPS_CONF->database = new stdClass();
$WOOPS_CONF->time     = new stdClass();
$WOOPS_CONF->lang     = new stdClass();
$WOOPS_CONF->xhtml    = new stdClass();
$WOOPS_CONF->error    = new stdClass();

################################################################################
# Database settings                                                            #
################################################################################

// The database driver to use, with PDO
$WOOPS_CONF->database->driver      = 'mysql';

// The database host
$WOOPS_CONF->database->host        = 'localhost';

// The database port
$WOOPS_CONF->database->port        = '3306';

// The database username
$WOOPS_CONF->database->user        = 'woops';

// The database user password
$WOOPS_CONF->database->password    = 'woops';

// The name of the database to use
$WOOPS_CONF->database->name        = 'woops';

// The prefix to use for all WOOPS tables
$WOOPS_CONF->database->tablePrefix = 'WOOPS_';

################################################################################
# Date and time settings                                                       #
################################################################################

// The default timezone
$WOOPS_CONF->time->timezone = 'Europe/Zurich';

################################################################################
# Language settings                                                            #
################################################################################

// The default timezone
$WOOPS_CONF->lang->defaultLanguage = 'en';

################################################################################
# XHTML settings                                                               #
################################################################################

// Format and indent the generated XHTML code
$WOOPS_CONF->xhtml->format = true;

################################################################################
# Error related settings                                                       #
################################################################################

// Wheter the error reporting must be verbose or not (should be turned off for production boxes)
$WOOPS_CONF->error->verbose = true;
