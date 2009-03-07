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
    protected $_targetUri  = NULL;
    
    /**
     * The response URI
     */
    protected $_requestUri = NULL;
    
    /**
     * The AMF marker
     */
    protected $_marker     = NULL;
    
    /**
     * The message's name
     */
    protected $_name       = '';
    
    /**
     * Class constructor
     * 
     * @param   string              The message's name
     * @param   string              The target URI
     * @param   string              The request URI
     * @param   Woops_Amf_Marker    The AMF marker object
     * @return  void
     */
    public function __construct( $name, $targetUri, $requestUri, Woops_Amf_Marker $marker )
    {
        $this->_name           = ( string )$name;
        $this->_marker         = $marker;
        $this->_targetUri      = new Woops_Uniform_Resource_Identifier( $targetUri );
        $this->_requestUri     = new Woops_Uniform_Resource_Identifier( $requestUri );
    }
    
    /**
     * Gets the message's name
     * 
     * @return  string  The message's name
     */
    public function getName()
    {
        return $this->_name;
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
     * Gets the request URI
     * 
     * @return  Woops_Uniform_Resource_Identifier   The request URI object
     */
    public function getRequestUri()
    {
        return $this->_requestUri;
    }
}
