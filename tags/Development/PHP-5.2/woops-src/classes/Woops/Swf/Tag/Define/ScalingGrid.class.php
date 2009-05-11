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

/**
 * SWF DefineScalingGrid tag
 * 
 * The DefineScalingGrid tag introduces the concept of 9-slice scaling, which
 * allows component-style scaling to be applied to a sprite or button character.
 * When the DefineScalingGrid tag associates a character with a 9-slice grid,
 * Flash Player conceptually divides the sprite or button into nine sections
 * with a grid-like overlay. When the character is scaled, each of the nine
 * areas is scaled independently. To maintain the visual integrity of the
 * character, corners are not scaled, while the remaining areas of the image are
 * scaled larger or smaller, as needed.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag.Define
 */
class Woops_Swf_Tag_Define_ScalingGrid extends Woops_Swf_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type        = 0x4E;
    
    /**
     * The ID of sprite or button character upon which the scaling grid will be applied
     */
    protected $_characterId = 0;
    
    /**
     * The center region of 9-slice grid
     */
    protected $_splitter    = NULL;
    
    /**
     * Class constructor
     * 
     * @param   Woops_Swf_File  The instance of the SWF file in which the tag is contained
     */
    public function __construct( Woops_Swf_File $file )
    {
        // Calls the parent constructor
        parent::__construct( $file );
        
        // Creates a new rectangle record
        $this->_splitter = new Woops_Swf_Record_Rectangle();
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        // Gets the character ID
        $this->_characterId = $stream->littleEndianUnsignedShort();
        
        // Processes the rectangle data
        $this->_splitter->processData( $stream );
    }
    
    /**
     * Gets the center region of 9-slice grid
     * 
     * @return  Woops_Swf_Record_Rectangle  The rectangle object for the center region of 9-slice grid
     */
    public function getSplitter()
    {
        return $this->_splitter;
    }
    
    /**
     * Gets the ID of sprite or button character upon which the scaling grid will be applied
     * 
     * @return  int     The ID of sprite or button character upon which the scaling grid will be applied
     */
    public function getCharacterId()
    {
        return $this->_characterId;
    }
    
    /**
     * Sets the ID of sprite or button character upon which the scaling grid will be applied
     * 
     * @param   int     The ID of sprite or button character upon which the scaling grid will be applied
     * @return  void
     */
    public function setCharacterId( $id )
    {
        $this->_characterId = ( int )$id;
    }
}
