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
 * ZIP file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Zip
 */
class Woops_Zip_File
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The local file headers
     */
    protected $_localFileHeaders = array();
    
    /**
     * The data descriptors
     */
    protected $_dataDescriptors  = array();
    
    /**
     * The ZIP central directory
     */
    protected $_centralDirectory = NULL;
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos      = 0;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        $this->_centralDirectory = new Woops_Zip_Central_Directory();
    }
    
    /**
     * Gets the current file header object (SPL Iterator method)
     * 
     * @return  Woops_Zip_Central_File_Header   The current file header object
     */
    public function current()
    {
        return $this->_localFileHeaders[ $this->_iteratorPos ];
    }
    
    /**
     * Moves to the next file header object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current file header object (SPL Iterator method)
     * 
     * @return  int     The index of the current file header
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next file header object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next file header, otherwise false
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_localFileHeaders );
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
     * Gets the central directory object
     * 
     * @return  Woops_Zip_Central_Directory The central directory object
     */
    public function getCentralDirectory()
    {
        return $this->_centralDirectory;
    }
    
    /**
     * Adds a local file header
     * 
     * @param   Woops_Zip_Local_File_Header The local file header
     * @return  void
     */
    public function addLocalFileHeader( Woops_Zip_Local_File_Header $header )
    {
        $this->_localFileHeaders[] = $header;
    }
    
    /**
     * Adds a data descriptor
     * 
     * @param   Woops_Zip_Data_Descriptor   The data descriptor
     * @return  void
     */
    public function addDataDescriptor( Woops_Zip_Data_Descriptor $descriptor )
    {
        $this->_dataDescriptors[] = $descriptor;
    }
}
