<?php
/* Copyright (C) 2014  Stephan Kreutzer
 *
 * This file is part of automated_digital_publishing_server.
 *
 * automated_digital_publishing_server is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License version 3 or any later version,
 * as published by the Free Software Foundation.
 *
 * automated_digital_publishing_server is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License 3 for more details.
 *
 * You should have received a copy of the GNU Affero General Public License 3
 * along with automated_digital_publishing_server. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * @file $/web/lang/en/project_generate_type2.lang.php
 * @author Stephan Kreutzer
 * @since 2014-10-02
 */



define("LANG_PAGETITLE", "Processing");
define("LANG_HEADER", "Processing");
define("LANG_FINDPROJECTSDIRECTORYFAILED", "Project directory is missing.");
define("LANG_FINDPROJECTLISTFAILED", "Project list is missing.");
define("LANG_READPROJECTLISTFAILED", "Can't read project list.");
define("LANG_FINDPROJECTINPROJECTLISTFAILED", "Can't find project in the project list.");
define("LANG_FINDPROJECTCONFIGURATIONFAILED", "Can't find project configuration.");
define("LANG_NOEPUBINPUTFILECONFIGURED", "No EPUB uploaded yet.");
define("LANG_NOEPUBINPUTFILEPATHCONFIGURED", "The uploaded EPUB isn't configured correctly.");
define("LANG_NOEXTRACTIONDIRECTORYCONFIGURED", "No extraction directory configured.");
define("LANG_NOEXTRACTIONDIRECTORYPATHCONFIGURED", "The extraction directory isn't configured correctly.");
define("LANG_CREATINGEPUB2HTML1CONFIGURATIONFAILED", "The configuration for the EPUB extraction wasn't created.");
define("LANG_WRITEPROJECTCONFIGURATIONFAILED", "Can't write project configuration.");
define("LANG_READEXTRACTIONCONFIGURATIONFAILED", "Can't read configuration for the EPUB extraction.");
define("LANG_NOEPUBINPUTFILECONFIGUREDINEXTRACTIONCONFIGURATION", "The EPUB extraction configuration is missing the EPUB input file.");
define("LANG_NOEXTRACTIONDIRECTORYEXTRACTIONCONFIGURATION", "The EPUB extraction configuration is missing the extraction directory.");
define("LANG_WRITEEXTRACTIONCONFIGURATIONFAILED", "Can't write EPUB extraction configuration.");
define("LANG_CREATINGEXTRACTIONDIRECTORYFAILED", "Can't create extraction directory.");
define("LANG_BUSY", "The conversion facility of the server is currently busy with another job.");
define("LANG_GENERALERROR", "An error has occurred.");
define("LANG_EXTRACTIONNOINDEXXMLRESULTFILE", "The EPUB extraction didn't create a result list.");
define("LANG_EXTRACTEPUBSUCCESS", "Extraction of the EPUB completed successfully.");
define("LANG_NOTEXTRACTED", "EPUB isn't extracted.");
define("LANG_PROJECTCONFIGURATIONMISSINGEXTRACTIONDIRECTORY", "The project configuration is missing the extraction directory.");
define("LANG_PROJECTCONFIGURATIONEXTRACTIONDIRECTORYINCOMPLETE", "The extraction directory setting in the project configuration is incomplete.");
define("LANG_READEXTRACTIONINDEXXMLRESULTFILEFAILED", "Can't read the EPUB extraction result list.");
define("LANG_EXTRACTEDEPUBEMPTY", "The extracted EPUB file doesn't contain any HTML file.");
define("LANG_WRITETRANSFORMATIONCONFIGURATIONFAILED", "Can't write the transformation configuration file.");
define("LANG_PREPAREHTMLSUCCESS", "The preparation was completed successfully.");
define("LANG_HTMLTOWORDPRESSFEATURENOTCONFIGURED", "This feature isn't configured.");
define("LANG_FILENUMBERNOTANUMBER", "The number of the HTML file to transmit is invalid.");
define("LANG_FILENUMBERFILEDOESNTEXIST", "There is no corresponding prepared HTML file for the given file number.");
define("LANG_WRITEJOBFILEFAILED", "Couldn't write the configuration file for this WordPress transmission job.");
define("LANG_RESULTINCOMPLETE", "The response of the WordPress installation is incomplete.");
define("LANG_READRESULTXMLPROBLEM", "Can't read the response of the WordPress installation.");
define("LANG_RESULTXMLINCOMPLETE", "Values are missing in the response of the WordPress installation.");
define("LANG_READRESULTXMLVALUEERROR", "Can't read a value from the response of the WordPress installation.");
define("LANG_POSTIDISNTNUMERIC", "The ID received from WordPress isn't numeric.");
define("LANG_HTMLTOWORDPRESSUPLOADSUCCESS", "Transmission of the HTML file to the configured WordPress installation was successful and got the ID:");
define("LANG_CONTINUE", "Continue");
define("LANG_LEAVE", "Leave");
define("LANG_LICENSE", "Licensing");



?>
