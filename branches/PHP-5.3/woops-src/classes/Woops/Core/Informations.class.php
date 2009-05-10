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
namespace Woops\Core;

/**
 * Class that contains WOOPS information constants
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core
 */
abstract class Informations
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE       = '5.3.0';
    
    /**
     * The WOOPS version
     */
    const WOOPS_VERSION        = '0.0.0';
    
    /**
     * The WOOPS version suffix (like rc, beta, aplha, dev, etc)
     */
    const WOOPS_VERSION_SUFFIX = 'dev';
}
