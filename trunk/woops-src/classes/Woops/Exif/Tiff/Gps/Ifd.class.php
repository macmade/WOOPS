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

/**
 * EXIF TIFF Image File Directory (IFD)
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Exif.Tiff.Gps
 */
class Woops_Exif_Tiff_Gps_Ifd implements Woops_Tiff_Ifd_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The EXIF GPS TIFF tags
     */
    const TAG_VERSION_ID         = 0x0000;
    const TAG_LATITUDE_REF       = 0x0001;
    const TAG_LATITUDE           = 0x0002;
    const TAG_LONGITUDE_REF      = 0x0003;
    const TAG_LONGITUDE          = 0x0004;
    const TAG_ALTITUDE_REF       = 0x0005;
    const TAG_ALTITUDE           = 0x0006;
    const TAG_TIME_STAMP         = 0x0007;
    const TAG_SATELLITES         = 0x0008;
    const TAG_STATUS             = 0x0009;
    const TAG_MEASURE_MODE       = 0x000A;
    const TAG_DOP                = 0x000B;
    const TAG_SPEED_REF          = 0x000C;
    const TAG_SPEED              = 0x000D;
    const TAG_TRACK_REF          = 0x000E;
    const TAG_TRACK              = 0x000F;
    const TAG_IMG_DIRECTION_REF  = 0x0010;
    const TAG_IMG_DIRECTION      = 0x0011;
    const TAG_MAP_DATUM          = 0x0012;
    const TAG_DEST_LATITUDE_REF  = 0x0013;
    const TAG_DEST_LATITUDE      = 0x0014;
    const TAG_DEST_LONGITUDE_REF = 0x0015;
    const TAG_DEST_LONGITUDE     = 0x0016;
    const TAG_DEST_BEARING_REF   = 0x0017;
    const TAG_DEST_BEARING       = 0x0018;
    const TAG_DEST_DISTANCE_REF  = 0x0019;
    const TAG_DEST_DISTANCE      = 0x001A;
    const TAG_PROCESSING_METHOD  = 0x001B;
    const TAG_AREA_INFORMATION   = 0x001C;
    const TAG_DATE_STAMP         = 0x001D;
    const TAG_DIFFERENTIAL       = 0x001E;
    
    /**
     * The types of the EXIF GPS TIFF tags, with their corresponding PHP class
     */
    protected static $_types = array(
        0x0000 => 'Woops_Exif_Tiff_Gps_Tag_VersionId',
        0x0001 => 'Woops_Exif_Tiff_Gps_Tag_Latitude_Ref',
        0x0002 => 'Woops_Exif_Tiff_Gps_Tag_Latitude',
        0x0003 => 'Woops_Exif_Tiff_Gps_Tag_Longitude_Ref',
        0x0004 => 'Woops_Exif_Tiff_Gps_Tag_Longitude',
        0x0005 => 'Woops_Exif_Tiff_Gps_Tag_Altitude_Ref',
        0x0006 => 'Woops_Exif_Tiff_Gps_Tag_Altitude',
        0x0007 => 'Woops_Exif_Tiff_Gps_Tag_TimeStamp',
        0x0008 => 'Woops_Exif_Tiff_Gps_Tag_Satellites',
        0x0009 => 'Woops_Exif_Tiff_Gps_Tag_Status',
        0x000A => 'Woops_Exif_Tiff_Gps_Tag_MeasureMode',
        0x000B => 'Woops_Exif_Tiff_Gps_Tag_Dop',
        0x000C => 'Woops_Exif_Tiff_Gps_Tag_Speed_Ref',
        0x000D => 'Woops_Exif_Tiff_Gps_Tag_Speed',
        0x000E => 'Woops_Exif_Tiff_Gps_Tag_Track_Ref',
        0x000F => 'Woops_Exif_Tiff_Gps_Tag_Track',
        0x0010 => 'Woops_Exif_Tiff_Gps_Tag_ImgDirection_Ref',
        0x0011 => 'Woops_Exif_Tiff_Gps_Tag_ImgDirection',
        0x0012 => 'Woops_Exif_Tiff_Gps_Tag_MapDatum',
        0x0013 => 'Woops_Exif_Tiff_Gps_Tag_Dest_Latitude_Ref',
        0x0014 => 'Woops_Exif_Tiff_Gps_Tag_Dest_Latitude',
        0x0015 => 'Woops_Exif_Tiff_Gps_Tag_Dest_Longitude_Ref',
        0x0016 => 'Woops_Exif_Tiff_Gps_Tag_Dest_Longitude',
        0x0017 => 'Woops_Exif_Tiff_Gps_Tag_Dest_Bearing_Ref',
        0x0018 => 'Woops_Exif_Tiff_Gps_Tag_Dest_Bearing',
        0x0019 => 'Woops_Exif_Tiff_Gps_Tag_Dest_Distance_Ref',
        0x001A => 'Woops_Exif_Tiff_Gps_Tag_Dest_Distance',
        0x001B => 'Woops_Exif_Tiff_Gps_Tag_ProcessingMethod',
        0x001C => 'Woops_Exif_Tiff_Gps_Tag_AreaInformation',
        0x001D => 'Woops_Exif_Tiff_Gps_Tag_DateStamp',
        0x001E => 'Woops_Exif_Tiff_Gps_Tag_Differential'
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
     * @param   Woops_Tiff_File The TIFF file in which the IFD is contained
     * @return  void
     */
    public function __construct( Woops_Tiff_File $file )
    {
        $this->_file   = $file;
        $this->_header = $this->_file->getHeader();
    }
    
    /**
     * Gets the current tag object (SPL Iterator method)
     * 
     * @return  Woops_Tiff_Tag  The current SWF tag object
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
    public function processData( Woops_Tiff_Binary_Stream $stream )
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
                
            } catch( Woops_Exif_Tiff_Gps_Ifd_Exception $e ) {
                
                // Checks if the exception was made for an unknown TIFF tag
                if( $e->getCode() !== Woops_Exif_Tiff_Gps_Ifd_Exception::EXCEPTION_INVALID_TAG_TYPE ) {
                    
                    // No - Throws the exception again
                    throw $e;
                }
                
                // Creates an unknown tag object, and adds it
                $tag = new Woops_Exif_Tiff_Gps_UnknownTag( $this->_file, $type );
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
     * @param   int                                 The tag type (one of the TAG_XXX constant)
     * @return  Woops_Tiff_Tag                      The tag object
     * @throws  Woops_Exif_Tiff_Gps_Ifd_Exception   If the tag type is invalid
     */
    public function newTag( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks the value type
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid value type
            throw new Woops_Exif_Tiff_Gps_Ifd_Exception(
                'Invalid tag type (' .  $type . ')',
                Woops_Exif_Tiff_Gps_Ifd_Exception::EXCEPTION_INVALID_TAG_TYPE
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
     * @param   Woops_Tiff_Tag The tag object
     * @return  void
     */
    public function addTag( Woops_Tiff_Tag $tag )
    {
        $this->_tags[] = $tag;
    }
}
