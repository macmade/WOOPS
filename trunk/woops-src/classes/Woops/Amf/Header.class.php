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
 * AMF header
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf
 */
class Woops_Amf_Header
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the header must be understood or not
     */
    protected $_mustUnderstand = false;
    
    /**
     * The AMF marker
     */
    protected $_marker         = NULL;
    
    /**
     * The header's name
     */
    protected $_name           = '';
    
    /**
     * Class constructor
     * 
     * @param   string              The header's name
     * @param   Woops_Amf_Marker    The AMF marker object
     * @param   boolean             Wheter the header must be understood
     * @return  void
     */
    public function __construct( $name, Woops_Amf_Marker $marker, $mustUnderstand = false )
    {
        $this->_name           = ( string )$name;
        $this->_marker         = $marker;
        $this->_mustUnderstand = ( boolean )$mustUnderstand;
    }
    
    /**
     * Gets the header's name
     * 
     * @return  string  The header's name
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
     * Gets the 'must understand' flag
     * 
     * @return  boolean Wheter the header must be understood
     */
    public function getMustUnderstand()
    {
        return $this->_mustUnderstand;
    }
    
    /**
     * Gsts the 'must understand' flag
     * 
     * @param   boolean Wheter the header must be understood
     * @return  void
     */
    public function setMustUnderstand( $value )
    {
        $this->_mustUnderstand = ( boolean )$mustUnderstand;
    }
}