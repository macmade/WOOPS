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

// Sets the error reporting level to the highest possible value
error_reporting( E_ALL | E_STRICT );

// Checks the PHP version
if( ( double )PHP_VERSION < 5.3 ) {
    
    // We are not running PHP 5.3 or greater
    trigger_error(
        'PHP version 5.3 is required to use this script (actual version is ' . PHP_VERSION . ')',
        E_USER_ERROR
    );
}

// Sets a dummy timezone, to prevent warnings if an error occurs before the configured timezone is set
date_default_timezone_set( 'Europe/Zurich' );

// File encoding
declare( ENCODING = 'UTF-8' );

// Checks for the SPL
if( !function_exists( 'spl_autoload_register' ) ) {
    
    // The SPL is unavailable
    throw new Exception(
        'The SPL (Standard PHP Library) is required to use this script'
    );
}

// Checks for the SimpleXMLElement class
if( !class_exists( 'SimpleXMLElement' ) ) {
    
    // SimpleXMLElement is unavailable
    throw new Exception(
        'The SimpleXMLElement class is required to use this script'
    );
}

// Includes the Woops class manager
require_once(
    __DIR__
  . DIRECTORY_SEPARATOR
  . 'classes'
  . DIRECTORY_SEPARATOR
  . 'Woops'
  . DIRECTORY_SEPARATOR
  . 'Core'
  . DIRECTORY_SEPARATOR
  . 'ClassManager.class.php'
);

// Registers an SPL autoload method to use to load the classes form the Woops project
spl_autoload_register( array( 'Woops\Core\ClassManager', 'autoLoad' ) );

// Sets the error and exception handlers - From now every mistake will produce a fatal error
set_exception_handler( array( 'Woops\Core\Exception\Handler', 'handleException' ) );
set_error_handler(     array( 'Woops\Core\Error\Handler',     'handleError' ) );

// Sets the default timezone
date_default_timezone_set( Woops\Core\Config\Getter::getInstance()->getVar( 'time', 'timezone' ) );

// Loads the active modules
Woops\Core\Module\Manager::getInstance()->initModules();