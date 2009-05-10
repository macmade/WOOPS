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
namespace Woops\Icc;

/**
 * ICC tag table
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Icc
 */
class TagTable extends \Woops\Core\Object implements \Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The ICC tag types (long form)
     */
    const TAG_A_TO_B0               = 0x41324230;
    const TAG_A_TO_B1               = 0x41324231;
    const TAG_A_TO_B2               = 0x41324232;
    const TAG_BLUE_MATRIX_COLUMN    = 0x6258595A;
    const TAG_BLUE_TRC              = 0x62545243;
    const TAG_B_TO_A0               = 0x42324130;
    const TAG_B_TO_A1               = 0x42324131;
    const TAG_B_TO_A2               = 0x42324132;
    const TAG_CALIBRATION_DATE_TIME = 0x63616C74;
    const TAG_CHAR_TARGET           = 0x74617267;
    const TAG_CHROMATIC_ADAPTATION  = 0x63686164;
    const TAG_CHROMATICITY          = 0x6368726D;
    const TAG_COLORANT_ORDER        = 0x636C726F;
    const TAG_COLORANT_TABLE        = 0x636C7274;
    const TAG_COLORANT_TABLE_OUT    = 0x636C6F74;
    const TAG_COPYRIGHT             = 0x63707274;
    const TAG_DEVICE_MFG_DESC       = 0x646D6E64;
    const TAG_DEVICE_MODEL_DESC     = 0x646D6464;
    const TAG_GAMUT                 = 0x67616D74;
    const TAG_GRAY_TRC              = 0x6B545243;
    const TAG_GREEN_MATRIX_COLUMN   = 0x6758595A;
    const TAG_GREEN_TRC             = 0x67545243;
    const TAG_LUMINANCE             = 0x6C756D69;
    const TAG_MEASUREMENT           = 0x6D656173;
    const TAG_MEDIA_BLACK_POINT     = 0x626B7074;
    const TAG_MEDIA_WHITE_POINT     = 0x77747074;
    const TAG_NAMED_COLOR_2         = 0x6E636C32;
    const TAG_OUTPUT_RESPONSE       = 0x72657370;
    const TAG_PREVIEW_0             = 0x70726530;
    const TAG_PREVIEW_1             = 0x70726531;
    const TAG_PREVIEW_2             = 0x70726532;
    const TAG_PROFILE_DESCRIPTION   = 0x64657363;
    const TAG_PROFILE_SEQUENCE_DESC = 0x70736571;
    const TAG_RED_MATRIX_COLUMN     = 0x7258595A;
    const TAG_RED_TRC               = 0x72545243;
    const TAG_TECHNOLOGY            = 0x74656368;
    const TAG_VIEWING_COND_DESC     = 0x76756564;
    const TAG_VIEWING_CONDITIONS    = 0x76696577;
    
    /**
     * The ICC tag types (short form)
     */
    const TAG_A2B0                  = 0x41324230;
    const TAG_A2B1                  = 0x41324231;
    const TAG_A2B2                  = 0x41324232;
    const TAG_BXYZ                  = 0x6258595A;
    const TAG_BTRC                  = 0x62545243;
    const TAG_B2A0                  = 0x42324130;
    const TAG_B2A1                  = 0x42324131;
    const TAG_B2A2                  = 0x42324132;
    const TAG_CALT                  = 0x63616C74;
    const TAG_TARG                  = 0x74617267;
    const TAG_CHAD                  = 0x63686164;
    const TAG_CHRM                  = 0x6368726D;
    const TAG_CLRO                  = 0x636C726F;
    const TAG_CLRT                  = 0x636C7274;
    const TAG_CLOT                  = 0x636C6F74;
    const TAG_CPRT                  = 0x63707274;
    const TAG_DMND                  = 0x646D6E64;
    const TAG_DMDD                  = 0x646D6464;
    const TAG_GAMT                  = 0x67616D74;
    const TAG_KTRC                  = 0x6B545243;
    const TAG_GXYZ                  = 0x6758595A;
    const TAG_GTRC                  = 0x67545243;
    const TAG_LUMI                  = 0x6C756D69;
    const TAG_MEAS                  = 0x6D656173;
    const TAG_BKPT                  = 0x626B7074;
    const TAG_WTPT                  = 0x77747074;
    const TAG_NCL2                  = 0x6E636C32;
    const TAG_RESP                  = 0x72657370;
    const TAG_PRE0                  = 0x70726530;
    const TAG_PRE1                  = 0x70726531;
    const TAG_PRE2                  = 0x70726532;
    const TAG_DESC                  = 0x64657363;
    const TAG_PSEQ                  = 0x70736571;
    const TAG_RXYZ                  = 0x7258595A;
    const TAG_RTRC                  = 0x72545243;
    const TAG_TECH                  = 0x74656368;
    const TAG_VUED                  = 0x76756564;
    const TAG_VIEW                  = 0x76696577;
    
    /**
     * The ICC tag types, with their corresponding PHP class
     */
     protected static $_types = array(
        0x41324230 => '\Woops\Icc\Tag\AToB0',
        0x41324231 => '\Woops\Icc\Tag\AToB1',
        0x41324232 => '\Woops\Icc\Tag\AToB2',
        0x6258595A => '\Woops\Icc\Tag\Blue\MatrixColumn',
        0x62545243 => '\Woops\Icc\Tag\Blue\Trc',
        0x42324130 => '\Woops\Icc\Tag\BToA0',
        0x42324131 => '\Woops\Icc\Tag\BToA1',
        0x42324132 => '\Woops\Icc\Tag\BToA2',
        0x63616C74 => '\Woops\Icc\Tag\CalibrationDateTime',
        0x74617267 => '\Woops\Icc\Tag\CharTarget',
        0x63686164 => '\Woops\Icc\Tag\ChromaticAdaptation',
        0x6368726D => '\Woops\Icc\Tag\Chromaticity',
        0x636C726F => '\Woops\Icc\Tag\Colorant\Order',
        0x636C7274 => '\Woops\Icc\Tag\Colorant\Table',
        0x636C6F74 => '\Woops\Icc\Tag\Colorant\Table\Out',
        0x63707274 => '\Woops\Icc\Tag\Copyright',
        0x646D6E64 => '\Woops\Icc\Tag\Device\MfgDesc',
        0x646D6464 => '\Woops\Icc\Tag\Device\ModelDesc',
        0x67616D74 => '\Woops\Icc\Tag\Gamut',
        0x6B545243 => '\Woops\Icc\Tag\Gray\Trc',
        0x6758595A => '\Woops\Icc\Tag\Green\MatrixColumn',
        0x67545243 => '\Woops\Icc\Tag\Green\Trc',
        0x6C756D69 => '\Woops\Icc\Tag\Luminance',
        0x6D656173 => '\Woops\Icc\Tag\Measurement',
        0x626B7074 => '\Woops\Icc\Tag\Media\BlackPoint',
        0x77747074 => '\Woops\Icc\Tag\Media\WhitePoint',
        0x6E636C32 => '\Woops\Icc\Tag\NamedColor2',
        0x72657370 => '\Woops\Icc\Tag\OutputResponse',
        0x70726530 => '\Woops\Icc\Tag\Preview\0',
        0x70726531 => '\Woops\Icc\Tag\Preview\1',
        0x70726532 => '\Woops\Icc\Tag\Preview\2',
        0x64657363 => '\Woops\Icc\Tag\Profile\Description',
        0x70736571 => '\Woops\Icc\Tag\Profile\SequenceDesc',
        0x7258595A => '\Woops\Icc\Tag\Red\MatrixColumn',
        0x72545243 => '\Woops\Icc\Tag\Red\Trc',
        0x74656368 => '\Woops\Icc\Tag\Technology',
        0x76756564 => '\Woops\Icc\Tag\Viewing\CondDesc',
        0x76696577 => '\Woops\Icc\Tag\Viewing\Conditions'
    );
    
    /**
     * The ICC tags
     */
    protected $_tags        = array();
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Gets the current file header object (SPL Iterator method)
     * 
     * @return  Woops\Zip\Central\File\Header   The current file header object
     */
    public function current()
    {
        return $this->_tags[ $this->_iteratorPos ];
    }
    
    /**
     * Moves to the next file header object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current file header object (SPL Iterator method)
     * 
     * @return  int     The index of the current file header
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next file header object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next file header, otherwise false
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
     * Creates a new ICC tag
     * 
     * @param   int                             The ICC tag type (one of the TAG_XXX constant)
     * @return  Woops\Icc\Tag                   The ICC tag object
     * @throws  Woops\Icc\TagTable\Exception    If the tag type is invalid
     */
    public function newTag( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks if the type is valid
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid tag type
            throw new TagTable\Exception(
                'Invalid tag type (' . $type . ')',
                TagTable\Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        // Gets the tag classname
        $tagClass      = self::$_types[ $type ];
        
        // Creates the tag object
        $tag           = new $tagClass( $this );
        
        // Stores the tag
        $this->_tags[] = $tag;
        
        // Returns the tag
        return $tag;
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops\Icc\Binary\Stream The IC binary stream
     * @return  void
     */
    public function processData( Binary\Stream $stream )
    {
        $this->_tags = array();
        
        $tagCount = $stream->bigEndianUnsignedLong();
        
        for( $i = 0; $i < $tagCount; $i++ ) {
            
            $type      = $stream->bigEndianUnsignedLong();
            $datOffset = $stream->bigEndianUnsignedLong();
            $size      = $stream->bigEndianUnsignedLong();
            
            $offset    = $stream->getOffset();
            
            try {
                
                $tag = $this->newTag( $type );
                
            } catch( TagTable\Exception $e ) {
                
                if( $e->getCode() !== TagTable\Exception::EXCEPTION_INVALID_TAG_TYPE ) {
                    
                    throw $e;
                }
                
                $tag = new UnknownTag( $type );
                $this->_tags[] = $tag;
            }
        }
    }
}
