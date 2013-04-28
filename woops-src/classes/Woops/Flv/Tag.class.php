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
 * Abstract for the FLV tag classes
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Flv
 */
abstract class Woops_Flv_Tag extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The FLV tag type
     */
    protected $_type              = 0x00;
    
    /**
     * The length of the data in the Data field
     */
    protected $_dataSize          = 0;
    
    /**
     * The time in milliseconds at which the data in this tag applies
     */
    protected $_timestamp         = 0;
    
    /**
     * The extension of the Timestamp field to form a SI32 value
     */
    protected $_timestampExtended = 0;
    
    /**
     * The stream ID
     */
    protected $_streamId          = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Flv_Binary_Stream $stream )
    {
        $this->_dataSize          = $stream->u24Int();
        $this->_timestamp         = $stream->u24Int();
        $this->_timestampExtended = $stream->unsignedChar();
        $this->_streamId          = $stream->u24Int();
    }
    
    /**
     * Gets the tag type
     * 
     * @return  int     The tag type
     */
    public function getType()
    {
        return $this->_type;
    }
    
    public function getDataSize()
    {
        return $this->_dataSize;
    }
}
