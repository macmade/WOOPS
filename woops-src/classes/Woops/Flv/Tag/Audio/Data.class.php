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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * 
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Flv.Tag.Audio
 */
class Woops_Flv_Tag_Audio_Data extends Woops_Flv_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The sound formats
     */
    const FORMAT_LINEAR_PCM_PLATFORM_ENDIAN  = 0x00;
    const FORMAT_ADPCM                       = 0x01;
    const FORMAT_MP3                         = 0x02;
    const FORMAT_LINEAR_PCM_LITTLE_ENDIAN    = 0x03;
    const FORMAT_NELLYMOSER_16KHZ            = 0x04;
    const FORMAT_NELLYMOSER_8KHZ             = 0x05;
    const FORMAT_NELLYMOSER                  = 0x06;
    const FORMAT_G711_A_LAW_LOGARITHMIC_PCM  = 0x07;
    const FORMAT_G711_MU_LAW_LOGARITHMIC_PCM = 0x08;
    const FORMAT_AAC                         = 0x0A;
    const FORMAT_SPEEX                       = 0x0B;
    const FORMAT_MP3_8KHZ                    = 0x0E;
    const FORMAT_DEVICE_SPECIFIC             = 0x0F;
    
    /**
     * The sound rates
     */
    const RATE_5KHZ                          = 0x00;
    const RATE_11KHZ                         = 0x01;
    const RATE_22KHZ                         = 0x02;
    const RATE_44KHZ                         = 0x03;
    
    /**
     * The sound sizes
     */
    const SIZE_8BIT                          = 0x00;
    const SIZE_16BIT                         = 0x00;
    
    /**
     * The sound types
     */
    const TYPE_MONO                          = 0x00;
    const TYPE_STEREO                        = 0x01;
    
    /**
     * The FLV tag type
     */
    protected $_type        = 0x08;
    
    /**
     * The sound format
     */
    protected $_soundFormat = 0;
    
    /**
     * The sampling rate
     */
    protected $_soundRate   = 0;
    
    /**
     * The size of each sample
     */
    protected $_soundSize   = 0;
    
    /**
     * The sound type (mono or stereo)
     */
    protected $_soundType   = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Flv_Binary_Stream $stream )
    {
        // Calls the parent method
        parent::processData( $stream );
        
        // Gets the audio informations
        $infos              = $stream->unsignedChar();
        
        // Sets the sound properties
        $this->_soundFormat = $infos >> 4;
        $this->_soundRate   = ( $infos >> 6 ) & 0x03;
        $this->_soundSize   = ( $infos >> 7 ) & 0x01;
        $this->_soundType   = ( $infos )      & 0x01;
        
        $stream->seek( $this->_dataSize - 1, Woops_Flv_Binary_Stream::SEEK_CUR );
    }
}
