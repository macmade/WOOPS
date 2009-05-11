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

# $Id: Helper.class.php 534 2009-03-03 07:15:08Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Database\Layer;

/**
 * Event object for the Woops\Database\Layer class
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Database.Layer
 */
class Event extends \Woops\Core\Event
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The available events
     */
    const EVENT_ENGINE_LOAD       = 0x01;
    const EVENT_ENGINE_CONNECT    = 0x02;
    const EVENT_ENGINE_DISCONNECT = 0x04;
    const EVENT_ENGINE_REGISTER   = 0x08;
}
