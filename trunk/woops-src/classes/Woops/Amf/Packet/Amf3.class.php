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
