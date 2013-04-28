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

# $Id: Helper.class.php 534 2009-03-03 07:15:08Z macmade $

/**
 * Event object for the Woops_Binary_Stream class
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Binary.Stream
 */
class Woops_Binary_Stream_Event extends Woops_Core_Event
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available events
     */
    const EVENT_SEEK          = 0x01;
    const EVENT_READ          = 0x02;
    const EVENT_WRITE         = 0x04;
    const EVENT_REWIND        = 0x08;
}
