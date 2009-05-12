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
namespace Woops\Midi\Chunk;

/**
 * MIDI header chunk
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Midi.Chunk
 */
class Header extends \Woops\Midi\Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The file formats
     */
    const FORMAT_SINGLE_MULTI_CHANNEL     = 0x00;
    const FORMAT_SIMULTANEOUS             = 0x01;
    const FORMAT_SEQUENCUALLY_INDEPENDANT = 0x02;
    
    /**
     * The chunk type
     */
    protected $_type     = 0x4D546864;
    
    /**
     * The overall organization of the file
     */
    protected $_format   = 0;
    
    /**
     * The number of tracks
     */
    protected $_tracks   = 0;
    
    /**
     * The meaning of the delta-times
     */
    protected $_division = 0;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( \Woops\Midi\Binary\Stream $stream )
    {
        // Calls the parent method
        parent::processData( $stream );
        
        // Gets the header properties
        $this->_format   = $stream->bigEndianUnsignedShort();
        $this->_tracks   = $stream->bigEndianUnsignedShort();
        $this->_division = $stream->bigEndianUnsignedShort();
    }
    
    /**
     * Gets the file format
     * 
     * @return  int     The file format
     */
    public function getFileFormat()
    {
        return $this->_format;
    }
    
    /**
     * Gets the number of tracks
     * 
     * @return  int     The number of tracks
     */
    public function getNumberOfTracks()
    {
        return $this->_tracks;
    }
    
    /**
     * Gets the meaning of the delta-times
     * 
     * @return  int     The meaning of the delta-times
     */
    public function getDivision()
    {
        return $this->_division;
    }
    
    /**
     * Sets the file format
     * 
     * @param   int     The file format (one of the FORMAT_XXX constant)
     * @return  void
     */
    public function setFileFormat( $value )
    {
        $this->_format = ( int )$value;
    }
    
    /**
     * Sets the number of tracks
     * 
     * @param   int     The number of tracks
     * @return  void
     */
    public function setNumberOfTracks( $value )
    {
        $this->_tracks = ( int )$value;
    }
    
    /**
     * Sets the meaning of the delta-times
     * 
     * @param   int     The meaning of the delta-times
     * @return  void
     */
    public function setDivision( $value )
    {
        $this->_division = ( int )$value;
    }
}
