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
 * AMF un-serializer
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf
 */
class Woops_Amf_UnSerializer
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The binary stream
     */
    protected $_stream = NULL;
    
    /**
     * The AMF packet object
     */
    protected $_packet = NULL;
    
    /**
     * Class constructor
     * 
     * @param   string  The AMF packet raw data
     * @return  void
     */
    public function __construct( $rawData )
    {
        // Creates the binary stream object
        $this->_stream  = new Woops_Amf_Binary_Stream( $rawData );
        
        // Gets the packet version
        $version        = $this->_stream->bigEndianUnsignedShort();
        
        // Creates a new AMF packet object
        $this->_packet  = new Woops_Amf_Packet( $version );
        
        // Gets the number of headers
        $headerCount    = $this->_stream->bigEndianUnsignedShort();
    }
    
    /**
     * Gets the AMF packet object
     * 
     * @return  Woops_Amf_Packet    The AMF packet object
     */
    public function getPacket()
    {
        return $this->_packet;
    }
}
