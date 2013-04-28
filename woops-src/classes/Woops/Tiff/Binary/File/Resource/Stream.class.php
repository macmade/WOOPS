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

# $Id: Stream.class.php 559 2009-03-04 17:18:24Z macmade $

/**
 * TIFF binary file resource stream
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Tiff.Binary.File.Resource
 */
class Woops_Tiff_Binary_File_Resource_Stream extends Woops_Tiff_Binary_Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Class constructor
     * 
     * @param   resource    The file handle for which to create a binary stream
     * @param   boolean     Whether to close the file handle or not
     * @return  void
     * @see     Woops_Tiff_Binary_Stream::__construct
     */
    public function __construct( $handle, $closeHandle = true )
    {
        // Checks if the file exists
        if( !is_resource( $handle ) ) {
            
            // Error - The file does not exist
            throw new Woops_Tiff_Binary_File_Resource_Stream_Exception(
                'Passed argument must be a valid file handle',
                Woops_Tiff_Binary_File_Resource_Stream_Exception::EXCEPTION_NO_RESOURCE
            );
        }
        
        // Storage
        $data = '';
        
        // Reads until the end of the file handle
        while( !feof( $handle ) ) {
            
            // Reads from the file handle
            $data .= fread( $handle, 8192 );
        }
        
        // Checks if we must close the file handle
        if( $closeHandle ) {
            
            // Closes the file handle
            fclose( $handle );
        }
        
        // Calls the parent constructor
        parent::__construct( $data );
    }
}
