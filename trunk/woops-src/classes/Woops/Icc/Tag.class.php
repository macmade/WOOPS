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
namespace Woops\Icc;

/**
 * Abstract for the ICC tag classes
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Icc
 */
abstract class Tag extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The ICC tag type
     */
    protected $_type = 0x00000000;
    
    /**
     * Gets the ICC tag type
     * 
     * @param   boolean If true, returns the tag type as a string, otherwise as an integer
     * @return  int     The ICC tag type
     */
    public function getType( $asString = false )
    {
        if( $asString ) {
            
            $type = chr( ( $this->_type >> 24 ) )
                  . chr( ( $this->_type >> 16 ) & 0xFF )
                  . chr( ( $this->_type >> 8 )  & 0xFF )
                  . chr( ( $this->_type )       & 0xFF );
            
            return $type;
        }
        
        return $this->_type;
    }
}
