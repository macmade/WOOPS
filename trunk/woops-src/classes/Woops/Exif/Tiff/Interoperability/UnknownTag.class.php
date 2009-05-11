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
namespace Woops\Exif\Tiff\Interoperability;

/**
 * Unknown EXIF Interoperability TIFF tag
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Exif.Tiff.Interoperability
 */
class UnknownTag extends Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The TIFF tag type
     */
    protected $_type = 0x0000;
    
    /**
     * Class constructor
     * 
     * @param   Woops\Tiff\File The TIFF file in which the tag is contained
     * @param   int             The TIFF tag type
     * @return  void
     */
    public function __construct( \Woops\Tiff\File $file, $type )
    {
        // Stores the tag type
        $this->_type = ( int )$type;
        
        // Calls the parent constructor
        parent::__construct( $file );
    }
}
