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
namespace Woops\Swf;

/**
 * SWF file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf
 */
class File extends \Woops\Core\Object implements \Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The SWF tag types
     */
    const TAG_END                           = 0x00;
    const TAG_SHOWFRAME                     = 0x01;
    const TAG_DEFINE_SHAPE                  = 0x02;
    const TAG_PLACE_OBJECT                  = 0x04;
    const TAG_REMOVE_OBJECT                 = 0x05;
    const TAG_DEFINE_BITS                   = 0x06;
    const TAG_DEFINE_BUTTON                 = 0x07;
    const TAG_JPEGTABLES                    = 0x08;
    const TAG_SET_BACKGROUNDCOLOR           = 0x09;
    const TAG_DEFINE_FONT                   = 0x0A;
    const TAG_DEFINE_TEXT                   = 0x0B;
    const TAG_DO_ACTION                     = 0x0C;
    const TAG_DEFINE_FONT_INFO              = 0x0D;
    const TAG_DEFINE_SOUND                  = 0x0E;
    const TAG_START_SOUND                   = 0x0F;
    const TAG_DEFINE_BUTTON_SOUND           = 0x11;
    const TAG_SOUND_STREAM_HEAD             = 0x12;
    const TAG_SOUND_STREAM_BLOCK            = 0x13;
    const TAG_DEFINE_BITS_LOSSLESS          = 0x14;
    const TAG_DEFINE_BITS_JPEG_2            = 0x15;
    const TAG_DEFINE_SHAPE_2                = 0x16;
    const TAG_DEFINE_BUTTON_CXFORM          = 0x17;
    const TAG_PROTECT                       = 0x18;
    const TAG_PLACE_OBJECT_2                = 0x1A;
    const TAG_REMOVE_OBJECT_2               = 0x1C;
    const TAG_DEFINE_SHAPE_3                = 0x20;
    const TAG_DEFINE_TEXT_2                 = 0x21;
    const TAG_DEFINE_BUTTON_2               = 0x22;
    const TAG_DEFINE_BITS_JPEG_3            = 0x23;
    const TAG_DEFINE_BITS_LOSSLESS_2        = 0x24;
    const TAG_DEFINE_EDITTEXT               = 0x25;
    const TAG_DEFINE_SPRITE                 = 0x27;
    const TAG_FRAMELABEL                    = 0x2B;
    const TAG_SOUND_STREAM_HEAD_2           = 0x2D;
    const TAG_DEFINE_MORPH_SHAPE            = 0x2E;
    const TAG_DEFINE_FONT_2                 = 0x30;
    const TAG_EXPORT_ASSETS                 = 0x38;
    const TAG_IMPORT_ASSETS                 = 0x39;
    const TAG_ENABLE_DEBUGGER               = 0x3A;
    const TAG_DO_INITACTION                 = 0x3B;
    const TAG_DEFINE_VIDEOSTREAM            = 0x3C;
    const TAG_VIDEOFRAME                    = 0x3D;
    const TAG_DEFINE_FONT_INFO_2            = 0x3E;
    const TAG_ENABLE_DEBUGGER_2             = 0x40;
    const TAG_SCRIPTLIMITS                  = 0x41;
    const TAG_SET_TABINDEX                  = 0x42;
    const TAG_FILEATTRIBUTES                = 0x45;
    const TAG_PLACE_OBJECT_3                = 0x46;
    const TAG_IMPORT_ASSETS_2               = 0x47;
    const TAG_DEFINE_FONT_ALIGNZONES        = 0x49;
    const TAG_CSMTEXTSETTINGS               = 0x4A;
    const TAG_DEFINE_FONT_3                 = 0x4B;
    const TAG_SYMBOLCLASS                   = 0x4C;
    const TAG_METADATA                      = 0x4D;
    const TAG_DEFINE_SCALINGGRID            = 0x4E;
    const TAG_DO_ABC                        = 0x52;
    const TAG_DEFINE_SHAPE_4                = 0x53;
    const TAG_DEFINE_MORPH_SHAPE_2          = 0x54;
    const TAG_DEFINE_SCENEANDFRAMELABELDATA = 0x56;
    const TAG_DEFINE_BINARYDATA             = 0x57;
    const TAG_DEFINE_FONT_NAME              = 0x58;
    const TAG_START_SOUND_2                 = 0x59;
    const TAG_DEFINE_BITS_JPEG_4            = 0x5A;
    const TAG_DEFINE_FONT_4                 = 0x5B;

    
    /**
     * The SWF tag types, with their corresponding PHP class
     */
    protected static $_types  = array(
        0x00 => '\Woops\Swf\Tag\End',
        0x01 => '\Woops\Swf\Tag\ShowFrame',
        0x02 => '\Woops\Swf\Tag\Define\Shape',
        0x04 => '\Woops\Swf\Tag\Place\Object',
        0x05 => '\Woops\Swf\Tag\Remove\Object',
        0x06 => '\Woops\Swf\Tag\Define\Bits',
        0x07 => '\Woops\Swf\Tag\Define\Button',
        0x08 => '\Woops\Swf\Tag\JpegTables',
        0x09 => '\Woops\Swf\Tag\Set\BackgroundColor',
        0x0A => '\Woops\Swf\Tag\Define\Font',
        0x0B => '\Woops\Swf\Tag\Define\Text',
        0x0C => '\Woops\Swf\Tag\DoAction',
        0x0D => '\Woops\Swf\Tag\Define\Font\Info',
        0x0E => '\Woops\Swf\Tag\Define\Sound',
        0x0F => '\Woops\Swf\Tag\Start\Sound',
        0x11 => '\Woops\Swf\Tag\Define\Button\Sound',
        0x12 => '\Woops\Swf\Tag\Sound\Stream\Head',
        0x13 => '\Woops\Swf\Tag\Sound\Stream\Block',
        0x14 => '\Woops\Swf\Tag\Define\Bits\Lossless',
        0x15 => '\Woops\Swf\Tag\Define\Bits\Jpeg2',
        0x16 => '\Woops\Swf\Tag\Define\Shape2',
        0x17 => '\Woops\Swf\Tag\Define\Button\Cxform',
        0x18 => '\Woops\Swf\Tag\Protect',
        0x1A => '\Woops\Swf\Tag\Place\Object2',
        0x1C => '\Woops\Swf\Tag\Remove\Object2',
        0x20 => '\Woops\Swf\Tag\Define\Shape3',
        0x21 => '\Woops\Swf\Tag\Define\Text2',
        0x22 => '\Woops\Swf\Tag\Define\Button2',
        0x23 => '\Woops\Swf\Tag\Define\Bits\Jpeg3',
        0x24 => '\Woops\Swf\Tag\Define\Bits\Lossless2',
        0x25 => '\Woops\Swf\Tag\Define\EditText',
        0x27 => '\Woops\Swf\Tag\Define\Sprite',
        0x2B => '\Woops\Swf\Tag\FrameLabel',
        0x2D => '\Woops\Swf\Tag\Sound\Stream\Head2',
        0x2E => '\Woops\Swf\Tag\Define\Morph\Shape',
        0x30 => '\Woops\Swf\Tag\Define\Font2',
        0x38 => '\Woops\Swf\Tag\Export\Assets',
        0x39 => '\Woops\Swf\Tag\Import\Assets',
        0x3A => '\Woops\Swf\Tag\Enable\Debugger',
        0x3B => '\Woops\Swf\Tag\DoInitAction',
        0x3C => '\Woops\Swf\Tag\Define\VideoStream',
        0x3D => '\Woops\Swf\Tag\VideoFrame',
        0x3E => '\Woops\Swf\Tag\Define\Font\Info2',
        0x40 => '\Woops\Swf\Tag\Enable\Debugger2',
        0x41 => '\Woops\Swf\Tag\ScriptLimits',
        0x42 => '\Woops\Swf\Tag\Set\TabIndex',
        0x45 => '\Woops\Swf\Tag\FileAttributes',
        0x46 => '\Woops\Swf\Tag\Place\Object3',
        0x47 => '\Woops\Swf\Tag\Import\Assets2',
        0x49 => '\Woops\Swf\Tag\Define\Font\AlignZones',
        0x4A => '\Woops\Swf\Tag\CsmTextSettings',
        0x4B => '\Woops\Swf\Tag\Define\Font3',
        0x4C => '\Woops\Swf\Tag\SymbolClass',
        0x4D => '\Woops\Swf\Tag\Metadata',
        0x4E => '\Woops\Swf\Tag\Define\ScalingGrid',
        0x52 => '\Woops\Swf\Tag\DoAbc',
        0x53 => '\Woops\Swf\Tag\Define\Shape4',
        0x54 => '\Woops\Swf\Tag\Define\Morph\Shape2',
        0x56 => '\Woops\Swf\Tag\Define\SceneAndFrameLabelData',
        0x57 => '\Woops\Swf\Tag\Define\BinaryData',
        0x58 => '\Woops\Swf\Tag\Define\Font\Name',
        0x59 => '\Woops\Swf\Tag\Start\Sound2',
        0x5A => '\Woops\Swf\Tag\Define\Bits\Jpeg4',
        0x5B => '\Woops\Swf\Tag\Define\Font4'
    );
    
    /**
     * The SWF header
     */
    protected $_header      = NULL;
    
    /**
     * The SWF tags
     */
    protected $_tags        = array();
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Class constructor
     * 
     * @param   int     The SWF version
     * @return  void
     */
    public function __construct( $version = 10 )
    {
        // Creates a SWF header
        $this->_header = new Header( $version );
    }
    
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
     * Checks if a type is valid
     * 
     * @param   int     The SWF tag type
     * @return  boolean True if the tag type is valid, otherwise false
     */
    public static function isValidTagType( $type )
    {
        return isset( self::$_types[ ( int )$type ] );
    }
    
    /**
     * Gets the PHP classname for a SWF tag type
     * 
     * @param   int                         The SWF tag type
     * @return  string                      The PHP classname for the tag
     * @throws  Woops\Swf\File\Exception    If the tag type is invalid
     */
    public static function getTagClass( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks if the type is valid
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid tag type
            throw new File\Exception(
                'Invalid tag type (' . $type . ')',
                File\Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        // Returns the tag classname
        return self::$_types[ $type ];
    }
    
    /**
     * Creates a new SWF tag in the current SWF file instance
     * 
     * @param   int                         The SWF tag type (one of the TAG_XXX constant)
     * @return  Woops\Swf\Tag               The SWF tag object
     * @throws  Woops\Swf\File\Exception    If the tag type is invalid
     */
    public function newTag( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks if the type is valid
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid tag type
            throw new File\Exception(
                'Invalid tag type (' . $type . ')',
                File\Exception::EXCEPTION_INVALID_TAG_TYPE
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
     * Gets the SWF header object
     * 
     * @return  Woops\Swf\Header    The header object
     */
    public function getHeader()
    {
        return $this->_header;
    }
}
