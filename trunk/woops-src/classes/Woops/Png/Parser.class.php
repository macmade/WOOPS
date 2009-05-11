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
 * PNG file parser
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png
 */
class Parser extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * An instance of the Woops\Png\File class
     */
    protected $pngFile               = NULL;
    
    /**
     * The binary stream
     */
    protected $_stream               = NULL;
    
    /**
     * The file path
     */
    protected $_filePath             = '';
    
    /**
     * Allows invalid chunk structure (not as in the PNG specification)
     */
    protected $_allowInvalidStucture = false;
    
    /**
     * An array that will be filled with the PNG informations
     */
    protected $_pngInfos             = array();
    
    /**
     * The parsing warnings/errors
     */
    protected $_warnings             = array();
    
    /**
     * Class constructor
     * 
     * @param   string  The location of the PNG file
     * @return  NULL
     * @see     _parseFile
     */
    public function __construct( $file, $allowInvalidStucture = false )
    {
        // Create a new instance of Png_File
        $this->_pngFile              = new File();
        
        // Sets the options for the current instance
        $this->_allowInvalidStucture = $allowInvalidStucture;
        
        // Stores the file path
        $this->_filePath             = $file;
        
        // Creates the binary stream
        $this->_stream               = new \Woops\Binary\File\Stream( $file );
        
        // Parses the file
        $this->_parseFile();
        
        // Deletes the stream object
        unset( $this->_stream );
    }
    
    /**
     * 
     */
    protected function _parseFile()
    {
        // The PNG file signature (\211   P   N   G  \r  \n \032 \n)
        $signature = chr( 137 ) . chr( 80 ) . chr( 78 ) . chr( 71 )
                   . chr( 13 )  . chr( 10 ) . chr( 26 ) . chr( 10 );
        
        // Checks the GIF signature
        if( $this->_stream->read( 8 ) !== $signature ) {
            
            // Wrong file type
            throw new Parser\Exception(
                'File ' . $this->_filePath . ' is not a PNG file.',
                Parser\Exception::EXCEPTION_BAD_SIGNATURE
            );
        }
        
        // Process the file till the end
        while( !$this->_stream->endOfStream() ) {
            
            // Gets the chunk size
            $chunkSize       = $this->_stream->bigEndianUnsignedLong();
            
            // Gets the chunk type
            $chunkType       = $this->_stream->read( 4 );
            
            // Checks if the chunk is valid or not
            $invalid = $this->_pngFile->isInvalidChunk( $chunkType );
            
            // Checks the invalid state
            if( $invalid ) {
                
                // Adds a warning
                $this->_warnings[] = array(
                    'chunkType'   => $chunkType,
                    'chunkLength' => $chunkSize,
                    'fileOffset'  => $this->_stream->getOffset() - 8,
                    'message'     => $invalid
                );
                
                // Checks if we allows invalid chunks
                if( $this->_allowInvalidStucture ) {
                    
                    // Tells the PNG file class to not complains about bad chunks
                    $this->_pngFile->allowAnyChunkType( true );
                    
                    // Adds the chunk
                    $chunk = $this->_pngFile->addChunk( $chunkType );
                    
                } else {
                    
                    // No invalid chunk is allowed - The current chunk will be skipped
                    $chunk = false;
                }
                
            } else {
                
                // Chunk is valid - Adds it
                $chunk = $this->_pngFile->addChunk( $chunkType );
            }
            
            // Storage for the chunk data
            $chunkData       = '';
            
            // Checks for data
            if( $chunkSize > 0 ) {
                
                // Gets the chunk data
                $chunkData = $this->_stream->read( $chunkSize );
                
                // Checks if the chunk object exists
                if( $chunk ) {
                    
                    // Stores the raw data
                    $chunk->setRawData( $chunkData );
                }
            }
            
            // Gets the cyclic redundancy check
            $crc     = $this->_stream->bigEndianUnsignedLong();
            
            // Checks the CRC
            if( $crc !== crc32( $chunkType . $chunkData ) ) {
                
                // Invalid CRC
                throw new Parser\Exception(
                    'Invalid cyclic redundancy check for chunk ' . $chunkType,
                    Parser\Exception::EXCEPTION_BAD_CRC
                );
            }
            
            // Checks if the current chunk is the PNG terminator chunk
            if( $chunkType === 'IEND' ) {
                
                // No more chunks
                break;
            }
        }
    }
    
    /**
     * Gets the Png_File instance
     * 
     * @return  object  The instance of Png_File
     */
    public function getPngFile()
    {
        return $this->_pngFile;
    }
    
    /**
     * Gets the parsing errors/warnings
     * 
     * @return  array   An array with the parsing errors/warnings
     */
    public function getWarnings()
    {
        return $this->_warnings;
    }
}
