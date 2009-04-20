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
 * Abstract for the ZIP extra field classes
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Zip
 */
abstract class Woops_Zip_ExtraField
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The type of the extra field
     */
    protected $_type = 0x0000;
    
    /**
     * The extra field data
     */
    protected $_data = '';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops_Zip_Binary_Stream The binary stream
     * @return  void
     */
    public function processData( Woops_Zip_Binary_Stream $stream )
    {
        $length      = $stream->littleEndianUnsignedShort();
        $this->_data = $stream->read( $length );
    }
    
    /**
     * Gets the extra field data
     * 
     * @return  string  The extra field data
     */
    public function getData()
    {
        return $this->_data;
    }
    
    /**
     * Sets the extra field data
     * 
     * @param   string  The extra field data
     * @return  void
     */
    public function setData( $value )
    {
        $this->_data = ( string )$value;
    }
}
