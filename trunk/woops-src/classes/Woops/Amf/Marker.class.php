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
     * Gets the AMF version
     * 
     * @return  int The AMF version
     */
    abstract public function getVersion();
    
    /**
     * The AMF marker type
     */
    protected $_type = 0x00;
    
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
