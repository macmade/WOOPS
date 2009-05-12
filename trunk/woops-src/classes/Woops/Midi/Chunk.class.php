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
namespace Woops\Midi;

/**
 * Abstract for the MIDI chunk classes
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi
 */
abstract class Chunk extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type     = 0x00000000;
    
    /**
     * The chunk's data size
     */
    protected $_dataSize = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Binary\Stream $stream )
    {
        $this->_dataSize = $stream->bigEndianUnsignedLong();
    }
    
    /**
     * Gets the chunk type
     * 
     * @return  int     The chunk type
     */
    public function getType()
    {
        return $this->_type;
    }
}
