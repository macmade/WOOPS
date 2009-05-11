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

# $Id: Stream.class.php 559 2009-03-04 17:18:24Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Gzip\Binary\File;

/**
 * GZIP binary file stream
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Gzip.Binary.File
 */
class Stream extends Resource\Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Class constructor
     * 
     * @param   string  The path to the file for which to create a binary stream
     * @return  void
     * @see     Woops\Gzip\Binary\File\Resource\Stream::__construct
     */
    public function __construct( $filePath )
    {
        // Checks if the file exists
        if( !file_exists( $filePath ) || !is_file( $filePath ) ) {
            
            // Error - The file does not exist
            throw new Stream\Exception(
                'The requested file does not exist (path: ' . $filePath . ')',
                Stream\Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the file is readable
        if( !is_readable( $filePath ) ) {
            
            // Error - The file is not readable
            throw new Stream\Exception(
                'The requested file is not readable (path: ' . $filePath . ')',
                Stream\Exception::EXCEPTION_FILE_NOT_READABLE
            );
        }
        
        // Opens a file handle
        $handle = fopen( $filePath, 'rb' );
        
        // Calls the parent constructor
        parent::__construct( $handle );
    }
}
