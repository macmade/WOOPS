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
namespace Woops\Yaml\File;

/**
 * YAML file parser class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Yaml.File
 */
class Parser extends \Woops\Yaml\Parser
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Class constructor
     * 
     * @param   string  The path to the YAML file
     * @return  void
     */
    public function __construct( $path )
    {
        // Checks if the file exists
        if( !file_exists( $path ) ) {
            
            // The file does not exist
            throw new Parser\Exception(
                'The requested file does not exist (path: ' . $path . ')',
                Parser\Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the file is readable
        if( !file_exists( $path ) ) {
            
            // The file is not readable
            throw new Parser\Exception(
                'The requested file is not readable (path: ' . $path . ')',
                Parser\Exception::EXCEPTION_FILE_NOT_READABLE
            );
        }
        
        // Calls the parent constructor
        parent::__construct( file_get_contents( $path ) );
    }
}
