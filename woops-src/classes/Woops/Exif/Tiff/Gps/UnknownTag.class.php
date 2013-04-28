<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * Unknown EXIF GPS TIFF tag
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Exif.Tiff.Gps
 */
class Woops_Exif_Tiff_Gps_UnknownTag extends Woops_Exif_Tiff_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The TIFF tag type
     */
    protected $_type = 0x0000;
    
    /**
     * Class constructor
     * 
     * @param   Woops_Tiff_File The TIFF file in which the tag is contained
     * @param   int             The TIFF tag type
     * @return  void
     */
    public function __construct( Woops_Tiff_File $file, $type )
    {
        // Stores the tag type
        $this->_type = ( int )$type;
        
        // Calls the parent constructor
        parent::__construct( $file );
    }
}
