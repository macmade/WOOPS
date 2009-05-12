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
namespace Woops\Tiff;

/**
 * TIFF Image File Directory (IFD)
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff
 */
class Ifd extends \Woops\Core\Object implements \Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The TIFF tag types in TIFF revision 6
     */
    const TAG_NEWSUBFILETYPE                    = 0x00FE;
    const TAG_SUBFILETYPE                       = 0x00FF;
    const TAG_IMAGE_WIDTH                       = 0x0100;
    const TAG_IMAGE_LENGTH                      = 0x0101;
    const TAG_BITSPERSAMPLE                     = 0x0102;
    const TAG_COMPRESSION                       = 0x0103;
    const TAG_PHOTOMETRICINTERPRETATION         = 0x0106;
    const TAG_THRESHHOLDING                     = 0x0107;
    const TAG_CELL_WIDTH                        = 0x0108;
    const TAG_CELL_LENGTH                       = 0x0109;
    const TAG_FILLORDER                         = 0x010A;
    const TAG_DOCUMENTNAME                      = 0x010D;
    const TAG_IMAGE_DESCRIPTION                 = 0x010E;
    const TAG_MAKE                              = 0x010F;
    const TAG_MODEL                             = 0x0110;
    const TAG_STRIP_OFFSETS                     = 0x0111;
    const TAG_ORIENTATION                       = 0x0112;
    const TAG_SAMPLESPERPIXEL                   = 0x0115;
    const TAG_ROWSPERSTRIP                      = 0x0116;
    const TAG_STRIP_BYTECOUNTS                  = 0x0117;
    const TAG_MINSAMPLEVALUE                    = 0x0118;
    const TAG_MAXSAMPLEVALUE                    = 0x0119;
    const TAG_X_RESOLUTION                      = 0x011A;
    const TAG_Y_RESOLUTION                      = 0x011B;
    const TAG_PLANARCONFIGURATION               = 0x011C;
    const TAG_PAGE_NAME                         = 0x011D;
    const TAG_X_POSITION                        = 0x011E;
    const TAG_Y_POSITION                        = 0x011F;
    const TAG_FREE_OFFSETS                      = 0x0120;
    const TAG_FREE_BYTECOUNTS                   = 0x0121;
    const TAG_GRAY_RESPONSE_UNIT                = 0x0122;
    const TAG_GRAY_RESPONSE_CURVE               = 0x0123;
    const TAG_T4OPTIONS                         = 0x0124;
    const TAG_T6OPTIONS                         = 0x0125;
    const TAG_RESOLUTIONUNIT                    = 0x0128;
    const TAG_PAGE_NUMBER                       = 0x0129;
    const TAG_TRANSFERFUNCTION                  = 0x012D;
    const TAG_SOFTWARE                          = 0x0131;
    const TAG_DATETIME                          = 0x0132;
    const TAG_ARTIST                            = 0x013B;
    const TAG_HOSTCOMPUTER                      = 0x013C;
    const TAG_PREDICTOR                         = 0x013D;
    const TAG_WHITEPOINT                        = 0x013E;
    const TAG_PRIMARYCHROMATICITIES             = 0x013F;
    const TAG_COLORMAP                          = 0x0140;
    const TAG_HALFTONEHINTS                     = 0x0141;
    const TAG_TILE_WIDTH                        = 0x0142;
    const TAG_TILE_LENGTH                       = 0x0143;
    const TAG_TILE_OFFSETS                      = 0x0144;
    const TAG_TILE_BYTECOUNTS                   = 0x0145;
    const TAG_INK_SET                           = 0x014C;
    const TAG_INK_NAMES                         = 0x014D;
    const TAG_NUMBEROFINKS                      = 0x014E;
    const TAG_DOTRANGE                          = 0x0150;
    const TAG_TARGETPRINTER                     = 0x0151;
    const TAG_EXTRASAMPLES                      = 0x0152;
    const TAG_SAMPLEFORMAT                      = 0x0153;
    const TAG_SMINSAMPLEVALUE                   = 0x0154;
    const TAG_SMAXSAMPLEVALUE                   = 0x0155;
    const TAG_TRANSFERRANGE                     = 0x0156;
    const TAG_JPEG_PROC                         = 0x0200;
    const TAG_JPEG_INTERCHANGE_FORMAT           = 0x0201;
    const TAG_JPEG_INTERCHANGE_FORMAT_LENGTH    = 0x0202;
    const TAG_JPEG_RESTARTINTERVAL              = 0x0203;
    const TAG_JPEG_LOSSLESSPREDICTORS           = 0x0205;
    const TAG_JPEG_POINTTRANSFORMS              = 0x0206;
    const TAG_JPEG_QTABLES                      = 0x0207;
    const TAG_JPEG_DCTABLES                     = 0x0208;
    const TAG_JPEG_ACTABLES                     = 0x0209;
    const TAG_YCBCR_COEFFICIENTS                = 0x0211;
    const TAG_YCBCR_SUBSAMPLING                 = 0x0212;
    const TAG_YCBCR_POSITIONING                 = 0x0213;
    const TAG_REFERENCEBLACKWHITE               = 0x0214;
    const TAG_COPYRIGHT                         = 0x8298;
    
    /**
     * The third-party TIFF tag types
     */
    const TAG_XMP                               = 0x02BC;
    const TAG_IPTC                              = 0x83BB;
    const TAG_PHOTOSHOP                         = 0x8649;
    const TAG_ICC_PROFILE                       = 0x8773;
    
    /**
     * The EXIF pointer TIFF tag types
     */
    const TAG_EXIF_IFD_POINTER                  = 0x8769;
    const TAG_EXIF_GPS_IFD_POINTER              = 0x8825;
    const TAG_EXIF_INTEROPERABILITY_IFD_POINTER = 0xA005;
    
    /**
     * The types of the TIFF tags, with their corresponding PHP class
     */
    protected static $_types = array(
        
        // Tags in TIFF revision 6
        0x00FE => 'Woops\Tiff\Tag\NewSubfileType',
        0x00FF => 'Woops\Tiff\Tag\SubfileType',
        0x0100 => 'Woops\Tiff\Tag\Image\Width',
        0x0101 => 'Woops\Tiff\Tag\Image\Length',
        0x0102 => 'Woops\Tiff\Tag\BitsPerSample',
        0x0103 => 'Woops\Tiff\Tag\Compression',
        0x0106 => 'Woops\Tiff\Tag\PhotometricInterpretation',
        0x0107 => 'Woops\Tiff\Tag\Threshholding',
        0x0108 => 'Woops\Tiff\Tag\Cell\Width',
        0x0109 => 'Woops\Tiff\Tag\Cell\Length',
        0x010A => 'Woops\Tiff\Tag\FillOrder',
        0x010D => 'Woops\Tiff\Tag\DocumentName',
        0x010E => 'Woops\Tiff\Tag\Image\Description',
        0x010F => 'Woops\Tiff\Tag\Make',
        0x0110 => 'Woops\Tiff\Tag\Model',
        0x0111 => 'Woops\Tiff\Tag\Strip\Offsets',
        0x0112 => 'Woops\Tiff\Tag\Orientation',
        0x0115 => 'Woops\Tiff\Tag\SamplesPerPixel',
        0x0116 => 'Woops\Tiff\Tag\RowsPerStrip',
        0x0117 => 'Woops\Tiff\Tag\Strip\ByteCounts',
        0x0118 => 'Woops\Tiff\Tag\MinSampleValue',
        0x0119 => 'Woops\Tiff\Tag\MaxSampleValue',
        0x011A => 'Woops\Tiff\Tag\X\Resolution',
        0x011B => 'Woops\Tiff\Tag\Y\Resolution',
        0x011C => 'Woops\Tiff\Tag\PlanarConfiguration',
        0x011D => 'Woops\Tiff\Tag\Page\Name',
        0x011E => 'Woops\Tiff\Tag\X\Position',
        0x011F => 'Woops\Tiff\Tag\Y\Position',
        0x0120 => 'Woops\Tiff\Tag\Free\Offsets',
        0x0121 => 'Woops\Tiff\Tag\Free\ByteCounts',
        0x0122 => 'Woops\Tiff\Tag\Gray\Response\Unit',
        0x0123 => 'Woops\Tiff\Tag\Gray\Response\Curve',
        0x0124 => 'Woops\Tiff\Tag\T4Options',
        0x0125 => 'Woops\Tiff\Tag\T6Options',
        0x0128 => 'Woops\Tiff\Tag\ResolutionUnit',
        0x0129 => 'Woops\Tiff\Tag\Page\Number',
        0x012D => 'Woops\Tiff\Tag\TransferFunction',
        0x0131 => 'Woops\Tiff\Tag\Software',
        0x0132 => 'Woops\Tiff\Tag\DateTime',
        0x013B => 'Woops\Tiff\Tag\Artist',
        0x013C => 'Woops\Tiff\Tag\HostComputer',
        0x013D => 'Woops\Tiff\Tag\Predictor',
        0x013E => 'Woops\Tiff\Tag\WhitePoint',
        0x013F => 'Woops\Tiff\Tag\PrimaryChromaticities',
        0x0140 => 'Woops\Tiff\Tag\ColorMap',
        0x0141 => 'Woops\Tiff\Tag\HalftoneHints',
        0x0142 => 'Woops\Tiff\Tag\Tile\Width',
        0x0143 => 'Woops\Tiff\Tag\Tile\Length',
        0x0144 => 'Woops\Tiff\Tag\Tile\Offsets',
        0x0145 => 'Woops\Tiff\Tag\Tile\ByteCounts',
        0x014C => 'Woops\Tiff\Tag\Ink\Set',
        0x014D => 'Woops\Tiff\Tag\Ink\Names',
        0x014E => 'Woops\Tiff\Tag\NumberOfInks',
        0x0150 => 'Woops\Tiff\Tag\DotRange',
        0x0151 => 'Woops\Tiff\Tag\TargetPrinter',
        0x0152 => 'Woops\Tiff\Tag\ExtraSamples',
        0x0153 => 'Woops\Tiff\Tag\SampleFormat',
        0x0154 => 'Woops\Tiff\Tag\SMinSampleValue',
        0x0155 => 'Woops\Tiff\Tag\SMaxSampleValue',
        0x0156 => 'Woops\Tiff\Tag\TransferRange',
        0x0200 => 'Woops\Tiff\Tag\Jpeg\Proc',
        0x0201 => 'Woops\Tiff\Tag\Jpeg\Interchange\Format',
        0x0202 => 'Woops\Tiff\Tag\Jpeg\Interchange\Format\Length',
        0x0203 => 'Woops\Tiff\Tag\Jpeg\RestartInterval',
        0x0205 => 'Woops\Tiff\Tag\Jpeg\LosslessPredictors',
        0x0206 => 'Woops\Tiff\Tag\Jpeg\PointTransforms',
        0x0207 => 'Woops\Tiff\Tag\Jpeg\QTables',
        0x0208 => 'Woops\Tiff\Tag\Jpeg\DcTables',
        0x0209 => 'Woops\Tiff\Tag\Jpeg\AcTables',
        0x0211 => 'Woops\Tiff\Tag\YCbCr\Coefficients',
        0x0212 => 'Woops\Tiff\Tag\YCbCr\SubSampling',
        0x0213 => 'Woops\Tiff\Tag\YCbCr\Positioning',
        0x0214 => 'Woops\Tiff\Tag\ReferenceBlackWhite',
        0x8298 => 'Woops\Tiff\Tag\Copyright',
        
        // Third-party tags
        0x02BC => 'Woops\Tiff\Tag\Xmp',
        0x83BB => 'Woops\Tiff\Tag\Iptc',
        0x8649 => 'Woops\Tiff\Tag\Photoshop',
        0x8773 => 'Woops\Tiff\Tag\IccProfile',
        
        // EXIF pointer tags
        0x8769 => 'Woops\Tiff\Tag\Exif\Ifd\Pointer',
        0x8825 => 'Woops\Tiff\Tag\Exif\Gps\Ifd\Pointer',
        0xA005 => 'Woops\Tiff\Tag\Exif\Interoperability\Ifd\Pointer'
        
    );
    
    /**
     * The TIFF file
     */
    protected $_file         = NULL;
    
    /**
     * The TIFF header
     */
    protected $_header       = NULL;
    
    /**
     * The TIFF tags contained in the IFD
     */
    protected $_tags         = array();
    
    /**
     * Offset of the next IFD
     */
    protected $_offset       = 0;
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Class constructor
     * 
     * @param   Woops\Tiff\File The TIFF file in which the IFD is contained
     * @return  void
     */
    public function __construct( File $file )
    {
        $this->_file   = $file;
        $this->_header = $this->_file->getHeader();
    }
    
    /**
     * Gets the current tag object (SPL Iterator method)
     * 
     * @return  Woops\Tiff\Tag  The current TIFF tag object
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
     * @return  int     The index of the current TIFF tag
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next tag object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next TIFF tag, otherwise false
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
    public function processData( Binary\Stream $stream )
    {
        // Resets the tag array
        $this->_tags = array();
        
        // Gets the number of directory entries
        $entries     = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedShort() : $stream->littleEndianUnsignedShort();
        
        // Process each directory entry
        for( $i = 0; $i < $entries; $i++ ) {
            
            // Gets the tag type
            $type = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedShort() : $stream->littleEndianUnsignedShort();
            
            // Prevents unknown TIFF tags to create an exceprion
            try {
                
                // Creates a new tag
                $tag = $this->newTag( $type );
                
            } catch( Ifd\Exception $e ) {
                
                // Checks if the exception was made for an unknown TIFF tag
                if( $e->getCode() !== Ifd\Exception::EXCEPTION_INVALID_TAG_TYPE ) {
                    
                    // No - Throws the exception again
                    throw $e;
                }
                
                // Creates an unknown tag object, and adds it
                $tag = new UnknownTag( $this->_file, $type );
                $this->addTag( $tag );
            }
            
            // Process the tag data
            $tag->processData( $stream );
            
            // Support for the EXIF IFD pointer tags
            if(    $type === self::TAG_EXIF_IFD_POINTER
                || $type === self::TAG_EXIF_GPS_IFD_POINTER
                || $type === self::TAG_EXIF_INTEROPERABILITY_IFD_POINTER
             ) {
                
                // Stores the current offset
                $offset    = $stream->getOffset();
                
                // Gets the IFD offset
                $ifdOffset = $tag->getValue();
                
                // Moves to the IFD offset
                $stream->seek( $ifdOffset, Binary\Stream::SEEK_SET );
                
                // Exif IFD classname
                $classname = ( $type === self::TAG_EXIF_IFD_POINTER ) ? 'Woops\Exif\Tiff\Ifd' : ( ( $type === self::TAG_EXIF_GPS_IFD_POINTER ) ? 'Woops\Exif\Tiff\Gps\Ifd' : 'Woops\Exif\Tiff\Interoperability\Ifd' );
                
                // Creates the EXIF IFD
                $ifd = $this->_file->newIfd( $classname );
                
                // Process the IFD data
                $ifd->processData( $stream );
                
                // Rewinds the stream
                $stream->seek( $offset, Binary\Stream::SEEK_SET );
                
            }
        }
        
        // Gets the offset of the next IFD
        $this->_offset = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedLong() : $stream->littleEndianUnsignedLong();
    }
    
    /**
     * Gets the offset of the next IDF (Image File Directory)
     * 
     * @return  int     The offset of the first IDF (Image File Directory)
     */
    public function getNextIfdOffset()
    {
        return $this->_offset;
    }
    
    /**
     * Sets the offset of the next IDF (Image File Directory)
     * 
     * @param   int     The offset of the first IDF (Image File Directory)
     * @return  void
     */
    public function setNextIfdOffset( $value )
    {
        $this->_offset = ( int )$value;
    }
    
    /**
     * Creates a new tag in the IFD
     * 
     * @param   int                         The tag type (one of the TAG_XXX constant)
     * @return  Woops\Tiff\Tag              The tag object
     * @throws  Woops\Tiff\Ifd\Exception    If the tag type is invalid
     */
    public function newTag( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks the value type
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid value type
            throw new Ifd\Exception(
                'Invalid tag type (' .  $type . ')',
                Ifd\Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        // Gets the PHP class for the tag type
        $tagClass      = self::$_types[ $type ];
        
        // Creates a new tag, and stores it
        $tag           = new $tagClass( $this->_file );
        $this->_tags[] = $tag;
        
        // Returns the tag object
        return $tag;
    }
    
    /**
     * Adds a tag in the IFD
     * 
     * @param   Woops\Tiff\Tag  The tag object
     * @return  void
     */
    public function addTag( Tag $tag )
    {
        $this->_tags[] = $tag;
    }
}
