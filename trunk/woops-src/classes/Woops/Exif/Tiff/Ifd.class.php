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
namespace Woops\Exif\Tiff;

/**
 * EXIF TIFF Image File Directory (IFD)
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Exif.Tiff
 */
class Ifd extends \Woops\Core\Object implements \Woops\Tiff\Ifd\ObjectInterface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The EXIF TIFF tags
     */
    const TAG_EXIF_VERSION                = 0x9000;
    const TAG_FLASHPIX_VERSION            = 0xA000;
    const TAG_COLOR_SPACE                 = 0xA001;
    const TAG_COMPONENTS_CONFIGURATION    = 0x9101;
    const TAG_COMPRESSED_BITS_PER_PIXEL   = 0x9102;
    const TAG_PIXEL_X_DIMENSION           = 0xA002;
    const TAG_PIXEL_Y_DIMENSION           = 0xA003;
    const TAG_MAKER_NOTE                  = 0x927C;
    const TAG_USER_COMMENT                = 0x9286;
    const TAG_RELATED_SOUND_FILE          = 0xA004;
    const TAG_DATE_TIME_ORIGINAL          = 0x9003;
    const TAG_DATE_TIME_DIGITIZED         = 0x9004;
    const TAG_SUB_SEC_TIME                = 0x9290;
    const TAG_SUB_SEC_TIME_ORIGINAL       = 0x9291;
    const TAG_SUB_SEC_TIME_DIGITIZED      = 0x9292;
    const TAG_EXPOSURE_TIME               = 0x829A;
    const TAG_F_NUMBER                    = 0x829D;
    const TAG_EXPOSURE_PROGRAM            = 0x8822;
    const TAG_SPECTRAL_SENSITIVITY        = 0x8824;
    const TAG_ISO_SPEED_RATINGS           = 0x8827;
    const TAG_OECF                        = 0x8828;
    const TAG_SHUTTER_SPEED_VALUE         = 0x9201;
    const TAG_APERTURE_VALUE              = 0x9202;
    const TAG_BRIGHTNESS_VALUE            = 0x9203;
    const TAG_EXPOSURE_BIAS_VALUE         = 0x9204;
    const TAG_MAX_APERTURE_VALUE          = 0x9205;
    const TAG_SUBJECT_DISTANCE            = 0x9206;
    const TAG_METERING_MODE               = 0x9207;
    const TAG_LIGHT_SOURCE                = 0x9208;
    const TAG_FLASH                       = 0x9209;
    const TAG_FOCAL_LENGTH                = 0x920A;
    const TAG_SUBJECT_AREA                = 0x9214;
    const TAG_FLASH_ENERGY                = 0xA20B;
    const TAG_SPATIAL_FREQUENCY_RESPONSE  = 0xA20C;
    const TAG_FOCAL_PLANE_X_RESOLUTION    = 0xA20E;
    const TAG_FOCAL_PLANE_Y_RESOLUTION    = 0xA20F;
    const TAG_FOCAL_PLANE_RESOLUTION_UNIT = 0xA210;
    const TAG_SUBJECT_LOCATION            = 0xA214;
    const TAG_EXPOSURE_INDEX              = 0xA215;
    const TAG_SENSING_METHOD              = 0xA217;
    const TAG_FILE_SOURCE                 = 0xA300;
    const TAG_SCENE_TYPE                  = 0xA301;
    const TAG_CFA_PATTERN                 = 0xA302;
    const TAG_CUSTOM_RENDERED             = 0xA401;
    const TAG_EXPOSURE_MODE               = 0xA402;
    const TAG_WHITE_BALANCE               = 0xA403;
    const TAG_DIGITAL_ZOOM_RATIO          = 0xA404;
    const TAG_FOCAL_LENGTH_IN35MM_FILM    = 0xA405;
    const TAG_SCENE_CAPTURE_TYPE          = 0xA406;
    const TAG_GAIN_CONTROL                = 0xA407;
    const TAG_CONTRAST                    = 0xA408;
    const TAG_SATURATION                  = 0xA409;
    const TAG_SHARPNESS                   = 0xA40A;
    const TAG_DEVICE_SETTING_DESCRIPTION  = 0xA40B;
    const TAG_SUBJECT_DISTANCE_RANGE      = 0xA40C;
    const TAG_IMAGE_UNIQUE_ID             = 0xA420;
    
    /**
     * The types of the EXIF TIFF tags, with their corresponding PHP class
     */
    protected static $_types = array
    (
        // Tags Relating to Version
        0x9000 => 'Woops\Exif\Tiff\Tag\ExifVersion',
        0xA000 => 'Woops\Exif\Tiff\Tag\FlashpixVersion',
        
        // Tag Relating to Image Data Characteristics
        0xA001 => 'Woops\Exif\Tiff\Tag\ColorSpace',
        
        // Tags Relating to Image Configuration
        0x9101 => 'Woops\Exif\Tiff\Tag\ComponentsConfiguration',
        0x9102 => 'Woops\Exif\Tiff\Tag\CompressedBitsPerPixel',
        0xA002 => 'Woops\Exif\Tiff\Tag\Pixel\XDimension',
        0xA003 => 'Woops\Exif\Tiff\Tag\Pixel\YDimension',
        
        // Tags Relating to User Information
        0x927C => 'Woops\Exif\Tiff\Tag\MakerNote',
        0x9286 => 'Woops\Exif\Tiff\Tag\UserComment',
        
        // Tag Relating to Related File Information
        0xA004 => 'Woops\Exif\Tiff\Tag\RelatedSoundFile',
        
        // Tags Relating to Date and Time
        0x9003 => 'Woops\Exif\Tiff\Tag\DateTime\Original',
        0x9004 => 'Woops\Exif\Tiff\Tag\DateTime\Digitized',
        0x9290 => 'Woops\Exif\Tiff\Tag\SubSec\Time',
        0x9291 => 'Woops\Exif\Tiff\Tag\SubSec\Time\Original',
        0x9292 => 'Woops\Exif\Tiff\Tag\SubSec\Time\Digitized',
        
        // Tags Relating to Picture-Taking Conditions
        0x829A => 'Woops\Exif\Tiff\Tag\Exposure\Time',
        0x829D => 'Woops\Exif\Tiff\Tag\FNumber',
        0x8822 => 'Woops\Exif\Tiff\Tag\Exposure\Program',
        0x8824 => 'Woops\Exif\Tiff\Tag\SpectralSensitivity',
        0x8827 => 'Woops\Exif\Tiff\Tag\IsoSpeedRatings',
        0x8828 => 'Woops\Exif\Tiff\Tag\Oecf',
        0x9201 => 'Woops\Exif\Tiff\Tag\ShutterSpeedValue',
        0x9202 => 'Woops\Exif\Tiff\Tag\ApertureValue',
        0x9203 => 'Woops\Exif\Tiff\Tag\BrightnessValue',
        0x9204 => 'Woops\Exif\Tiff\Tag\Exposure\BiasValue',
        0x9205 => 'Woops\Exif\Tiff\Tag\MaxApertureValue',
        0x9206 => 'Woops\Exif\Tiff\Tag\Subject\Distance',
        0x9207 => 'Woops\Exif\Tiff\Tag\MeteringMode',
        0x9208 => 'Woops\Exif\Tiff\Tag\LightSource',
        0x9209 => 'Woops\Exif\Tiff\Tag\Flash',
        0x920A => 'Woops\Exif\Tiff\Tag\Focal\Length',
        0x9214 => 'Woops\Exif\Tiff\Tag\Subject\Area',
        0xA20B => 'Woops\Exif\Tiff\Tag\Flash\Energy',
        0xA20C => 'Woops\Exif\Tiff\Tag\SpatialFrequencyResponse',
        0xA20E => 'Woops\Exif\Tiff\Tag\Focal\Plane\XResolution',
        0xA20F => 'Woops\Exif\Tiff\Tag\Focal\Plane\YResolution',
        0xA210 => 'Woops\Exif\Tiff\Tag\Focal\Plane\ResolutionUnit',
        0xA214 => 'Woops\Exif\Tiff\Tag\Subject\Location',
        0xA215 => 'Woops\Exif\Tiff\Tag\Exposure\Index',
        0xA217 => 'Woops\Exif\Tiff\Tag\SensingMethod',
        0xA300 => 'Woops\Exif\Tiff\Tag\FileSource',
        0xA301 => 'Woops\Exif\Tiff\Tag\Scene\Type',
        0xA302 => 'Woops\Exif\Tiff\Tag\CfaPattern',
        0xA401 => 'Woops\Exif\Tiff\Tag\CustomRendered',
        0xA402 => 'Woops\Exif\Tiff\Tag\Exposure\Mode',
        0xA403 => 'Woops\Exif\Tiff\Tag\WhiteBalance',
        0xA404 => 'Woops\Exif\Tiff\Tag\DigitalZoomRatio',
        0xA405 => 'Woops\Exif\Tiff\Tag\Focal\LengthIn35mmFilm',
        0xA406 => 'Woops\Exif\Tiff\Tag\Scene\CaptureType',
        0xA407 => 'Woops\Exif\Tiff\Tag\GainControl',
        0xA408 => 'Woops\Exif\Tiff\Tag\Contrast',
        0xA409 => 'Woops\Exif\Tiff\Tag\Saturation',
        0xA40A => 'Woops\Exif\Tiff\Tag\Sharpness',
        0xA40B => 'Woops\Exif\Tiff\Tag\DeviceSettingDescription',
        0xA40C => 'Woops\Exif\Tiff\Tag\Subject\DistanceRange',
        
        // Other Tags
        0xA420 => 'Woops\Exif\Tiff\Tag\ImageUniqueId'
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
    public function __construct( \Woops\Tiff\File $file )
    {
        $this->_file   = $file;
        $this->_header = $this->_file->getHeader();
    }
    
    /**
     * Gets the current tag object (SPL Iterator method)
     * 
     * @return  Woops\Tiff\Tag  The current SWF tag object
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
    public function processData( \Woops\Tiff\Binary\Stream $stream )
    {
        // Resets the tag array
        $this->_tags = array();
        
        // Gets the number of directory entries
        $entries     = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedShort() : $stream->littleEndianUnsignedShort();
        
        // Process each directory entry
        for( $i = 0; $i < $entries; $i++ )
        {
            // Gets the tag type
            $type = ( $this->_header->isBigEndian() ) ? $stream->bigEndianUnsignedShort() : $stream->littleEndianUnsignedShort();
            
            // Prevents unknown TIFF tags to create an exceprion
            try
            {
                // Creates a new tag
                $tag = $this->newTag( $type );
            }
            catch( Ifd\Exception $e )
            {
                // Checks if the exception was made for an unknown TIFF tag
                if( $e->getCode() !== Ifd\Exception::EXCEPTION_INVALID_TAG_TYPE )
                {
                    // No - Throws the exception again
                    throw $e;
                }
                
                // Creates an unknown tag object, and adds it
                $tag = new UnknownTag( $this->_file, $type );
                $this->addTag( $tag );
            }
            
            // Process the tag data
            $tag->processData( $stream );
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
     * @param   int                             The tag type (one of the TAG_XXX constant)
     * @return  Woops\Tiff\Tag                  The tag object
     * @throws  Woops\Exif\Tiff\Ifd_Exception   If the tag type is invalid
     */
    public function newTag( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks the value type
        if( !isset( self::$_types[ $type ] ) )
        {
            // Error - Invalid value type
            throw new Ifd\Exception
            (
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
     * @param   Woops\Tiff\Tag The tag object
     * @return  void
     */
    public function addTag( \Woops\Tiff\Tag $tag )
    {
        $this->_tags[] = $tag;
    }
}
