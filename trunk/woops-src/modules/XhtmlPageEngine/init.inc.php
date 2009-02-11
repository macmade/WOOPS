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

Woops_Xhtml_Parser::registerProcessingInstructionHandler( 'woops-module-block', 'Woops_Mod_XhtmlPageEngine_Block_ProcessingInstruction_Handler' );
Woops_Page_Engine::getInstance()->registerPageEngineClass( 'Woops_Mod_XhtmlPageEngine_Page_Engine' );
