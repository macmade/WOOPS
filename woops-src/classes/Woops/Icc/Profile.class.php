<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * ICC profile
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Icc
 */
class Woops_Icc_Profile extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The ICC header
     */
    protected $_header   = NULL;
    
    /**
     * The ICC tag table
     */
    protected $_tagTable = NULL;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        $this->_header   = new Woops_Icc_Header();
        $this->_tagTable = new Woops_Icc_TagTable();
    }
    
    /**
     * Gets the ICC header
     * 
     * @return  Woops_Icc_Header    The ICC header
     */
    public function getHeader()
    {
        return $this->_header;
    }
    
    /**
     * Gets the ICC tag table
     * 
     * @return  Woops_Icc_Tag_Table The ICC tag table
     */
    public function getTagTable()
    {
        return $this->_tagTable;
    }
}
