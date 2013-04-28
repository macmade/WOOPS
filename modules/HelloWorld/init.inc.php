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

Woops_Core_Module_Manager::getInstance()->registerBlock(
    'cms',
    $moduleName,
    'SayHello',
    'Woops_Mod_HelloWorld_SayHello'
);
