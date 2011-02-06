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

# $Id: init.inc.php 190 2009-02-11 07:55:49Z macmade $

Woops\Core\Module\Manager::getInstance()->registerBlock
(
    'cms',
    $moduleName,
    'Backend',
    'Woops\Mod\Admin\Backend'
);
