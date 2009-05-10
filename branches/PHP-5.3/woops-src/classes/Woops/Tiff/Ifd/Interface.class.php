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
namespace Woops\Tiff\Ifd;

/**
 * Interface for the custom TIFF IFD classes
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff.Ifd
 */
interface Interface extends \Iterator
{
    /**
     * Class constructor
     * 
     * @param   Woops\Tiff\File The TIFF file in which the IFD is contained
     * @return  void
     */
    public function __construct( \Woops\Tiff\File $file );
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops\Tiff\Binary\Stream    The binary stream
     * @return  void
     */
    public function processData( \Woops\Tiff\Binary\Stream $stream );
    
    /**
     * Gets the offset of the next IDF (Image File Directory)
     * 
     * @return  int     The offset of the first IDF (Image File Directory)
     */
    public function getNextIfdOffset();
    
    /**
     * Sets the offset of the next IDF (Image File Directory)
     * 
     * @param   int     The offset of the first IDF (Image File Directory)
     * @return  void
     */
    public function setNextIfdOffset( $value );
    
    /**
     * Creates a new tag in the IFD
     * 
     * @param   int             The tag type (one of the TAG_XXX constant)
     * @return  Woops\Tiff\Tag  The tag object
     */
    public function newTag( $type );
    
    /**
     * Adds a tag in the IFD
     * 
     * @param   Woops\Tiff\Tag  The tag object
     * @return  void
     */
    public function addTag( \Woops\Tiff\Tag $tag );
}
