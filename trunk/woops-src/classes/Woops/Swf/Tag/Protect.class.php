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

/**
 * SWF protect tag
 * 
 * The Protect tag marks a file as not importable for editing in an authoring
 * environment. If the Protect tag contains no data (tag length = 0), the SWF
 * file cannot be imported. If this tag is present in the file, any authoring
 * tool should prevent the file from loading for editing.
 * If the Protect tag does contain data (tag length is not 0), the SWF file can
 * be imported if the correct password is specified. The data in the tag is a
 * null-terminated string that specifies an MD5-encrypted password. Specifying a
 * password is only supported in SWF 5 or later.
 * The MD5 password encryption algorithm used was written by Poul-Henning Kamp
 * and is freely distributable. It resides in the FreeBSD tree at
 * src/lib/libcrypt/crypt-md5.c. The EnableDebugger tag also uses MD5 password
 * encryption algorithm.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag
 */
class Woops_Swf_Tag_Protect extends Woops_Swf_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type     = 0x18;
    
    /**
     * The MD5-encrypted password
     */
    protected $_password = '';
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        // Checks if we have data, meaning we have a MD5-encrypted password
        if( !$stream->endOfStream() ) {
            
            // Gets the MD5-encrypted password
            $this->_password = $stream->nullTerminatedString();
        }
    }
    
    /**
     * Gets the MD5-encrypted password
     * 
     * @return  string  The MD5-encrypted password
     */
    public function getPassword()
    {
        return $this->_password;
    }
    
    /**
     * Sets the MD5-encrypted password
     * 
     * @param   string  The MD5-encrypted password
     * @param   boolean If true, encode the passed string as MD5
     * @return  void
     */
    public function setPassword( $value, $encode = false )
    {
        $this->_password = ( $encode ) ? md5( ( string )$value ) : ( string )$value;
    }
}
