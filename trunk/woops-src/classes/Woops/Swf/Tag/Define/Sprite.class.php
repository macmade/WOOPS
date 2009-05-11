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
namespace Woops\Swf\Tag\Define;

/**
 * SWF DefineSprite tag
 * 
 * The DefineSprite tag defines a sprite character. It consists of a character
 * ID and a frame count, followed by a series of control tags. The sprite is
 * terminated with an End tag.
 * The length specified in the Header reflects the length of the entire
 * DefineSprite tag, including the ControlTags field.
 * Definition tags (such as DefineShape) are not allowed in the DefineSprite
 * tag. All of the characters that control tags refer to in the sprite must be
 * defined in the main body of the file before the sprite is defined.
 * The minimum file format version is SWF 3.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag.Define
 */
class Sprite extends \Woops\Swf\Tag implements \Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The SWF tag type
     */
    protected $_type        = 0x27;
    
    /**
     * The character ID of the sprite
     */
    protected $_spriteId    = 0;
    
    /**
     * The number of frames in the sprite
     */
    protected $_frameCount  = 0;
    
    /**
     * The SWF tags
     */
    protected $_tags        = array();
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Gets the current tag object (SPL Iterator method)
     * 
     * @return  Woops\Swf\Tag   The current SWF tag object
     */
    public function current()
    {
        return $this->_tags[ $this->_iteratorPos ];
    }
    
    /**
     * Moves to the next tag object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current tag object (SPL Iterator method)
     * 
     * @return  int     The index of the current SWF tag
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next tag object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next SWF tag, otherwise false
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_tags );
    }
    
    /**
     * Rewinds the SPL Iterator pointer (SPL Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_iteratorPos = 0;
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( \Woops\Swf\Binary\Stream $stream )
    {
        // Gets the sprite ID and the frame count
        $this->_spriteId   = $stream->littleEndianUnsignedShort();
        $this->_frameCount = $stream->littleEndianUnsignedShort();
        
        // Resets the tag array
        $this->_tags = array();
        
        // Process the tags
        while(!$stream->endOfStream() ) {
            
            // Gets thge tag record header
            $tagHeader = $stream->littleEndianUnsignedShort();
            
            // Gets the tag type
            $tagType   = $tagHeader >> 6;
            
            // Gets the tag length
            $tagLength = $tagHeader & 0x3F;
            
            // Checks for a 32bit length
            if( $tagLength === 0x3F ) {
                
                // Tag is long
                $tagLength = $stream->littleEndianUnsignedLong();
            }
            
            // Creates the tag
            $tag           = $this->newTag( $tagType );
            
            // Creates a binary stream with the tag data
            $tagData       = new \Woops\Swf\Binary\Stream( $stream->read( $tagLength ) );
            
            // Processes the tag data
            $tag->processData( $tagData );
        }
    }
    
    /**
     * Creates a new SWF tag
     * 
     * @param   int             The SWF tag type (one of the Woops\Swf\File::TAG_XXX constant)
     * @return  Woops\Swf\Tag   The SWF tag object
     */
    public function newTag( $type )
    {
        // Gets the tag classname
        $tagClass      = \Woops\Swf\File::getTagClass( $type );
        
        // Creates the tag object
        $tag           = new $tagClass( $this->_file );
        
        // Stores the tag
        $this->_tags[] = $tag;
        
        // Returns the tag
        return $tag;
    }
    
    /**
     * Gets the character ID of the sprite
     * 
     * @return  int     The character ID of the sprite
     */
    public function getSpriteId()
    {
        return $this->_spriteId;
    }
    
    /**
     * Gets the number of frames in the sprite
     * 
     * @return  int     The number of frames in the sprite
     */
    public function getFrameCount()
    {
        return $this->_frameCount;
    }
    
    /**
     * Sets the character ID of the sprite
     * 
     * @param   int     The character ID of the sprite
     * @return  void
     */
    public function setSpriteId( $value )
    {
        $this->_spriteId = ( int )$value;
    }
    
    /**
     * Sets the number of frames in the sprite
     * 
     * @param   int     The number of frames in the sprite
     * @return  void
     */
    public function setFrameCount( $value )
    {
        $this->_frameCount = ( int )$value;
    }
}
