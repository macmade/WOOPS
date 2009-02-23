<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 file parser
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4
 */
class Woops_File_Mpeg4_Parser extends Woops_File_Parser_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * An instance of the Woops_File_Mpeg4_File class
     */
    protected $_mpeg4File            = NULL;
    
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
        $this->_mpeg4File            = new Woops_File_Mpeg4_File();
        
        // Calls the parent constructor
        parent::__construct( $file );
    }
    
    protected function _parseFile( $bytes = 0, $level = 0, $parent = NULL )
    {
        // Number of bytes read in the current parsing level
        $bytesRead = 0;
        
        // Reads 8 bytes of the MPEG-4 files till the end of the file
        // 8 bytes is the atom length and the atom type
        while( $chunk = $this->_read( 8 ) ) {
            
            // Gets the atom length
            $atomLength     = self::$_binUtils->bigEndianUnsignedLong( $chunk );
            
            // Gets the atom type
            $atomType       = substr( $chunk, 4 );
            
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
                    'fileOffset' => ftell( $this->_fileHandle ) - 8,
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
                
                $lengthData       = $this->_read( 8 );
                $length1          = self::$_binUtils->bigEndianUnsignedLong( $lengthData, 0 );
                $length2          = self::$_binUtils->bigEndianUnsignedLong( $lengthData, 4 );
                $atomSize         = ( double )( ( $length1 << 32 ) + $length2 );
                $atomDataLength   = $atomDataLength + 8;
                
                if( $atomObject ) {
                    
                    $atomObject->setExtended( true );
                }
            }
            
            if( $atomDataLength ) {
                
                if( $atomObject && is_subclass_of( $atomObject, 'Woops_File_Mpeg4_ContainerAtom' ) ) {
                    
                    $this->_parseFile( $atomDataLength, $level + 1, $atomObject );
                    
                } else {
                    
                    $readData       = true;
                    $dataBytesCount = 0;
                    $letters        = array();
                    $binData        = '';
                    $data           = $this->_read( $atomDataLength );
                    
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
