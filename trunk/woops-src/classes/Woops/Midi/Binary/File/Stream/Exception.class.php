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

# $Id: Exception.class.php 559 2009-03-04 17:18:24Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Midi\Binary\File\Stream;

/**
 * Exception class for the Woops\Midi\Binary\File\Stream class
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi.Binary.File.Stream
 */
final class Exception extends \Woops\Core\Exception\Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_FILE           = 0x01;
    const EXCEPTION_FILE_NOT_READABLE = 0x02;
}
