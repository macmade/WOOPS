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

// Internal namespace
namespace Woops\Xhtml\ProcessingInstruction\Handler;

/**
 * Interface for the XHTML processing instruction handlers
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Xhtml.ProcessingInstruction.Handler
 */
interface ObjectInterface
{
    /**
     * Process a processing instruction
     * 
     * @param   
     * @param   
     * @return  
     */
    public function process( \stdClass $options );
}
