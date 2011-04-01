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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * FLV binary stream
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Flv.Binary
 */
class Woops_Flv_Binary_Stream extends Woops_Binary_Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Gets an unsigned 24bits integer from the stream
     * 
     * @return  int     The unsigned 24bits integer
     */
    public function u24Int()
    {
        // Read 3 bytes
        $byte1 = $this->unsignedChar();
        $byte2 = $this->unsignedChar();
        $byte3 = $this->unsignedChar();
        
        // Returns the integer
        return ( $byte1 << 16 ) | ( $byte2 << 8 ) | $byte3;
    }
}
