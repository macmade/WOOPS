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
namespace Woops\Mod\Pdo\Database\Engine;

/**
 * Exception class for the Woops\Mod\Pdo\Database\Engine class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Pdo.Database.Engine
 */
final class Exception extends \Woops\Core\Exception\Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_PDO            = 0x01;
    const EXCEPTION_NO_PDO_DRIVER     = 0x02;
    const EXCEPTION_NO_CONNECTION     = 0x03;
    const EXCEPTION_BAD_METHOD        = 0x04;
    const EXCEPTION_INVALID_STATEMENT = 0x05;
}
