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

/**
 * Interface for the XHTML processing instruction handlers
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Xhtml.ProcessingInstruction_Handler
 */
interface Woops_Xhtml_ProcessingInstruction_Handler_Interface
{
    /**
     * Process a processing instruction
     * 
     * @param   
     * @param   
     * @return  
     */
    function process( stdClass $options );
}