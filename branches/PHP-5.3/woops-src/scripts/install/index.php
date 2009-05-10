<?php
	
	// Includes the check scripts
	require_once( __DIR__ . DIRECTORY_SEPARATOR . '../' . DIRECTORY_SEPARATOR . 'install-check' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Woops_Check_Environment.class.php' );
	require_once( __DIR__ . DIRECTORY_SEPARATOR . '../' . DIRECTORY_SEPARATOR . 'install-check' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Woops_Check_Filesystem.class.php' );
	require_once( __DIR__ . DIRECTORY_SEPARATOR . '../' . DIRECTORY_SEPARATOR . 'install-check' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Woops_Check_Configuration.class.php' );
	
	// Creates instances of the check scripts
	$CHECK_ENV     = new Woops_Check_Environment();
	$CHECK_FS      = new Woops_Check_Filesystem();
	$CHECK_CONF    = new Woops_Check_Configuration();
	
	// Checks for errors or warnings that could prevent the installation
	$INSTALL_ERROR = ( $CHECK_ENV->hasErrors || $CHECK_FS->hasErrors || $CHECK_FS->hasWarnings );
	
	// Checks if we can run the installer
	if( $INSTALL_ERROR ) {
		
		// Errors detected
		$CONTENT = '<div class="box-warning">
						<h4>Problems detected</h4>
						<div class="message">
							We detected errors or warnings that could prevent the installation of WOOPS.<br />
							Please run the <a href="' . substr( str_replace( '//', '/', $_SERVER[ 'SCRIPT_NAME' ] ), 0, -10 ) . '-check/" title="Installation check">installation check script</a> to find out more.
						</div>
					</div>';
		
	} else {
		
		// Includes the initialization script
		require_once( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'init.inc.php' );
		
		// File encoding
		declare( ENCODING = 'UTF-8' );
		
		// Checks if the Install module is loaded
		if( !( Woops\Core\Module\Manager::getInstance()->isLoaded( 'Install' ) ) ) {
			
			// Install module not loaded
			$CONTENT = '<div class="box-error">
							<h4>Installation disabled</h4>
							<div class="message">
								The WOOPS installation module is currently disabled.<br />
								In order to access the installation script, please loads the "Install" module, either from the WOOPS module manager or by editing the WOOPS configuration file.
							</div>
						</div>';
			
		} else {
			
			// Creates an instance of the install form
			$INSTALL_FORM = new Woops\Mod\Install\Form();
			
			// Gets the install form content
			$CONTENT      = ( string )$INSTALL_FORM;
		}
	}
?>

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
		<meta name="author" content="Jean-David Gadina" />
		<meta name="copyright" content="Copyright (C) 2009 Jean-David Gadina" />
		<meta name="DC.Creator" content="Jean-David Gadina" />
		<meta name="DC.Language" scheme="NISOZ39.50" content="en" />
		<meta name="DC.Rights" content="Copyright (C) 2009 Jean-David Gadina" />
		<meta name="generator" content="BBEdit 9.1" />
		<meta name="rating" content="General" />
		<meta name="robots" content="all" />
	</head>
	<body>
		<div id="frame">
			<div id="content">
				<h1>WOOPS installation</h1>
				<?php print $CONTENT; ?>
			</div>
			<div id="footer">
				<div id="copyright">
					WOOPS - Web Object Oriented Programming System<br />
					Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)
				</div>
				<div id="w3c">
					<a href="http://validator.w3.org/check?uri=referer" title="Valid XHTML Strict"><img src="css/w3c-xhtml.png" alt="valid xhtml strict" width="80" height="15" /></a>
					<a href="http://jigsaw.w3.org/css-validator/check/referer" title="Valid CSS"><img src="css/w3c-css.png" alt="valid css" width="80" height="15" /></a>
				</div>
			</div>
		</div>
	</body>
</html>
