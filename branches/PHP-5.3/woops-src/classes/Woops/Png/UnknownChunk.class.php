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

# $Id$

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Png;

/**
 * Placeholder for the unknown PNG chunks
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Png
 */
class UnknownChunk extends Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The chunk type
     */
    protected $_type = '';
    
    /**
     * Class constructor
     * 
     * @param   Woops\Png\File  The instance of the PNG file in which the chunk is placed
     * @param   string          The chunk type
     * @return  NULL
     */
    public function __construct( File $pngFile, $type )
    {
        // Sets the chunk type
        $this->_type = substr( $type, 0, 4 );
        
        // Calls the parent constructor
        parent::__construct( $pngFile );
    }
    
    /**
     * Process the chunk data
     * 
     * This method will process the chunk raw data and returns human readable
     * values, stored as properties of an stdClass object. Please take a look
     * at the PNG specification for this specific chunk to see which data will
     * be extracted.
     * 
     * @return  stdClass    The human readable chunk data
     */
    public function getProcessedData()
    {
        return new \stdClass();
    }
}
