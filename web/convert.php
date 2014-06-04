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

if (isset($_SESSION['step']) === true)
{
    $error = true;

    if ($_SESSION['step'] === 1)
    {
        $result = include("./automated_digital_publishing/workflows/odt2html1_caller.php");

        if (is_numeric($result) === true)
        {
            $error = false;
            
            echo "        <p>\n".
                 "          ".LANG_BUSY."\n".
                 "        </p>\n";  
        }
        else
        {
            if (isset($_SESSION['output']) === true)
            {
                if (file_exists("./output/".$_SESSION['output'].".html") === true)
                {
                    $_SESSION['input'] = $_SESSION['output'];
                    $_SESSION['step'] = 2;
                    
                    $error = false;
                }
            }
        }
    }
    else if ($_SESSION['step'] === 2)
    {
        if (file_exists("./output/".$_SESSION['input'].".html") === true)
        {
            $xml = simplexml_load_file("./output/".$_SESSION['input'].".html");

            foreach ($xml->body->children() as $child)
            {
                echo $child->asXML();
            }
            
            $error = false;
        }
    }
    
    if ($error === false)
    {
        echo "        <div>\n".
             "          <a href=\"convert.php\">".LANG_CONTINUE."</a>\n".
             "        </div>\n";
    }
    else
    {
        echo "        <p>\n".
             "          ".LANG_ERROR."\n".
             "        </p>\n";    
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
