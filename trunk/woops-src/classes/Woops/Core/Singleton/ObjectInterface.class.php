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
namespace Woops\Core\Singleton;

/**
 * Interface for the singleton classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Singleton
 */
interface ObjectInterface
{
    /**
     * Gets the unique instance (singleton)
     * 
     * @return  Woops\Core\SIngleton\ObjectInterface    The requested instance
     */
    public static function getInstance();
}
