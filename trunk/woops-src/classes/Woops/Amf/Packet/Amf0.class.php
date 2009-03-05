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
        0x00 => 'Woops_Amf_Marker_Amf0_Number',
        0x01 => 'Woops_Amf_Marker_Amf0_Boolean',
        0x02 => 'Woops_Amf_Marker_Amf0_String',
        0x03 => 'Woops_Amf_Marker_Amf0_Object',
        0x04 => 'Woops_Amf_Marker_Amf0_MovieClip',
        0x05 => 'Woops_Amf_Marker_Amf0_Null',
        0x06 => 'Woops_Amf_Marker_Amf0_Undefined',
        0x07 => 'Woops_Amf_Marker_Amf0_Reference',
        0x08 => 'Woops_Amf_Marker_Amf0_EcmaArray',
        0x09 => 'Woops_Amf_Marker_Amf0_ObjectEnd',
        0x0A => 'Woops_Amf_Marker_Amf0_StrictArray',
        0x0B => 'Woops_Amf_Marker_Amf0_Date',
        0x0C => 'Woops_Amf_Marker_Amf0_LongString',
        0x0D => 'Woops_Amf_Marker_Amf0_Unsupported',
        0x0E => 'Woops_Amf_Marker_Amf0_Recordset',
        0x0F => 'Woops_Amf_Marker_Amf0_XmlDocument',
        0x10 => 'Woops_Amf_Marker_Amf0_TypedObject',
        0x11 => 'Woops_Amf_Marker_Amf0_AvmPlus'
    );
    
    /**
     * The allowed AMF3 markers
     * 
     * AMF3 packets can be placed on an AMF0 packet, by using the AVM+
     * AMF0 marker, that specifies the next object is an AMF3 object.
     */
    protected $_amf3Packets = array(
        0x00 => 'Woops_Amf_Marker_Amf3_Undefined',
        0x01 => 'Woops_Amf_Marker_Amf3_Null',
        0x02 => 'Woops_Amf_Marker_Amf3_False',
        0x03 => 'Woops_Amf_Marker_Amf3_True',
        0x04 => 'Woops_Amf_Marker_Amf3_Integer',
        0x05 => 'Woops_Amf_Marker_Amf3_Double',
        0x06 => 'Woops_Amf_Marker_Amf3_String',
        0x07 => 'Woops_Amf_Marker_Amf3_XmlDoc',
        0x08 => 'Woops_Amf_Marker_Amf3_Date',
        0x09 => 'Woops_Amf_Marker_Amf3_Array',
        0x0A => 'Woops_Amf_Marker_Amf3_Object',
        0x0B => 'Woops_Amf_Marker_Amf3_Xml',
        0x0C => 'Woops_Amf_Marker_Amf3_ByteArray'
    );
}
