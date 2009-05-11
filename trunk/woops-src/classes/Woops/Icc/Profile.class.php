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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Icc;

/**
 * ICC profile
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Icc
 */
class Profile extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
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
        $this->_header   = new Header();
        $this->_tagTable = new TagTable();
    }
    
    /**
     * Gets the ICC header
     * 
     * @return  Woops\Icc\Header    The ICC header
     */
    public function getHeader()
    {
        return $this->_header;
    }
    
    /**
     * Gets the ICC tag table
     * 
     * @return  Woops\Icc\Tag_Table The ICC tag table
     */
    public function getTagTable()
    {
        return $this->_tagTable;
    }
}
