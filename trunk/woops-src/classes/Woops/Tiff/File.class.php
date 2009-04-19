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
class Woops_Tiff_File
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The TIFF header
     */
    protected $_header = NULL;
    
    /**
     * The image file directories (IFD)
     */
    protected $_ifds   = array();
    
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
     * @return  Woops_Tiff_Ifd  The IFD object
     */
    public function newIfd()
    {
        // Creates the IFD
        $ifd           = new Woops_Tiff_Ifd( $this );
        
        // Stores the IFD
        $this->_ifds[] = $ifd;
        
        // Returns the IFD
        return $ifd;
    }
}
