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
 * Abstract for the SWF tag classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf
 */
abstract class Woops_Swf_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    abstract public function processData( Woops_Swf_Binary_Stream $stream );
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x00;
    
    /**
     * Gets the SWF tag type
     * 
     * @return  int     The SWF tag type
     */
    public function getType()
    {
        return $this->_type;
    }
}
