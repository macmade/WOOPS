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

Woops_Core_Module_Manager::getInstance()->registerBlockType(
    'cms',
    'Woops_Mod_Cms_Block'
);

Woops_Xhtml_Parser::registerProcessingInstructionHandler(
    'woops-cms-block',
    'Woops_Mod_Cms_Block_ProcessingInstruction_Handler'
);

Woops_Page_Engine::getInstance()->registerPageEngine(
    'Woops_Mod_Cms_Page_Engine'
);
