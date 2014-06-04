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
 * @file $/web/upload.php
 * @author Stephan Kreutzer
 * @since 2014-05-31
 */



session_start();

require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("upload"));

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
     "      <div>\n".
     "        <h1>".LANG_HEADER."</h1>\n".
     "\n";

if (isset($_POST['upload']) !== true)
{
    echo "        <p>\n".
         "          ".LANG_UPLOAD_DESCRIPTION."\n".
         "        </p>\n".
         "\n".
         "        <form enctype=\"multipart/form-data\" action=\"upload.php\" method=\"post\">\n".
         "          <fieldset>\n".
         "            <input type=\"file\" name=\"file\" accept=\"application/vnd.oasis.opendocument.text\"/><br/>\n".
         "            <input type=\"submit\" name=\"upload\" value=\"".LANG_UPLOAD_SUBMIT."\"/><br/>\n".
         "          </fieldset>\n".
         "        </form>\n";
}
else
{
    $validFile = true;
    
    if (isset($_FILES['file']) !== true)
    {
        echo LANG_UPLOAD_GENERAL_ERROR."<br/>\n";
        $validFile = false;
    }
    
    if ($validFile === true)
    {
        if ($_FILES['file']['error'] != 0)
        {
            echo LANG_UPLOAD_SPECIFIC_ERROR_PRE.$_FILES['file']['error'].LANG_UPLOAD_SPECIFIC_ERROR_POST."<br/>\n";
            $validFile = false;
        }
    }

    if ($validFile === true)
    {
        if ($_FILES['file']['size'] > 5242880)
        {
            echo LANG_UPLOAD_FILESIZE_ERROR_PRE."5242880".LANG_UPLOAD_FILESIZE_ERROR_POST."<br/>\n";
            $validFile = false;
        }
    }
    
    if ($validFile === true)
    {
        $id = md5(uniqid(rand(), true));
    
        if (move_uploaded_file($_FILES['file']['tmp_name'], "input/".$id) === true)
        {
            $_SESSION['input'] = $id;
            $_SESSION['step'] = 1;
        
            echo "        <p>\n".
                 "          ".LANG_UPLOAD_SUCCESS."\n".
                 "        </p>\n".
                 "        <div>\n".
                 "          <a href=\"convert.php\">".LANG_UPLOAD_CONTINUE."</a>\n".
                 "        </div>\n";
        }
        else
        {
            echo LANG_UPLOAD_CANT_SAVE."<br/>\n";
        }
    }
}

echo "\n".
     "        <div>\n".
     "          <a href=\"index.php\">".LANG_UPLOAD_ABORT."</a>\n".
     "        </div>\n".
     "      </div>\n".
     "\n".
     "\n".
     "  </body>\n".
     "\n".
     "\n".
     "</html>\n";

?>
