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

/**
 * AMF3 packet
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Packet
 */
class Woops_Amf_Packet_Amf3 extends Woops_Amf_Packet
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF packet version
     */
    protected $_version = 3;
    
    /**
     * The allowed AMF markers
     */
    protected $_packets = array(
        0x00 => 'Undefined',
        0x01 => 'Null',
        0x02 => 'False',
        0x03 => 'True',
        0x04 => 'Integer',
        0x05 => 'Double',
        0x06 => 'String',
        0x07 => 'XmlDoc',
        0x08 => 'Date',
        0x09 => 'Array',
        0x0A => 'Object',
        0x0B => 'Xml',
        0x0C => 'ByteArray'
    );
}
