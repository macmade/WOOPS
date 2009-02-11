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
    'xhtml',
    'Woops_Mod_XhtmlPageEngine_Xhtml_Block'
);

Woops_Xhtml_Parser::registerProcessingInstructionHandler(
    'woops-block-xhtml',
    'Woops_Mod_XhtmlPageEngine_Xhtml_Block_ProcessingInstruction_Handler'
);

Woops_Page_Engine::getInstance()->registerPageEngineClass(
    'Woops_Mod_XhtmlPageEngine_Xhtml_Page_Engine'
);
