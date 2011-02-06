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

Woops\Core\Module\Manager::getInstance()->registerBlockType
(
    'cms',
    'Woops\Mod\Cms\Block'
);

Woops\Xhtml\Parser::registerProcessingInstructionHandler
(
    'woops-cms-block',
    'Woops\Mod\Cms\Block\ProcessingInstruction\Handler'
);

Woops\Page\Engine::getInstance()->registerPageEngine
(
    'Woops\Mod\Cms\Page\Engine'
);
