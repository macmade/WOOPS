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
 * YAML file parser class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Yaml.File
 */
class Woops_Yaml_File_Parser extends Woops_Yaml_Parser
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
            throw new Woops_Yaml_File_Parser_Exception(
                'The requested file does not exist (path: ' . $path . ')',
                Woops_Yaml_File_Parser_Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the file is readable
        if( !file_exists( $path ) ) {
            
            // The file is not readable
            throw new Woops_Yaml_File_Parser_Exception(
                'The requested file is not readable (path: ' . $path . ')',
                Woops_Yaml_File_Parser_Exception::EXCEPTION_FILE_NOT_READABLE
            );
        }
        
        // Calls the parent constructor
        parent::__construct( file_get_contents( $path ) );
    }
}
