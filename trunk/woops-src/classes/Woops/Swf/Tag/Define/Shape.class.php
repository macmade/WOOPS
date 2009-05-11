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
 * SWF DefineShape tag
 * 
 * The DefineShape tag defines a shape for later use by control tags such as
 * PlaceObject. The ShapeId uniquely identifies this shape as 'character' in the
 * Dictionary. The ShapeBounds field is the rectangle that completely encloses
 * the shape. The SHAPEWITHSTYLE structure includes all the paths, fill styles
 * and line styles that make up the shape.
 * The minimum file format version is SWF 1.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag.Define.Shape
 */
class Shape extends \Woops\Swf\Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The SWF tag type
     */
    protected $_type        = 0x02;
    
    /**
     * The ID for this character
     */
    protected $_shapeId     = 0;
    
    /**
     * The bounds of the shape
     */
    protected $_shapeBounds = NULL;
    
    /**
     * Class constructor
     * 
     * @param   Woops\Swf\File  The instance of the SWF file in which the tag is contained
     */
    public function __construct( \Woops\Swf\File $file )
    {
        // Calls the parent constructor
        parent::__construct( $file );
        
        // Creates a new rectangle record
        $this->_shapeBounds = new \Woops\Swf\Record\Rectangle();
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( \Woops\Swf\Binary\Stream $stream )
    {
        // Gets the shape ID
        $this->_shapeId = $stream->littleEndianUnsignedShort();
        
        // Processes the rectangle data
        $this->_shapeBounds->processData( $stream );
    }
    
    /**
     * Gets the shape bounds
     * 
     * @return  Woops\Swf\Record\Rectangle  The rectangle object for the shape bounds
     */
    public function getShapeBounds()
    {
        return $this->_shapeBounds;
    }
    
    /**
     * Gets the shape ID
     * 
     * @return  int     The shape ID
     */
    public function getShapeId()
    {
        return $this->_shapeId;
    }
    
    /**
     * Sets the shape ID
     * 
     * @param   int     The shape ID
     * @return  void
     */
    public function setShapeId( $id )
    {
        $this->_shapeId = ( int )$id;
    }
}
