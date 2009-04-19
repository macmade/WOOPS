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

/**
 * TIFF file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff
 */
class Woops_Tiff_File implements Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
        $this->_header = new Woops_Tiff_Header();
    }
    
    /**
     * Gets the current IFD object (SPL Iterator method)
     * 
     * @return  Woops_Tiff_Ifd  The current SWF tag object
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
     * @return  Woops_Tiff_Header   The TIFF header
     */
    public function getHeader()
    {
        return $this->_header;
    }
    
    /**
     * Creates a new IFD in the current TIFF file
     * 
     * @param   string          An optionnal PHP classname, for a custom IFD object (the class MUST extends the Woops_Tiff_Ifd class)
     * @return  Woops_Tiff_Ifd  The IFD object
     * @throws  Woops_Tiff_File_Exception   If the custom class does not extends Woops_Tiff_Ifd
     */
    public function newIfd( $customClass = '' )
    {
        // Checks for a custom class
        if( $customClass ) {
            
            // Creates a reflection object for the custom class
            $ref = Woops_Core_Reflection_Class::getInstance( $customClass );
            
            // Checks if the custom class implements the Woops_Tiff_Ifd_Interface class
            if( !$ref->implementsInterface( 'Woops_Tiff_Ifd_Interface' ) ) {
                
                // Error - INvalid IFD class
                throw new Woops_Tiff_File_Exception(
                    'Invalid IFD custom class \'' . $customClass . '\'. It must implement the Woops_Tiff_Ifd_Interface interface',
                    Woops_Tiff_File_Exception::EXCEPTION_INVALID_IFD_CLASS
                );
            }
            
            // Creates the IFD
            $ifd = new $customClass( $this );
            
        } else {
            
            // Creates the IFD
            $ifd = new Woops_Tiff_Ifd( $this );
        }
        
        // Stores the IFD
        $this->_ifds[] = $ifd;
        
        // Returns the IFD
        return $ifd;
    }
}
