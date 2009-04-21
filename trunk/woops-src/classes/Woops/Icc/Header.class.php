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
 * ICC profile header
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Icc
 */
class Woops_Icc_Header
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The ICC specification version
     */
    const ICC_VERSION   = 4;
    
    /**
     * The ICC profile signature
     */
    const ICC_SIGNATURE = 0x61637370;
    
    /**
     * The prodile/device classes
     */
    const CLASS_INPUT_DEVICE           = 'scnr';
    const CLASS_DISPLAY_DEVICE         = 'mntr';
    const CLASS_OUTPUT_DEVICE          = 'prtr';
    const CLASS_DEVICE_LINK            = 'link';
    const CLASS_COLOR_SPACE_CONVERSION = 'spac';
    const CLASS_ABSTRACT               = 'abst';
    const CLASS_NAMED_COLOUR           = 'nmcl';
    
    /**
     * The colour spaces
     */
    const COLOUR_XYZ                   = 'XYZ ';
    const COLOUR_LAB                   = 'Lab ';
    const COLOUR_LUV                   = 'Luv ';
    const COLOUR_YCBCR                 = 'YCbr';
    const COLOUR_YXY                   = 'Yxy ';
    const COLOUR_RGB                   = 'RGB ';
    const COLOUR_GRAY                  = 'GRAY';
    const COLOUR_HSV                   = 'HSV ';
    const COLOUR_HLS                   = 'HLS ';
    const COLOUR_CMYK                  = 'CMYK';
    const COLOUR_CMY                   = 'CMY ';
    const COLOUR_2                     = '2CLR';
    const COLOUR_3                     = '3CLR';
    const COLOUR_4                     = '4CLR';
    const COLOUR_5                     = '5CLR';
    const COLOUR_6                     = '6CLR';
    const COLOUR_7                     = '7CLR';
    const COLOUR_8                     = '8CLR';
    const COLOUR_9                     = '9CLR';
    const COLOUR_10                    = 'ACLR';
    const COLOUR_11                    = 'BCLR';
    const COLOUR_12                    = 'CCLR';
    const COLOUR_13                    = 'DCLR';
    const COLOUR_14                    = 'ECLR';
    const COLOUR_15                    = 'FCLR';
    
    /**
     * The profile size
     */
    protected $_profileSize      = 0;
    
    /**
     * The preferred CMM type
     */
    protected $_preferredCmmType = '';
    
    /**
     * The profile version number
     */
    protected $_version          = '';
    
    /**
     * The profile/device class
     */
    protected $_class            = '';
    
    /**
     * The coulour space
     */
    protected $_colourSpace      = '';
    
    /**
     * The profile connection space
     */
    protected $_connectionSpace  = '';
    
    /**
     * The date and time the profile was first created
     */
    protected $_dateTime         = 0;
    
    /**
     * The primary platform signature
     */
    protected $_primaryPlatform  = '';
    
    /**
     * The profile flags
     */
    protected $_flags            = 0;
    
    /**
     * The device manufacturer of the device for which the profile is created
     */
    protected $_manufacturer     = '';
    
    /**
     * The device model of the device for which this profile is created
     */
    protected $_model            = '';
    
    /**
     * The device attributes unique to the particular device setup such as media type
     */
    protected $_attributes       = 0;
    
    /**
     * The rendering intent
     */
    protected $_renderingIntent  = 0;
    
    /**
     * The XYZ values of the illuminant of the Profile Connection Space
     */
    protected $_illuminant       = array();
    
    /**
     * The profile creator signature
     */
    protected $_creator          = '';
    
    /**
     * The profile ID
     */
    protected $_id               = '';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @param   Woops_Icc_Binary_Stream The IC binary stream
     * @return  void
     */
    public function processData( Woops_Icc_Binary_Stream $stream )
    {
        $this->_profileSize      = $stream->bigEndianUnsignedLong();
        $this->_preferredCmmType = $stream->read( 4 );
        
        $version                 = $stream->bigEndianUnsignedLong();
        $major                   = $version >> 24;
        $minor                   = ( $version >> 16 ) & 0x00F0;
        $bugfix                  = ( $version >> 16 ) & 0x00F;
        $this->_version          = self::ICC_VERSION . '.' . $major . '.' . $minor . '.' . $bugfix;
        
        $this->_class            = $stream->read( 4 );
        
        $this->_colourSpace      = $stream->read( 4 );
        
        $this->_connectionSpace  = $stream->read( 4 );
        
        $this->_dateTime         = $stream->dateTime();
        
        $signature               = $stream->bigEndianUnsignedLong();
        
        if( $signature !== self::ICC_SIGNATURE ) {
            
            throw new Woops_Icc_Header_Exception(
                'Invalid ICC signature (' . $signature . ')',
                Woops_Icc_Header_Exception::EXCEPTION_BAD_SIGNATURE
            );
        }
        
        $this->_primaryPlatform = $stream->read( 4 );
        
        $this->_flags           = $stream->bigEndianUnsignedLong();
        
        $this->_manufacturer    = $stream->read( 4 );
        
        $this->_model           = $stream->bigEndianUnsignedLong();
        
        $this->_attributes      = ( $stream->bigEndianUnsignedLong() << 32 )
                                |   $stream->bigEndianUnsignedLong();
        
        $this->_renderingIntent = $stream->bigEndianUnsignedLong();
        
        $this->_illuminant      = $stream->xyzNumber( 12 );
        
        $this->_creator         = $stream->read( 4 );
        
        $this->_id              = $stream->read( 16 );
    }
}
