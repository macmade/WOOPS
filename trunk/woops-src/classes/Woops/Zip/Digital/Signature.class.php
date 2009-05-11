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
namespace Woops\Zip\Digital;

/**
 * ZIP digital signature
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Zip.Digital
 */
class Signature extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The signature data
     */
    protected $_data = '';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops\Zip\Binary\Stream The binary stream
     * @return  void
     */
    public function processData( \Woops\Zip\Binary\Stream $stream )
    {
        $length      = $stream->littleEndianUnsignedShort();
        $this->_data = $stream->read( $length );
    }
    
    /**
     * Gets the signature data
     * 
     * @return  string  The signature data
     */
    public function getData()
    {
        return $this->_data;
    }
    
    /**
     * Sets the signature data
     * 
     * @param   string  The signature data
     * @return  void
     */
    public function setData( $value )
    {
        $this->_data = ( string )$value;
    }
}
