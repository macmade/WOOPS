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
 * TIFF file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff.Image.File
 */
class Woops_Tiff_Image_File_Directory implements Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The TIFF tag types in TIFF revision 6
     */
    const TAG_NEWSUBFILETYPE                 = 0x00FE;
    const TAG_SUBFILETYPE                    = 0x00FF;
    const TAG_IMAGE_WIDTH                    = 0x0100;
    const TAG_IMAGE_LENGTH                   = 0x0101;
    const TAG_BITSPERSAMPLE                  = 0x0102;
    const TAG_COMPRESSION                    = 0x0103;
    const TAG_PHOTOMETRICINTERPRETATION      = 0x0106;
    const TAG_THRESHHOLDING                  = 0x0107;
    const TAG_CELL_WIDTH                     = 0x0108;
    const TAG_CELL_LENGTH                    = 0x0109;
    const TAG_FILLORDER                      = 0x010A;
    const TAG_DOCUMENTNAME                   = 0x010D;
    const TAG_IMAGE_DESCRIPTION              = 0x010E;
    const TAG_MAKE                           = 0x010F;
    const TAG_MODEL                          = 0x0110;
    const TAG_STRIP_OFFSETS                  = 0x0111;
    const TAG_ORIENTATION                    = 0x0112;
    const TAG_SAMPLESPERPIXEL                = 0x0115;
    const TAG_ROWSPERSTRIP                   = 0x0116;
    const TAG_STRIP_BYTECOUNTS               = 0x0117;
    const TAG_MINSAMPLEVALUE                 = 0x0118;
    const TAG_MAXSAMPLEVALUE                 = 0x0119;
    const TAG_X_RESOLUTION                   = 0x011A;
    const TAG_Y_RESOLUTION                   = 0x011B;
    const TAG_PLANARCONFIGURATION            = 0x011C;
    const TAG_PAGE_NAME                      = 0x011D;
    const TAG_X_POSITION                     = 0x011E;
    const TAG_Y_POSITION                     = 0x011F;
    const TAG_FREE_OFFSETS                   = 0x0120;
    const TAG_FREE_BYTECOUNTS                = 0x0121;
    const TAG_GRAY_RESPONSE_UNIT             = 0x0122;
    const TAG_GRAY_RESPONSE_CURVE            = 0x0123;
    const TAG_T4OPTIONS                      = 0x0124;
    const TAG_T6OPTIONS                      = 0x0125;
    const TAG_RESOLUTIONUNIT                 = 0x0128;
    const TAG_PAGE_NUMBER                    = 0x0129;
    const TAG_TRANSFERFUNCTION               = 0x012D;
    const TAG_SOFTWARE                       = 0x0131;
    const TAG_DATETIME                       = 0x0132;
    const TAG_ARTIST                         = 0x013B;
    const TAG_HOSTCOMPUTER                   = 0x013C;
    const TAG_PREDICTOR                      = 0x013D;
    const TAG_WHITEPOINT                     = 0x013E;
    const TAG_PRIMARYCHROMATICITIES          = 0x013F;
    const TAG_COLORMAP                       = 0x0140;
    const TAG_HALFTONEHINTS                  = 0x0141;
    const TAG_TILE_WIDTH                     = 0x0142;
    const TAG_TILE_LENGTH                    = 0x0143;
    const TAG_TILE_OFFSETS                   = 0x0144;
    const TAG_TILE_BYTECOUNTS                = 0x0145;
    const TAG_INK_SET                        = 0x014C;
    const TAG_INK_NAMES                      = 0x014D;
    const TAG_NUMBEROFINKS                   = 0x014E;
    const TAG_DOTRANGE                       = 0x0150;
    const TAG_TARGETPRINTER                  = 0x0151;
    const TAG_EXTRASAMPLES                   = 0x0152;
    const TAG_SAMPLEFORMAT                   = 0x0153;
    const TAG_SMINSAMPLEVALUE                = 0x0154;
    const TAG_SMAXSAMPLEVALUE                = 0x0155;
    const TAG_TRANSFERRANGE                  = 0x0156;
    const TAG_JPEG_PROC                      = 0x0200;
    const TAG_JPEG_INTERCHANGE_FORMAT        = 0x0201;
    const TAG_JPEG_INTERCHANGE_FORMAT_LENGTH = 0x0202;
    const TAG_JPEG_RESTARTINTERVAL           = 0x0203;
    const TAG_JPEG_LOSSLESSPREDICTORS        = 0x0205;
    const TAG_JPEG_POINTTRANSFORMS           = 0x0206;
    const TAG_JPEG_QTABLES                   = 0x0207;
    const TAG_JPEG_DCTABLES                  = 0x0208;
    const TAG_JPEG_ACTABLES                  = 0x0209;
    const TAG_YCBCR_COEFFICIENTS             = 0x0211;
    const TAG_YCBCR_SUBSAMPLING              = 0x0212;
    const TAG_YCBCR_POSITIONING              = 0x0213;
    const TAG_REFERENCEBLACKWHITE            = 0x0214;
    const TAG_COPYRIGHT                      = 0x8298;
    
    /**
     * The third-party TIFF tag types
     */
    const TAG_XMP                            = 0x02BC;
    const TAG_IPTC                           = 0x83BB;
    const TAG_PHOTOSHOP                      = 0x8649;
    const TAG_ICC_PROFILE                    = 0x8773;
    
    /**
     * The types of the TIFF tags, with their corresponding PHP class
     */
    protected static $_types = array(
        
        // Tags in TIFF revision 6
        0x00FE => 'Woops_Tiff_Tag_NewSubfileType',
        0x00FF => 'Woops_Tiff_Tag_SubfileType',
        0x0100 => 'Woops_Tiff_Tag_Image_Width',
        0x0101 => 'Woops_Tiff_Tag_Image_Length',
        0x0102 => 'Woops_Tiff_Tag_BitsPerSample',
        0x0103 => 'Woops_Tiff_Tag_Compression',
        0x0106 => 'Woops_Tiff_Tag_PhotometricInterpretation',
        0x0107 => 'Woops_Tiff_Tag_Threshholding',
        0x0108 => 'Woops_Tiff_Tag_Cell_Width',
        0x0109 => 'Woops_Tiff_Tag_Cell_Length',
        0x010A => 'Woops_Tiff_Tag_FillOrder',
        0x010D => 'Woops_Tiff_Tag_DocumentName',
        0x010E => 'Woops_Tiff_Tag_Image_Description',
        0x010F => 'Woops_Tiff_Tag_Make',
        0x0110 => 'Woops_Tiff_Tag_Model',
        0x0111 => 'Woops_Tiff_Tag_Strip_Offsets',
        0x0112 => 'Woops_Tiff_Tag_Orientation',
        0x0115 => 'Woops_Tiff_Tag_SamplesPerPixel',
        0x0116 => 'Woops_Tiff_Tag_RowsPerStrip',
        0x0117 => 'Woops_Tiff_Tag_Strip_ByteCounts',
        0x0118 => 'Woops_Tiff_Tag_MinSampleValue',
        0x0119 => 'Woops_Tiff_Tag_MaxSampleValue',
        0x011A => 'Woops_Tiff_Tag_X_Resolution',
        0x011B => 'Woops_Tiff_Tag_Y_Resolution',
        0x011C => 'Woops_Tiff_Tag_PlanarConfiguration',
        0x011D => 'Woops_Tiff_Tag_Page_Name',
        0x011E => 'Woops_Tiff_Tag_X_Position',
        0x011F => 'Woops_Tiff_Tag_Y_Position',
        0x0120 => 'Woops_Tiff_Tag_Free_Offsets',
        0x0121 => 'Woops_Tiff_Tag_Free_ByteCounts',
        0x0122 => 'Woops_Tiff_Tag_Gray_Response_Unit',
        0x0123 => 'Woops_Tiff_Tag_Gray_Response_Curve',
        0x0124 => 'Woops_Tiff_Tag_T4Options',
        0x0125 => 'Woops_Tiff_Tag_T6Options',
        0x0128 => 'Woops_Tiff_Tag_ResolutionUnit',
        0x0129 => 'Woops_Tiff_Tag_Page_Number',
        0x012D => 'Woops_Tiff_Tag_TransferFunction',
        0x0131 => 'Woops_Tiff_Tag_Software',
        0x0132 => 'Woops_Tiff_Tag_DateTime',
        0x013B => 'Woops_Tiff_Tag_Artist',
        0x013C => 'Woops_Tiff_Tag_HostComputer',
        0x013D => 'Woops_Tiff_Tag_Predictor',
        0x013E => 'Woops_Tiff_Tag_WhitePoint',
        0x013F => 'Woops_Tiff_Tag_PrimaryChromaticities',
        0x0140 => 'Woops_Tiff_Tag_ColorMap',
        0x0141 => 'Woops_Tiff_Tag_HalftoneHints',
        0x0142 => 'Woops_Tiff_Tag_Tile_Width',
        0x0143 => 'Woops_Tiff_Tag_Tile_Length',
        0x0144 => 'Woops_Tiff_Tag_Tile_Offsets',
        0x0145 => 'Woops_Tiff_Tag_Tile_ByteCounts',
        0x014C => 'Woops_Tiff_Tag_Ink_Set',
        0x014D => 'Woops_Tiff_Tag_Ink_Names',
        0x014E => 'Woops_Tiff_Tag_NumberOfInks',
        0x0150 => 'Woops_Tiff_Tag_DotRange',
        0x0151 => 'Woops_Tiff_Tag_TargetPrinter',
        0x0152 => 'Woops_Tiff_Tag_ExtraSamples',
        0x0153 => 'Woops_Tiff_Tag_SampleFormat',
        0x0154 => 'Woops_Tiff_Tag_SMinSampleValue',
        0x0155 => 'Woops_Tiff_Tag_SMaxSampleValue',
        0x0156 => 'Woops_Tiff_Tag_TransferRange',
        0x0200 => 'Woops_Tiff_Tag_Jpeg_Proc',
        0x0201 => 'Woops_Tiff_Tag_Jpeg_Interchange_Format',
        0x0202 => 'Woops_Tiff_Tag_Jpeg_Interchange_Format_Length',
        0x0203 => 'Woops_Tiff_Tag_Jpeg_RestartInterval',
        0x0205 => 'Woops_Tiff_Tag_Jpeg_LosslessPredictors',
        0x0206 => 'Woops_Tiff_Tag_Jpeg_PointTransforms',
        0x0207 => 'Woops_Tiff_Tag_Jpeg_QTables',
        0x0208 => 'Woops_Tiff_Tag_Jpeg_DcTables',
        0x0209 => 'Woops_Tiff_Tag_Jpeg_AcTables',
        0x0211 => 'Woops_Tiff_Tag_YCbCr_Coefficients',
        0x0212 => 'Woops_Tiff_Tag_YCbCr_SubSampling',
        0x0213 => 'Woops_Tiff_Tag_YCbCr_Positioning',
        0x0214 => 'Woops_Tiff_Tag_ReferenceBlackWhite',
        0x8298 => 'Woops_Tiff_Tag_Copyright',
        
        // Third-party tags
        0x02BC => 'Woops_Tiff_Tag_Xmp',
        0x83BB => 'Woops_Tiff_Tag_Iptc',
        0x8649 => 'Woops_Tiff_Tag_Photoshop',
        0x8773 => 'Woops_Tiff_Tag_IccProfile'
        
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
                
            } catch( Woops_Tiff_Image_File_Directory_Exception $e ) {
                
                // Checks if the exception was made for an unknown TIFF tag
                if( $e->getCode() !== Woops_Tiff_Image_File_Directory_Exception::EXCEPTION_INVALID_TAG_TYPE ) {
                    
                    // No - Throws the exception again
                    throw $e;
                }
                
                // Creates an unknown tag object, and adds it
                $tag = new Woops_Tiff_UnknownTag( $this->_file, $type );
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
     * @param   int                                         The tag type (one of the TAG_XXX constant)
     * @return  Woops_Tiff_Tag                              The tag object
     * @throws  Woops_Tiff_Image_File_Directory_Exception   If the tag type is invalid
     */
    public function newTag( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks the value type
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid value type
            throw new Woops_Tiff_Image_File_Directory_Exception(
                'Invalid tag type (' .  $type . ')',
                Woops_Tiff_Image_File_Directory_Exception::EXCEPTION_INVALID_TAG_TYPE
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
     * @param   Woops_Tiff_Tag  The tag object
     * @return  void
     */
    public function addTag( Woops_Tiff_Tag $tag )
    {
        $this->_tags[] = $tag;
    }
}
