<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * GIF file parser
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Gif
 */
class Woops_Gif_Parser extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The identifiers of the GIF blocks
     */
    const TRAILER                   = 0x3b;
    const IMAGE_DESCRIPTOR          = 0x2c;
    const EXTENSION                 = 0x21;
    const EXTENSION_GRAPHIC_CONTROL = 0xf9;
    const EXTENSION_COMMENT         = 0xfe;
    const EXTENSION_PLAIN_TEXT      = 0x01;
    const EXTENSION_APPLICATION     = 0xff;
    
    /**
     * An stdClass object that will be filled with the GIF informations
     */
    protected $_gifInfos            = NULL;
    
    /**
     * The binary stream
     */
    protected $_stream              = NULL;
    
    /**
     * The file path
     */
    protected $_filePath            = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The location of the file to parse
     * @return  void
     * @see     _parseFile
     */
    public function __construct( $file )
    {
        // Stores the file path
        $this->_filePath = $file;
        
        // Creates the binary stream
        $this->_stream   = new Woops_Binary_File_Stream( $file );
        
        // Parses the file
        $this->_parseFile();
        
        // Deletes the stream object
        unset( $this->_stream );
    }
    
    /**
     * 
     */
    protected function _getLogicalScreenDescriptor()
    {
        // Storage
        $lsd                         = new stdClass();
        
        // Gets the image dimensions
        $lsd->width                  = $this->_stream->littleEndianUnsignedShort();
        $lsd->height                 = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the packed fields
        $packedFields                = $this->_stream->unsignedChar();
        
        // Whether to global color table will follow 
        $lsd->globalColorTableFlag   = ( $packedFields & 0x80 ) >> 7;  // Mask is 1000 0000
        
        // The color resolution
        $lsd->colorResolution        = ( $packedFields & 0x70 ) >> 4;  // Mask is 0111 0000
        
        // Whether the global color table is sorted
        $lsd->sortFlag               = ( $packedFields & 0x08 ) >> 3;  // Mask is 0000 1000
        
        // The size of the global color table
        $lsd->sizeOfGlobalColorTable = ( $packedFields & 0x07 );       // Mask is 0000 0111
        
        // Gets the background color index
        $lsd->bgColorIndex           = $this->_stream->unsignedChar();
        
        // Gets the pixel aspect ratio
        $lsd->pixelAspectRatio       = $this->_stream->unsignedChar();
        
        // Returns the logical screen descriptor
        return $lsd;
    }
    
    /**
     * 
     */
    protected function _getColorTable( $size )
    {
        // Storage
        $table = array();
        
        // Computes the number of colors in the global color table
        $length = pow( 2, $size + 1 );
        
        // Process the global color table
        for( $i = 0; $i < $length; $i++ ) {
            
            // Storage
            $table[ $i ]        = new stdClass();
            
            // Gets the color values
            $red                = $this->_stream->unsignedChar();
            $green              = $this->_stream->unsignedChar();
            $blue               = $this->_stream->unsignedChar();
            
            // Gets the hexadecimal values
            $redHex             = dechex( $red );
            $greenHex           = dechex( $green );
            $blueHex            = dechex( $blue );
            
            // Completes each hexadecimal value if needed
            $redHex             = ( strlen( $redHex )   == 1 ) ? '0' . $redHex   : $redHex;
            $greenHex           = ( strlen( $greenHex ) == 1 ) ? '0' . $greenHex : $greenHex;
            $blueHex            = ( strlen( $blueHex )  == 1 ) ? '0' . $blueHex  : $blueHex;
            
            // Stores the color values
            $table[ $i ]->red   = $red;
            $table[ $i ]->green = $green;
            $table[ $i ]->blue  = $blue;
            $table[ $i ]->hex   = '#' . strtoupper( $redHex . $greenHex . $blueHex );
        }
        
        // Returns the global color table
        return $table;
    }
    
    /**
     * 
     */
    protected function _getImageSeparator()
    {
        // Storage
        $block = new stdClass();
        
        // Gets the position
        $block->left                  = $this->_stream->littleEndianUnsignedShort();
        $block->top                   = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the dimensions
        $block->width                 = $this->_stream->littleEndianUnsignedShort();
        $block->height                = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the packed fields
        $packedFields                 = $this->_stream->unsignedChar();
        
        // Whether to local color table will follow 
        $block->localColorTableFlag   = ( $packedFields & 0x80 ) >> 7;  // Mask is 1000 0000
        
        // Whether the image is interlaced
        $block->interlaceFlag         = ( $packedFields & 0x40 ) >> 6;  // Mask is 0100 0000
        
        // Whether the local color table is sorted
        $block->sortFlag              = ( $packedFields & 0x20 ) >> 5;  // Mask is 0010 0000
        
        // The size of the local color table
        $block->sizeOfLocalColorTable = ( $packedFields & 0x07 );       // Mask is 0000 0111
        
        // Checks if the local color table flag is set
        if( $block->localColorTableFlag ) {
            
            // Local color table follows - Gets its values
            $block->localColorTable   = $this->_getColorTable( $block->sizeOfLocalColorTable  );
        }
        
        // Gets the LZW minimum code size
        $block->lzwMinimumCodeSize    = $this->_stream->unsignedChar();
        
        // Gets the image data
        $block->imageData             = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getDataSubBlocks()
    {
        // Storage
        $data = array();
        
        // Gets the next block size
        $blockSize     = $this->_stream->unsignedChar();
        
        // Process the data blocks until the end of the parent block
        while( $blockSize !== 0x00 ) {
            
            // Storage
            $block       = new stdClass();
            
            // Adds the block size
            $block->size = $blockSize;
            
            // For now, do not process or store the block data
            $this->_stream->seek( $blockSize, Woops_Binary_File_Stream::SEEK_CUR );
            
            // Adds the data block
            $data[]      = $block;
            
            // Gets the next block size
            $blockSize = $this->_stream->unsignedChar();
        }
        
        // Returns the data of the sub blocks
        return $data;
    }
    
    /**
     * 
     */
    protected function _getGraphicControlExtension()
    {
        // Storage
        $block = new stdClass();
        
        // Gets the block size
        $block->size                  = $this->_stream->unsignedChar();
        
        // Gets the packed fields
        $packedFields                 = $this->_stream->unsignedChar();
        
        // The way in which the graphic is to be treated after being displayed
        $block->disposalMethod        = ( $packedFields & 0x1c ) >> 2;  // Mask is 0001 1100
        
        // Whether an user input is expected
        $block->userInputFlag         = ( $packedFields & 0x02 ) >> 1;  // Mask is 0000 0010
        
        // Whether a transparency index is given
        $block->transparentColorFlag  = ( $packedFields & 0x01 );       // Mask is 0000 0001
        
        // Gets the delay time
        $block->delayTime             = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the transparent color index
        $block->transparentColorIndex = $this->_stream->unsignedChar();
        
        // Block terminator
        $this->_stream->read( 1 );
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getCommentExtension()
    {
        // Storage
        $block              = new stdClass();
        
        // Gets the block size
        $block->size        = $this->_stream->unsignedChar();
        
        // Gets the comment data blocks
        $block->commentData = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getPlainTextExtension()
    {
        // Storage
        $block                           = new stdClass();
        
        // Gets the block size
        $block->size                     = $this->_stream->unsignedChar();
        
        // Gets the left position of the text grid
        $block->textGridLeftPosition     = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the top position of the text grid
        $block->textGridTopPosition      = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the width of the text grid
        $block->textGridWidth            = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the height of the text grid
        $block->textGridHeight           = $this->_stream->littleEndianUnsignedShort();
        
        // Gets the width of the character cell
        $block->characterCellWidth       = $this->_stream->unsignedChar();
        
        // Gets the height of the character cell
        $block->characterCellHeight      = $this->_stream->unsignedChar();
        
        // Gets the color index for the foreground color
        $block->textForegroundColorIndex = $this->_stream->unsignedChar();
        
        // Gets the color index for the background color
        $block->textBackgroundColorIndex = $this->_stream->unsignedChar();
        
        // Gets the plain text data blocks
        $block->plainTextData            = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _getApplicationExtension()
    {
        // Storage
        $block = new stdClass();
        
        // Gets the block size
        $block->size                      = $this->_stream->unsignedChar();
        
        // Gets the application identifier
        $block->applicationIdentifier     = $this->_stream->read( 8 );
        
        // Gets the application identifier code
        $block->applicationIdentifierCode = $this->_stream->read( 3 );
        
        // Gets the application data blocks
        $block->applicationData           = $this->_getDataSubBlocks();
        
        // Return the block informations
        return $block;
    }
    
    /**
     * 
     */
    protected function _parseFile()
    {
        // Checks the GIF signature
        if( $this->_stream->read( 3 ) !== 'GIF' ) {
            
            // Wrong file type
            throw new Woops_Gif_Parser_Exception(
                'File ' . $this->_filePath . ' is not a GIF file.',
                Woops_Gif_Parser_Exception::EXCEPTION_NOT_GIF
            );
        }
        
        // Storage
        $infos                          = new stdClass();
        
        // Gets the GIF version
        $infos->version                 = $this->_stream->read( 3 );
        
        // Gets the logical screen descriptor
        $infos->logicalScreenDescriptor = $this->_getLogicalScreenDescriptor();
        
        // Checks if the global color table flag is set
        if( $infos->logicalScreenDescriptor->globalColorTableFlag ) {
            
            // Global color table follows - Gets its values
            $infos->globalColorTable    = $this->_getColorTable( $infos->logicalScreenDescriptor->sizeOfGlobalColorTable );
        }
        
        // Gets the identifier of the next block
        $blockId = $this->_stream->unsignedChar();
        
        // Process the blocks until the trailer (0x3b) is reached
        while( $blockId !== self::TRAILER ) {
            
            // Parses the block
            $this->_parseBlock( $blockId, $infos );
            
            // Gets the identifier of the next block
            $blockId = $this->_stream->unsignedChar();
        }
        
        // Stores the informations
        $this->_gifInfos = $infos;
    }
    
    /**
     * 
     */
    protected function _parseBlock( $id, stdClass $infos )
    {
        // Checks the block identifier
        switch( $id ) {
            
            // Image descriptor block
            case self::IMAGE_DESCRIPTOR :
                
                // Checks if the storage array exists
                if( !isset( $infos->images ) ) {
                    
                    // Creates the storage array
                    $infos->images = array();
                }
                
                // Adds the storage object for the current image
                $image                 = new stdClass();
                
                // Gets the image separator block
                $image->imageSeparator = $this->_getImageSeparator();
                
                // Adds the current image
                $infos->images[]       = $image;
                break;
            
            // Extension block
            case self::EXTENSION :
                
                // Gets the extension block identifier
                $extBlockId = $this->_stream->unsignedChar();
                
                // Parses the extension block
                $this->_parseExtensionBlock( $extBlockId, $infos );
                break;
            
            // Unknown block
            default:
                
                // Invalid block identifier
                throw new Woops_Gif_Parser_Exception(
                    'Invalid GIF block identifier: \'0x' . dechex( $id ) . '\'.',
                    Woops_Gif_Parser_Exception::EXCEPTION_BAD_ID
                );
                break;
        }
    }
    
    /**
     * 
     */
    protected function _parseExtensionBlock( $id, stdClass $infos )
    {
        // Checks the extension block identifier
        switch( $id ) {
            
            // Graphic control extension block
            case self::EXTENSION_GRAPHIC_CONTROL:
                
                // Checks if the storage array exists
                if( !isset( $infos->graphicControlExtension ) ) {
                    
                    // Creates the storage array
                    $infos->graphicControlExtension = array();
                }
                
                // Gets the graphic control extension block
                $infos->graphicControlExtension[] = $this->_getGraphicControlExtension();
                break;
            
            // Comment extension block
            case self::EXTENSION_COMMENT:
                
                // Checks if the storage array exists
                if( !isset( $infos->commentExtension ) ) {
                    
                    // Creates the storage array
                    $infos->commentExtension = array();
                }
                
                // Gets the comment extension block
                $infos->commentExtension[] = $this->_getCommentExtension();
                break;
            
            // Plain text extension block
            case self::EXTENSION_PLAIN_TEXT:
                
                // Checks if the storage array exists
                if( !isset( $infos->plainTextExtension ) ) {
                    
                    // Creates the storage array
                    $infos->plainTextExtension = array();
                }
                
                // Gets the plain text extension block
                $infos->plainTextExtension[] = $this->_getPlainTextExtension();
                break;
            
            // Application extension block
            case self::EXTENSION_APPLICATION:
                
                // Checks if the storage array exists
                if( !isset( $infos->applicationExtension ) ) {
                    
                    // Creates the storage array
                    $infos->applicationExtension = array();
                }
                
                // Gets the application extension block
                $infos->applicationExtension[] = $this->_getApplicationExtension();
                break;
            
            // Unknown sub block
            default:
                
                // Invalid sub block identifier
                throw new Woops_Gif_Parser_Exception(
                    'Invalid GIF extension block identifier: \'0x' . dechex( $id ) . '\'.',
                    Woops_Gif_Parser_Exception::EXCEPTION_BAD_EXT_ID
                );
                break;
        }
    }
    
    /**
     * 
     */
    public function getInfos()
    {
        return clone( $this->_gifInfos );
    }
}
