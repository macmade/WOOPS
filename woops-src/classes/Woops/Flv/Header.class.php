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
 * FLV file header
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Flv
 */
class Woops_Flv_Header extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The FLV signature
     */
    const SIGNATURE = 0x464C56;
    
    /**
     * The FLV version
     */
    protected $_version    = 0;
    
    /**
     * Whether audio tags are present or not
     */
    protected $_hasAudio   = false;
    
    /**
     * Whether video tags are present or not
     */
    protected $_hasVideo   = false;
    
    /**
     * The offset in bytes from start of file to start of body
     */
    protected $_dataOffset = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Flv_Binary_Stream $stream )
    {
        // Gets the FLV signature
        $signature = ( $stream->unsignedChar() << 16 )
                   | ( $stream->unsignedChar() << 8 )
                   |   $stream->unsignedChar();
        
        // Checks the FLV signature
        if( $signature !== self::SIGNATURE ) {
            
            // Error - Invalid FLV signature
            throw new Woops_Flv_Header_Exception(
                'Invalid FLV signature (' . $signature . ')',
                Woops_Flv_Header_Exception::EXCEPTION_BAD_SIGNATURE
            );
        }
        
        // Gets the FLV version
        $this->_version    = $stream->unsignedChar();
        
        // Gets the flags
        $flags             = $stream->unsignedChar();
        
        // Sets the audio and video flags
        $this->_hasAudio   = ( boolean )( $flags & 0x04 );
        $this->_hasVideo   = ( boolean )( $flags & 0x01 );
        
        // Gets the data offset
        $this->_dataOffset = $stream->bigEndianUnsignedLong();
    }
    
    /**
     * Gets the FLV version
     * 
     * @return  int     The FLV version
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * Sets the FLV version
     * 
     * @param   int     The FLV version
     * @return  void
     */
    public function setVersion( $version )
    {
        $this->_version = ( int )$version;
    }
    
    /**
     * Checks whether audio tags are present or not
     * 
     * @return  boolean True if audio tags are present, otherwise false
     */
    public function hasAudio()
    {
        return $this->_hasAudio;
    }
    
    /**
     * Checks whether video tags are present or not
     * 
     * @return  boolean True if video tags are present, otherwise false
     */
    public function hasVideo()
    {
        return $this->_hasVideo;
    }
    
    /**
     * Decides whether audio tags are present or not
     * 
     * @return  boolean True if audio tags are present, otherwise false
     * @return  void
     */
    public function setAudio( $value )
    {
        $this->_hasAudio = ( boolean )$value;
    }
    
    /**
     * Decides whether video tags are present or not
     * 
     * @param   boolean True if video tags are present, otherwise false
     * @return  void
     */
    public function setVideo( $value )
    {
        $this->_hasVideo = ( boolean )$value;
    }
    
    /**
     * Gets the data offset
     * 
     * @return  int     The data offset in bytes
     */
    public function getDataOffset()
    {
        return $this->_dataOffset;
    }
    
    /**
     * Sets the data offset
     * 
     * @param   int     The data offset in bytes
     * @return  void
     */
    public function setDataOffset( $value )
    {
        $this->_dataOffset = ( int )$value;
    }
}
