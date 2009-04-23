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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * FLV file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Flv
 */
class Woops_Flv_File
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The FLV header
     */
    protected $_header = NULL;
    
    /**
     * The FLV tags
     */
    protected $_tags   = array();
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        $this->_header = new Woops_Flv_Header();
    }
    
    /**
     * Gets the FLV header
     * 
     * @return  Woops_Flv_Header    The FLV header
     */
    public function getHeader()
    {
        return $this->_header;
    }
}
