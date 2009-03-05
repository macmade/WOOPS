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
 * AMF packet
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Amf
 */
class Woops_Amf_Packet
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
     * Class constructor
     * 
     * @param   int The AMF packet version (0 for AMF-0, 3 for AMF-3)
     */
    public function __construct( $version = 0 )
    {
        // Checks the version
        if( ( int )$version !== 0 || ( int )$version !== 3 ) {
            
            // Unsupported AMF version
            throw new Woops_Amf_Packet_Exception(
                'Invalid AMF version (' . ( int )$version . ')',
                Woops_Amf_Packet_Exception::EXCEPTION_INVALID_VERSION
            );
        }
        
        // Stores the version
        $this->_version = ( int )$version;
    }
}
