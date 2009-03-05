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
 * Abstract class for the AMF3 markers
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf.Marker
 */
abstract class Woops_Amf_Marker_Amf3 extends Woops_Amf_Marker
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The AMF version
     */
    protected $_version = 3;
    
    /**
     * Gets the AMF version
     * 
     * @return  int The AMF version
     */
    public function getVersion()
    {
        return $this->_version;
    }
}
