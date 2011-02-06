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
 * MIDI file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi
 */
class File extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The MIDI chunk types
     */
    const CHUNK_HEADER = 0x4D546864;
    const CHUNK_TRACK  = 0x4D54726B;
    
    /**
     * The MIDI chunk types with their corresponding PHP classname
     */
    protected static $_types = array
    (
        0x4D546864 => 'Woops\Midi\Chunk\Header',
        0x4D54726B => 'Woops\Midi\Chunk\Track'
    );
    
    /**
     * The MIDI chunks
     */
    protected $_chunks = array();
    
    /**
     * Creates a new chunk
     * 
     * @param   int                 The chunk type (one of the CHUNK_XXX constant)
     * @return  Woops\Midi\Chunk    The chunk object
     */
    public function newChunk( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks the chunk type
        if( !isset( self::$_types[ $type ] ) )
        {
            // Error - Invalid type
            throw new File\Exception
            (
                'Invalid chunk type (' . $type . ')',
                File\Exception::EXCEPTION_INVALID_CHUNK_TYPE
            );
        }
        
        // Classname for the chunk
        $chunkClass      = self::$_types[ $type ];
        
        // Creates the chunk object
        $chunk           = new $chunkClass();
        
        // Stores the chunk
        $this->_chunks[] = $chunk;
        
        // Returns the chunk
        return $chunk;
    }
}
