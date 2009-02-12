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

Woops_Core_Module_Manager::getInstance()->registerBlock(
    'cms',
    $moduleName,
    'SayHello',
    'Woops_Mod_HelloWorld_SayHello'
);
