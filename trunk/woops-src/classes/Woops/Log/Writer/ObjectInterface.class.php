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
namespace Woops\Log\Writer;

/**
 * Interface for the log writer classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Log.Writer
 */
interface ObjectInterface extends \Woops\Core\Singleton\ObjectInterface
{
    /**
     * Writes a log message
     * 
     * @param   string  The message to write
     * @param   int     The current time, as a timestamp
     * @param   int     The log type
     * @param   string  The name of the type
     * @return  void
     */
    public function write( $message, $time, $type, $typeName );
}
