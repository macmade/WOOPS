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
