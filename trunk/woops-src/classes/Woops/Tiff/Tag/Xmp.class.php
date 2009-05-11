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
namespace Woops\Tiff\Tag;

/**
 * 
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff.Tag
 */
class Xmp extends \Woops\Tiff\Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The TIFF tag type
     */
    protected $_type = 0x02BC;
    
    /**
     * Reads tag value(s) from the binary stream
     * 
     * @param   Woops\Tiff\Binary\Stream    The binary stream
     * @param   int                         The number of values
     * @return  void
     */
    protected function _readValuesFromStream( \Woops\Tiff\Binary\Stream $stream, $count )
    {
        $this->_values[] = $stream->read( $count );
    }
}
