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

#
# Drops all database tables
#
DROP TABLE IF EXISTS `{$PREFIX}CONTRIBUTORS`;
DROP TABLE IF EXISTS `{$PREFIX}CONTRIBUTORS_LOGS`;
DROP TABLE IF EXISTS `{$PREFIX}PAGEHEADERS`;
DROP TABLE IF EXISTS `{$PREFIX}PAGEINFOS`;
DROP TABLE IF EXISTS `{$PREFIX}TEMPLATES`;
