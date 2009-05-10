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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Swf;

/**
 * Abstract for the SWF tag classes
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf
 */
abstract class Tag extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    abstract public function processData( Binary\Stream $stream );
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x00;
    
    /**
     * The instance of the SWF file in which the tag is contained
     */
    protected $_file = NULL;
    
    /**
     * Class constructor
     * 
     * @param   Woops\Swf\File  The instance of the SWF file in which the tag is contained
     */
    public function __construct( File $file )
    {
        $this->_file = $file;
    }
    
    /**
     * Gets the SWF tag type
     * 
     * @return  int     The SWF tag type
     */
    public function getType()
    {
        return $this->_type;
    }
}
