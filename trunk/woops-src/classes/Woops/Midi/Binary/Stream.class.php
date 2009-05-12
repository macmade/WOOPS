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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Midi\Binary;

/**
 * MIDI binary stream
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi.Binary
 */
class Stream extends \Woops\Binary\Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    public function variableLengthQuantity()
    {
        // Reads an unsigned char
        $char   = $this->unsignedChar();
        
        // Storage for the result
        $result = $char;
        
        while( $char & 0x80 ) {
            
            $char   = $this->unsignedChar();
            $result = ( $result << 8 ) | $char;
        }
        
        return $result;
    }
}
