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
namespace Woops\Mpeg4;

/**
 * MPEG-4 file parser
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4
 */
class Parser extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * An instance of the Woops\Mpeg4\File class
     */
    protected $_mpeg4File            = NULL;
    
    /**
     * The binary stream
     */
    protected $_stream               = NULL;
    
    /**
     * The file path
     */
    protected $_filePath             = '';
    
    /**
     * The parsing warnings/errors
     */
    protected $_warnings             = array();
    
    /**
     * Allows invalid atom hierarchy (not as in ISO-IEC 14496-12)
     */
    protected $_allowInvalidStucture = false;
    
    /**
     * Class constructor
     * 
     * @param   string      The location of the MPEG-4 file
     * @param   boolean     Allows invalid atom hierarchy (not as in ISO-IEC 14496-12)
     * @return  NULL
     */
    public function __construct( $file, $allowInvalidStucture = false, $allowUnknownAtoms = false )
    {
        // Sets the options for the current instance
        $this->_allowInvalidStucture = $allowInvalidStucture;
        
        // Create a new instance of Mpeg4_File
        $this->_mpeg4File            = new File();
        
        // Stores the file path
        $this->_filePath             = $file;
        
        // Creates the binary stream
        $this->_stream               = new \Woops\Binary\File\Stream( $file );
        
        // Parses the file
        $this->_parseFile();
        
        // Deletes the stream object
        unset( $this->_stream );
    }
    
    protected function _parseFile( $bytes = 0, $level = 0, $parent = NULL )
    {
        // Number of bytes read in the current parsing level
        $bytesRead = 0;
        
        // Reads 8 bytes of the MPEG-4 files till the end of the file
        // 8 bytes is the atom length and the atom type
        while( !$this->_stream->endOfStream() ) {
            
            // Gets the atom length
            $atomLength     = $this->_stream->bigEndianUnsignedLong();
            
            // Gets the atom type
            $atomType       = $this->_stream->read( 4 );
            
            // Gets the atom data length
            $atomDataLength = $atomLength - 8;
            
            // Storage for the current atom
            $atomObject     = NULL;
            
            // Checks the parsing level (top or not)
            if( $level === 0 ) {
                
                // Parent is the file itself for the top-level atoms
                $parent = $this->_mpeg4File;
            }
            
            // Checks if the current atom can be inserted in the parent, and if the atom class exists
            $validAtom          = $parent->validChildType( $atomType );
            
            if( !$validAtom ) {
                
                $errorMsg = ( $level === 0 ) ? 'Atom ' . $atomType . ' cannot be stored as a top-level atom' : 'Atom ' . $atomType . ' cannot be stored in atom ' . $parent->getType();
                
                // Adds a warning
                $this->_warnings[] = array(
                    'atomType'   => $atomType,
                    'atomLength' => $atomLength,
                    'fileOffset' => $this->_stream->getOffset() - 8,
                    'parseLevel'      => $level,
                    'hierarchy'  => ( $level === 0 ) ? '' : implode( ' / ', $parent->getHierarchy() ),
                    'message'    => $errorMsg
                );
                
                if( $this->_allowInvalidStucture ) {
                    
                    $parent->allowAnyChildrenType( true );
                    $atomObject = $parent->addChild( $atomType );
                    $parent->allowAnyChildrenType( false );
                }
                
            } else {
                
                $atomObject = $parent->addChild( $atomType );
            }
            
            if( $atomLength === 0 ) {
                
                return false;
                
            } elseif( $atomLength === 1 ) {
                
                $length1          = $this->_stream->bigEndianUnsignedLong();
                $length2          = $this->_stream->bigEndianUnsignedLong();
                $atomSize         = ( double )( ( $length1 << 32 ) + $length2 );
                $atomDataLength   = $atomDataLength + 8;
                
                if( $atomObject ) {
                    
                    $atomObject->setExtended( true );
                }
            }
            
            if( $atomDataLength ) {
                
                if( $atomObject && is_subclass_of( $atomObject, 'Woops\Mpeg4\ContainerAtom' ) ) {
                    
                    $this->_parseFile( $atomDataLength, $level + 1, $atomObject );
                    
                } else {
                    
                    $readData       = true;
                    $dataBytesCount = 0;
                    $letters        = array();
                    $binData        = '';
                    $data           = $this->_stream->read( $atomDataLength );
                    
                    if( $atomObject ) {
                        
                        $atomObject->setRawData( $data );
                    }
                }
            }
                
            $bytesRead += $atomLength;
                    
            if( $bytes > 0 && $bytes == $bytesRead ) {
                
                return false;
            }
        }
    }
    
    /**
     * Gets the Mpeg4_File instance
     * 
     * @return  object  The instance of Mpeg4_File
     */
    public function getMpeg4File()
    {
        return $this->_mpeg4File;
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
