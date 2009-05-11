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
namespace Woops\Core\MultiSingleton;

/**
 * Interface for the multi-singleton classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.MultiSingleton
 */
interface ObjectInterface
{
    /**
     * Gets a singleton instance
     * 
     * @param   string  The instance name
     * @return  object  The requested instance
     */
    public static function getInstance( $instanceName );
}
