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
 * 
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Tiff.Tag
 */
class Woops_Tiff_Tag_Photoshop extends Woops_Tiff_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The TIFF tag type
     */
    protected $_type = 0x8649;
    
    /**
     * Reads tag value(s) from the binary stream
     * 
     * @param   Woops_Tiff_Binary_Stream    The binary stream
     * @param   int                         The number of values
     * @return  void
     */
    protected function _readValuesFromStream( $stream, $count )
    {
        $this->_values[] = $stream->read( $count );
    }
}
