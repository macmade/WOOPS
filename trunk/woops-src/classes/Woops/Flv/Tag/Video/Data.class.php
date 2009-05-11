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
namespace Woops\Flv\Tag\Video;

/**
 * 
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Flv.Tag.Video
 */
class Data extends \Woops\Flv\Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The frame types
     */
    const TYPE_KEYFRAME              = 0x01;
    const TYPE_INTERFRAME            = 0x02;
    const TYPE_DISPOSABLE_INTERFRAME = 0x03;
    const TYPE_GENERATED_KEYFRAME    = 0x04;
    const TYPE_VIDEO_INFO_FRAME      = 0x05;
    const TYPE_VIDEO_COMMAND_FRAME   = 0x05;
    
    
    /**
     * The codec IDs
     */
    const CODEC_JPEG                 = 0x01;
    const CODEC_SORENSON_H263        = 0x02;
    const CODEC_SCREEN_VIDEO         = 0x03;
    const CODEC_ON2_VP6              = 0x04;
    const CODEC_ON2_VP6_ALPHA        = 0x05;
    const CODEC_SCREEN_VIDEO_2       = 0x06;
    const CODEC_AVC                  = 0x07;
    
    /**
     * The FLV tag type
     */
    protected $_type      = 0x09;
    
    /**
     * The frame type
     */
    protected $_frameType = 0;
    
    /**
     * The codec ID
     */
    protected $_codecId   = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( \Woops\Flv\Binary\Stream $stream )
    {
        // Calls the parent method
        parent::processData( $stream );
        
        // Gets the video infos
        $infos            = $stream->unsignedChar();
        
        // Sets the video infos
        $this->_frameType = $infos >> 4;
        $this->_codecId   = $infos & 0x0F;
        
        $stream->seek( $this->_dataSize - 1, \Woops\Flv\Binary\Stream::SEEK_CUR );
    }
}
