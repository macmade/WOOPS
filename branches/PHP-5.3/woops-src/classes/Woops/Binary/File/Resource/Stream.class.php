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
namespace Woops\Binary\File\Resource;

/**
 * Binary file resource stream
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Binary.File.Resource
 */
class Stream extends \Woops\Binary\Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * Class constructor
     * 
     * @param   resource    The file handle for which to create a binary stream
     * @param   boolean     Whether to close the file handle or not
     * @return  void
     * @see     Woops\Binary\Stream::__construct
     */
    public function __construct( $handle, $closeHandle = true )
    {
        // Checks if the file exists
        if( !is_resource( $handle ) ) {
            
            // Error - The file does not exist
            throw new Stream\Exception(
                'Passed argument must be a valid file handle',
                Stream\Exception::EXCEPTION_NO_RESOURCE
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
