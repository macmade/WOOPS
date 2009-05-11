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
namespace Woops\Tiff;

/**
 * TIFF file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff
 */
class File extends \Woops\Core\Object implements \Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The TIFF header
     */
    protected $_header      = NULL;
    
    /**
     * The image file directories (IFD)
     */
    protected $_ifds        = array();
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        $this->_header = new Header();
    }
    
    /**
     * Gets the current IFD object (SPL Iterator method)
     * 
     * @return  Woops\Tiff\Ifd  The current IFD object
     */
    public function current()
    {
        return $this->_ifds[ $this->_iteratorPos ];
    }
    
    /**
     * Moves to the next IFD object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current IFD object (SPL Iterator method)
     * 
     * @return  int     The index of the current TIFF tag
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next IFD object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next TIFF IFD, otherwise false
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_ifds );
    }
    
    /**
     * Rewinds the SPL Iterator pointer (SPL Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_iteratorPos = 0;
    }
    
    /**
     * Gets the TIFF header
     * 
     * @return  Woops\Tiff\Header   The TIFF header
     */
    public function getHeader()
    {
        return $this->_header;
    }
    
    /**
     * Creates a new IFD in the current TIFF file
     * 
     * @param   string                      An optionnal PHP classname, for a custom IFD object (the class MUST extends the Woops\Tiff\Ifd class)
     * @return  Woops\Tiff\Ifd              The IFD object
     * @throws  Woops\Tiff\File\Exception   If the custom class does not extends Woops\Tiff\Ifd
     */
    public function newIfd( $customClass = '' )
    {
        // Checks for a custom class
        if( $customClass ) {
            
            // Creates a reflection object for the custom class
            $ref = \Woops\Core\Reflection::getClassReflector( $customClass );
            
            // Checks if the custom class implements the Woops\Tiff\Ifd\ObjectInterface class
            if( !$ref->implementsInterface( 'Woops\Tiff\Ifd\ObjectInterface' ) ) {
                
                // Error - Invalid IFD class
                throw new File\Exception(
                    'Invalid IFD custom class \'' . $customClass . '\'. It must implement the Woops\Tiff\Ifd\ObjectInterface interface',
                    File\Exception::EXCEPTION_INVALID_IFD_CLASS
                );
            }
            
            // Creates the IFD
            $ifd = new $customClass( $this );
            
        } else {
            
            // Creates the IFD
            $ifd = new Ifd( $this );
        }
        
        // Stores the IFD
        $this->_ifds[] = $ifd;
        
        // Returns the IFD
        return $ifd;
    }
}
