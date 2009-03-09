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
 * AMF message
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf
 */
class Woops_Amf_Message
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The target URI
     */
    protected $_targetUri   = NULL;
    
    /**
     * The response URI
     */
    protected $_responseUri = NULL;
    
    /**
     * The AMF marker
     */
    protected $_marker      = NULL;
    
    /**
     * Class constructor
     * 
     * @param   string              The message's name
     * @param   string              The target URI
     * @param   string              The response URI
     * @param   Woops_Amf_Marker    The AMF marker object
     * @return  void
     */
    public function __construct( $targetUri, $responseUri, Woops_Amf_Marker $marker )
    {
        $this->_marker         = $marker;
        $this->_targetUri      = new Woops_Uniform_Resource_Identifier( $targetUri );
        $this->_responseUri    = new Woops_Uniform_Resource_Identifier( $responseUri );
    }
    
    /**
     * Gets the AMF message as binary
     * 
     * @return  string  The AMF message
     */
    public function __toString()
    {
        // Creates a new binary stream
        $stream = new Woops_Amf_Binary_Stream();
        
        // Writes the target URI
        $stream->writeUtf8String( $this->_targetUri );
        
        // Writes the response URI
        $stream->writeUtf8String( $this->_responseUri );
        
        // Writes the message's length (U32-1 - unknown length)
        $stream->writeBigEndianUnsignedLong( 0xFFFFFFFF );
        
        // Writes the marker
        $stream->write( ( string )$this->_marker );
        
        // Resets the stream pointer
        $stream->rewind();
        
        // Returns the stream data
        return ( string )$stream;
    }
    
    /**
     * Gets the AMF marker
     * 
     * @return  Woops_Amf_Marker The AMF marker
     */
    public function getMarker()
    {
        return $this->_marker;
    }
    
    /**
     * Gets the target URI
     * 
     * @return  Woops_Uniform_Resource_Identifier   The target URI object
     */
    public function getTargetUri()
    {
        return $this->_targetUri;
    }
    
    /**
     * Gets the response URI
     * 
     * @return  Woops_Uniform_Resource_Identifier   The response URI object
     */
    public function getResponseUri()
    {
        return $this->_responseUri;
    }
}
