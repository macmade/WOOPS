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
namespace Woops\Swf\Tag\Sound\Stream\Head;

/**
 * 
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag.Sound.Stream.Head
 */
class 2 extends \Woops\Swf\Tag\Sound\Stream\Head
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x2D;
}
