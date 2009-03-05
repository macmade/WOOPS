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
 * AMF0 packet
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Packet
 */
class Woops_Amf_Packet_Amf0 extends Woops_Amf_Packet
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF packet version
     */
    protected $_version = 0;
    
    /**
     * The allowed AMF packets
     */
    protected $_packets = array(
        0x00 => 'Number',
        0x01 => 'Boolean',
        0x02 => 'String',
        0x03 => 'Object',
        0x04 => 'MovieClip',
        0x05 => 'Null',
        0x06 => 'Undefined',
        0x07 => 'Reference',
        0x08 => 'EcmaArray',
        0x09 => 'ObjectEnd',
        0x0A => 'StrictArray',
        0x0B => 'Date',
        0x0C => 'LongString',
        0x0D => 'Unsupported',
        0x0E => 'Recordset',
        0x0F => 'XmlDocument',
        0x10 => 'TypedObject',
        0x11 => 'AvmPlus'
    );
    
    /**
     * The allowed AMF3 markers
     * 
     * AMF3 packets can be placed on an AMF0 packet, by using the AVM+
     * AMF0 marker, that specifies the next object is an AMF3 object.
     */
    protected $_amf3Packets = array(
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
