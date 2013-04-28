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

# $Id$

/**
 * Placeholder for the unknown PNG chunks
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Png
 */
class Woops_Png_UnknownChunk extends Woops_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = '';
    
    /**
     * Class constructor
     * 
     * @param   Png_File    The instance of the Png_File class in which the chunk is placed
     * @param   string      The chunk type
     * @return  NULL
     */
    public function __construct( Png_File $pngFile, $type )
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
        return new stdClass();
    }
}
