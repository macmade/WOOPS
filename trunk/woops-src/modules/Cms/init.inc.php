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

Woops_Core_Module_Manager::getInstance()->registerBlockType(
    'cms',
    'Woops_Mod_Cms_Block'
);

Woops_Xhtml_Parser::registerProcessingInstructionHandler(
    'woops-cms-block',
    'Woops_Mod_Cms_Block_ProcessingInstruction_Handler'
);

Woops_Page_Engine::getInstance()->registerPageEngineClass(
    'Woops_Mod_Cms_Page_Engine'
);
