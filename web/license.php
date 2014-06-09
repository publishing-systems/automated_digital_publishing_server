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
 * @file $/web/license.php
 * @brief License information page.
 * @author Stephan Kreutzer
 * @since 2014-06-06
 */



session_start();

require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("license"));

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
     "<!DOCTYPE html\n".
     "    PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n".
     "    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n".
     "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n".
     "    <head>\n".
     "        <title>".LANG_PAGETITLE."</title>\n".
     "        <link rel=\"stylesheet\" type=\"text/css\" href=\"mainstyle.css\"/>\n".
     "        <meta http-equiv=\"expires\" content=\"1296000\"/>\n".
     "        <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=UTF-8\"/>\n".
     "    </head>\n".
     "    <body>\n".
     "        <div class=\"mainbox\">\n".
     "          <div class=\"mainbox_header\">\n".
     "            <h1 class=\"mainbox_header_h1\">".LANG_HEADER."</h1>\n".
     "          </div>\n".
     "          <div class=\"mainbox_body\">\n";
     
require_once("./license.inc.php");
echo getHTMLLicenseNotification("license");
echo "<hr/>\n";
echo getHTMLLicenseFull("license");
     
echo "          </div>\n".
     "        </div>\n".
     "    </body>\n".
     "</html>\n";



?>
