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

/**
 * Interface for the XHTML processing instruction handlers
 *
 * @author      Jean-David Gadina - www.xs-labs.com
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
    public function process( stdClass $options );
}
