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
 * PNG file
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png
 */
class File extends \Woops\Core\Object implements \Iterator, \ArrayAccess
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Allows invalid chunk structure (not as in the PNG specification)
     */
    protected $_allowInvalidStucture  = false;
    
    /**
     * The current position for the SPL Iterator methods
     */
    protected $_iteratorIndex         = 0;
    
    /**
     * The number of added chunks
     */
    protected $_chunksCount           = 0;
    
    /**
     * THe type of the last chunk added
     */
    protected $_lastChunk             = '';
    
    /**
     * The PNG file signature
     */
    protected $_signature             = '';
    
    /**
     * An array with the PNG chunks
     */
    protected $_chunks                = array();
    
    /**
     * The name and count of the added chunks
     */
    protected $_chunksByName          = array();
    
    /**
     * The valid PNG chunks, as in the PNG specification
     */
    protected $_validChunks           = array(
        
        // Critical chunks
        'IHDR' => true,
        'PLTE' => true,
        'IDAT' => true,
        'IEND' => true,
        
        // Ancillary chunks
        'cHRM' => true,
        'gAMA' => true,
        'iCCP' => true,
        'sBIT' => true,
        'sRGB' => true,
        'bKGD' => true,
        'hIST' => true,
        'tRNS' => true,
        'pHYs' => true,
        'sPLT' => true,
        'tIME' => true,
        'iTXt' => true,
        'tEXt' => true,
        'zTXt' => true
    );
    
    /**
     * The chunks that can be added multiple times in the file
     */
    protected $_allowedMultipleChunks = array(
        'IDAT' => true,
        'sPLT' => true,
        'iTXt' => true,
        'tEXt' => true,
        'zTXt' => true
    );
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    public function __construct()
    {
        // Sets the PNG file signature (\211   P   N   G  \r  \n \032 \n)
        $this->_signature = chr( 137 ) . chr( 80 ) . chr( 78 ) . chr( 71 )
                          . chr( 13 )  . chr( 10 ) . chr( 26 ) . chr( 10 );
    }
    
    /**
     * Writes the PNG file
     * 
     * @return  string  The content of the PNG file
     */
    public function __toString()
    {
        // Starts with the PNG signature
        $data = $this->_signature;
        
        // Process each chunk
        foreach( $this->_chunks as $chunk ) {
            
            // Adds the current chunk
            $data .= ( string )$chunk;
        }
        
        // Returns the PNG data
        return $data;
    }
    
    /**
     * Gets a chunk by its name
     * 
     * @param   string  The name of the chunk
     * @return  mixed   The chunk object, or NULL if there is no such chunk
     */
    public function __get( $name )
    {
        // Checks if the chunk exists
        if( !isset( $this->_chunksByName[ $name ] ) ) {
            
            // No such chunk
            return NULL;
        }
        
        // Returns the chunk
        return $this->_chunksByName[ $name ][ 0 ];
    }
    
    /**
     * Checks if a chunk exists
     * 
     * @param   string  The name of the chunk
     * @retrun  boolean
     */
    public function __isset( $name )
    {
        return isset( $this->_chunksByName[ $name ] );
    }
    
    /**
     * Checks if an offset exists in the chunks array (SPL ArrayAccess method)
     * 
     * @param   int     The offset to check
     * @return  boolean
     */
    public function offsetExists( $offset )
    {
        return isset( $this->_chunks[ $offset ] );
    }
    
    /**
     * Gets a chunk at the specified offset (SPL ArrayAccess method)
     * 
     * @param   int     The offset in the chunks array
     * @return  object  The chunk object
     */
    public function offsetGet( $offset )
    {
        return $this->_chunks[ $offset ];
    }
    
    /**
     * Sets a chunk at the specified offset (SPL ArrayAccess method)
     * 
     * This method will always return false. Please use the addChunk()
     * method instead.
     * 
     * @param   int         The offset in the chunks array
     * @param   mixed       The PNG chunk to set
     * @return  boolean Always false, as a chunk cannot be set by this way
     */
    public function offsetSet( $offset, $value )
    {
        return false;
    }
    
    /**
     * Unsets a chunk at the specified offset (SPL ArrayAccess method)
     * 
     * This method will always return false, as there is no way to remove a
     * chunk once it has been added
     * 
     * @param   int     The offset in the chunks array
     * @return  boolean Always false, as a chunk cannot be set by this way
     */
    public function offsetUnset( $offset )
    {
        return false;
    }
    
    /**
     * Resets the iterator index (SPL Iterator method)
     * 
     * @return  NULL
     */
    public function rewind()
    {
        $this->_iteratorIndex = 0;
    }
    
    /**
     * Gets the current chunk (SPL Iterator method)
     * 
     * @return  Png_Chunk   The current chunk
     */
    public function current()
    {
        return $this->_chunks[ $this->_iteratorIndex ];
    }
    
    /**
     * Gets the current chunk type (SPL Iterator method)
     * 
     * @return  string  The chunk type
     */
    public function key()
    {
        return $this->_chunks[ $this->_iteratorIndex ]->getType();
    }
    
    /**
     * Increments the iterator index (SPL Iterator method)
     * 
     * @return  NULL
     */
    public function next()
    {
        $this->_iteratorIndex++;
    }
    
    /**
     * Checks if another chunk can be got (SPL Iterator method)
     * 
     * @return  boolean
     */
    public function valid()
    {
        return $this->_iteratorIndex < $this->_chunksCount;
    }
    
    /**
     * Gets the processed data of all the chunks contained in the current file
     * 
     * @return  array   The processed data for all the chunks
     */
    public function getProcessedData()
    {
        // Storage
        $data = array();
        
        // Process each chunk
        foreach( $this->_chunks as $chunk ) {
            
            // Creates a storage object
            $chunkData               = new \stdClass();
            
            // Sets the common properties
            $chunkData->type         = $chunk->getType();
            $chunkData->size         = $chunk->getDataLength();
            $chunkData->isCritical   = $chunk->isCritical();
            $chunkData->isAncillary  = $chunk->isAncillary();
            $chunkData->isPrivate    = $chunk->isPrivate();
            $chunkData->isSafeToCopy = $chunk->isSafeToCopy();
            
            // Gets the chunk data
            $chunkData->data         = $chunk->getProcessedData();
            
            // Adds the chunk processed data
            $data[]                   = $chunkData;
        }
        
        // Returns the processed data
        return $data;
    }
    
    /**
     * Adds a chunk in the current file
     * 
     * @param   string                      The type of the chunk to add
     * @return  object                      The chunk object corresponding to the given type
     * @throws  Woops\Png\File\Exception    If the chunk type is invalid
     */
    public function addChunk( $chunkType )
    {
        // Checks if the chunk is invalid
        $invalid = $this->isInvalidChunk( $chunkType );
        
        // Checks the invalid state, and if invalid chunks are allowed
        if( $invalid && !$this->_allowInvalidStucture ) {
            
            // Invalid chunk
            throw new File\Exception(
                $invalid,
                File\Exception::EXCEPTION_INVALID_CHUNK
            );
        }
        
        // Name of the chunk class
        $className = 'Chunk\\' . ucfirst( strtolower( $chunkType ) );
        
        // Checks if the class exists
        if( class_exists( $className ) ) {
            
            // Creates the chunk
            $chunk = new $className( $this );
            
        } else {
            
            // Chunk is unknown - Creates an instance of the Png_UnknownChunk class
            $chunk = new UnknownChunk( $this, $chunkType );
        }
        
        // Adds the chunk to the list of the chunks
        $this->_chunks[]  = $chunk;
        
        // Stores the chunk type
        $this->_lastChunk = $chunkType;
        
        // Checks if the chunk type has already been registered
        if( isset( $this->_chunksByName[ $chunkType ] ) ) {
            
            // Adds the chunk to the chunk list
            $this->_chunksByName[ $chunkType ][] = $chunk;
            
        } else {
            
            // Creates the storage place for the chunk type
            $this->_chunksByName[ $chunkType ]   = array();
            
            // Adds the chunk to the chunk list
            $this->_chunksByName[ $chunkType ][] = $chunk;
        }
        
        // Increments the number of chunks
        $this->_chunksCount++;
        
        // Returns the chunk
        return $chunk;
    }
    
    /**
     * Checks if a chunk type is invalid
     * 
     * @param   string  The chunk type
     * @return  mixed   False if the chunk is valid, otherwise an error message
     */
    public function isInvalidChunk( $type )
    {
        // Checks if the chunk is valid
        if( !isset( $this->_validChunks[ $type ] ) ) {
            
            // Chunk is not in the PNG specification
            return 'Chunk ' . $type . ' is not part of the PNG specification';
        }
        
        // Checks if the chunk already exists and if it can be added multiple times
        if( isset( $this->_chunksByName[ $type ] ) && !isset( $this->_allowedMultipleChunks[ $type ] ) ) {
            
            // Chunk cannot be added twice
            return 'Chunk ' . $type . ' cannot be added more than once';
        }
        
        // The IHDR chunk must be present before any other chunk
        if( $type !== 'IHDR' && !isset( $this->_chunksByName[ 'IHDR' ] ) ) {
            
            // No IHDR chunk
            return 'Cannot add chunk ' . $type . ' as there is no IHDR chunk';
        }
        
        // IDAT chunks must be consecutives
        if( $type === 'IDAT' && isset( $this->_chunksByName[ 'IDAT' ] ) && $this->_lastChunk != 'IDAT' ) {
            
            // IDAT chunks are not consecutives
            return 'IDAT chunks must be consecutives';
        }
        
        // No chunk can be placed if the IEND chunk exists
        if( isset( $this->_chunksByName[ 'IEND' ] ) ) {
            
            // IEND already added
            return 'Cannot add chunk ' . $type . ' as the IEND chunk is already present';
        }
        
        return false;
    }
}
