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
 * Abstract class for the AMF markers
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf
 */
abstract class Woops_Amf_Marker
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Processes the raw data for the marker
     * 
     * @param   Woops_Amf_Binary_Stream The AMF binary stream object
     * @return  void
     */
    abstract public function processData( Woops_Amf_Binary_Stream $stream );
    
    /**
     * The AMF version
     */
    protected $_version = 0;
    
    /**
     * The AMF marker type
     */
    protected $_type    = 0x00;
    
    /**
     * The AMF packet object in which the AMF marker belongs
     */
    protected $_packet  = NULL;
    
    /**
     * The allowed AMF packets
     */
    protected $_markers = array();
    
    /**
     * The processed marker data
     */
    protected $_data    = NULL;
    
    /**
     * Class constructor
     * 
     * @param   Woops_Amf_Packet    The AMF packet object in which the AMF marker belongs
     * @return  void
     */
    public function __construct( Woops_Amf_Packet $packet )
    {
        $this->_packet = $packet;
        $this->_data   = new stdClass();
    }
    
    /**
     * Gets the AMF marker as binary
     * 
     * @return  string  The AMF marker
     */
    public function __toString()
    {
        $stream = new Woops_Amf_Binary_Stream();
        
        $stream->writeChar( $this->_type );
        
        $stream->rewind();
        
        return ( string )$stream;
    }
    
    /**
     * Gets the AMF data
     * 
     * @return  stdClass    The AMF data
     */
    public function getData()
    {
        return $this->_data;
    }
    
    /**
     * Gets the AMF version
     * 
     * @return  int The AMF version
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * Gets the AMF marker type
     * 
     * @return  int The AMF marker type
     */
    public function getType()
    {
        return $this->_type;
    }
}
