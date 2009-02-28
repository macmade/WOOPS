<?php
	
	// Aborts the script. This is recommended on production boxes, as this
	// script may reveal important informations on the server.
	#exit();
	
	// Includes the check scripts
	require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../' . DIRECTORY_SEPARATOR . 'install-check' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Woops_Check_Environment.class.php' );
	require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../' . DIRECTORY_SEPARATOR . 'install-check' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Woops_Check_Filesystem.class.php' );
	require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../' . DIRECTORY_SEPARATOR . 'install-check' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Woops_Check_Configuration.class.php' );
	
	// Creates instances of the check scripts
	$CHECK_ENV     = new Woops_Check_Environment();
	$CHECK_FS      = new Woops_Check_Filesystem();
	$CHECK_CONF    = new Woops_Check_Configuration();
	
	// Checks for errors or warnings that could prevent the installation
	$INSTALL_ERROR = ( $CHECK_ENV->hasErrors || $CHECK_FS->hasErrors || $CHECK_FS->hasWarnings );
	
	// If no error, we are able to use WOOPS
	if( !$INSTALL_ERROR ) {
		
		// Disables the class cache (and AOP) for now
		define( 'WOOPS_CLASS_CACHE_MODE_OFF', true );
		
		// Includes the initialization script
		require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'init.inc.php' );
		
		// Creates an instance of the install form
		$INSTALL_FORM = new Woops_Mod_Install_Form();
	}
?>
<?xml version="1.0" encoding="utf-8"?>

<!-- $Id$ -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		
		<!--
		
		################################################################################
		#                                                                              #
		#                WOOPS - Web Object Oriented Programming System                #
		#                                                                              #
		#                               COPYRIGHT NOTICE                               #
		#                                                                              #
		# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
		# All rights reserved                                                          #
		################################################################################
		
		-->
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>WOOPS - Web Object Oriented Programming System</title>
		<link rel="schema.dc" href="http://purl.org/metadata/dublin_core_elements" />
		<link rel="stylesheet" rev="stylesheet" href="css/base.css" type="text/css" media="screen" charset="utf-8" />
		<link rev="made" href="mailto:macmade@eosgarden.com" />
		<meta http-equiv="content-language" content="en" />
		<meta http-equiv="reply-to" content="macmade@eosgarden.com" />
		<meta name="author" content="Jean-David Gadina, eosgarden" />
		<meta name="copyright" content="Copyright, eosgarden, 2009" />
		<meta name="DC.Creator" content="Jean-David Gadina, eosgarden" />
		<meta name="DC.Language" scheme="NISOZ39.50" content="en" />
		<meta name="DC.Rights" content="Copyright, eosgarden, 2009" />
		<meta name="generator" content="BBEdit 9.1" />
		<meta name="rating" content="General" />
		<meta name="robots" content="all" />
	</head>
	<body>
		<div id="frame">
			<div id="content">
				<h1>WOOPS installation</h1>
				<?php
					
					if( $INSTALL_ERROR ) {
						
				?>
				<div class="install-error">
					<h4>Problems detected</h4>
					<div class="message">
						We detected errors or warnings that could prevent the installation of WOOPS.<br />
						Please run the installation check script</a> to find out more.
					</div>
				</div>
				<?php
						
					} else {
						
						print $INSTALL_FORM;
					}
					
				?>
			</div>
			<div id="footer">
				<div id="copyright">
					eosgarden © 2009 / lausanne - switzerland / info(at)eosgarden / www.eosgarden.com
				</div>
				<div id="w3c">
					<a href="http://validator.w3.org/check?uri=referer" title="Valid XHTML Strict"><img src="css/w3c-xhtml.png" alt="valid xhtml strict" width="80" height="15" /></a>
					<a href="http://jigsaw.w3.org/css-validator/check/referer" title="Valid CSS"><img src="css/w3c-css.png" alt="valid css" width="80" height="15" /></a>
				</div>
			</div>
		</div>
	</body>
</html>
