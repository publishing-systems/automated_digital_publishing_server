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
 * @file $/web/convert.php
 * @author Stephan Kreutzer
 * @since 2014-05-31
 */



session_start();

require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("convert"));

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
     "<!DOCTYPE html\n".
     "    PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n".
     "    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n".
     "\n".
     "\n".
     "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n".
     "\n".
     "\n".
     "  <head>\n".
     "\n".
     "\n".
     "      <title>".LANG_PAGETITLE."</title>\n".
     "\n".
     "      <meta http-equiv=\"expires\" content=\"1296000\" />\n".
     "      <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=UTF-8\" />\n".
     "\n".
     "\n".
     "  </head>\n".
     "\n".
     "\n".
     "  <body>\n".
     "\n".
     "\n".
     "      <div>\n";

$result = include("./automated_digital_publishing/odt2html/odt2html1/caller.php");

if (isset($_SESSION['output']) === true)
{
    if (file_exists("./output/".$_SESSION['output'].".html") === true)
    {
        $xml = simplexml_load_file("./output/".$_SESSION['output'].".html");

        foreach ($xml->body->children() as $child)
        {
            echo $child->asXML();
        }
    }
    else
    {
        echo "<pre>".$result."</pre>\n";
    }
}

echo "\n".
     "        <div>\n".
     "          <a href=\"index.php\">".LANG_LEAVE."</a>\n".
     "        </div>\n".
     "      </div>\n".
     "\n".
     "\n".
     "  </body>\n".
     "\n".
     "\n".
     "</html>\n";

?>
