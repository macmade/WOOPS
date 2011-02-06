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

// File encoding
declare( ENCODING = 'UTF-8' );

Woops\Core\Module\Manager::getInstance()->registerBlock
(
    'cms',
    $moduleName,
    'SayHello',
    'Woops\Mod\HelloWorld\SayHello'
);
