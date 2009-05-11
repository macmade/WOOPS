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

# $Id$

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Png;

/**
 * Abstract for the PNG chunks
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png
 */
abstract class Chunk extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * Gets the processed data
     * 
     * @return  stdClass    The processed data
     */
    abstract public function getProcessedData();
    
    /**
     * The binary stream
     */
    protected $_stream     = NULL;
    
    /**
     * The instance of the PNG file class in which the chunk is placed
     */
    protected $_pngFile    = NULL;
    
    /**
     * The chunk type
     */
    protected $_type       = '';
    
    /**
     * The chunk data
     */
    protected $_data       = '';
    
    /**
     * The chunk data length
     */
    protected $_dataLength = 0;
    
    /**
     * Class constructor
     * 
     * @param   Woops\Png\File  The instance of the PNG file class in which the chunk is placed
     * @return  NULL
     */
    public function __construct( File $pngFile )
    {
        // Stores a reference to the PNG file
        $this->_pngFile = $pngFile;
    }
    
    /**
     * 
     */
    public function __toString()
    {
        // Gets the chunk length
        $length = pack( 'N', $this->_dataLength );
        
        // Computes the CRC
        $crc    = pack( 'N', crc32( $this->_type . $this->_data ) );
        
        // Returns the full chunk
        return $length . $this->_type . $this->_data . $crc;
    }
    
    /**
     * 
     */
    public function getType()
    {
        return $this->_type;
    }
    
    /**
     * 
     */
    public function getDataLength()
    {
        return $this->_dataLength;
    }
    
    /**
     * 
     */
    public function setRawData( $data )
    {
        $this->_data       = $data;
        $this->_dataLength = strlen( $data );
        $this->_stream     = new Binary\Stream( $data );
    }
    
    /**
     * 
     */
    public function isCritical()
    {
        return ( boolean )!( ( ord( substr( $this->_type, 0, 1 ) ) >> 4 ) & 0x2 );
    }
    
    /**
     * 
     */
    public function isAncillary()
    {
        return ( boolean )( ( ord( substr( $this->_type, 0, 1 ) ) >> 4 ) & 0x2 );
    }
    
    /**
     * 
     */
    public function isPrivate()
    {
        return ( boolean )!( ( ord( substr( $this->_type, 1, 1 ) ) >> 4 ) & 0x2 );
    }
    
    /**
     * 
     */
    public function isSafeToCopy()
    {
        return ( boolean )( ( ord( substr( $this->_type, 3, 1 ) ) >> 4 ) & 0x2 );
    }
}
