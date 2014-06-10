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
 * @file $/web/project_upload.php
 * @brief Upload input files for a project.
 * @author Stephan Kreutzer
 * @since 2014-06-08
 */



session_start();

if (isset($_SESSION['user_id']) !== true ||
    isset($_POST['project_nr']) !== true)
{
    exit();
}

if (is_numeric($_POST['project_nr']) !== true)
{
    exit();
}


require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("project_upload"));

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

if (isset($_POST['upload']) !== true)
{
    echo "            <p>\n".
         "              ".LANG_UPLOAD_DESCRIPTION."\n".
         "            </p>\n".
         "\n".
         "            <form enctype=\"multipart/form-data\" action=\"project_upload.php\" method=\"post\">\n".
         "              <fieldset>\n".
         "                <input type=\"file\" name=\"file\" accept=\"application/vnd.oasis.opendocument.text\"/><br/>\n".
         "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
         "                <input type=\"submit\" name=\"upload\" value=\"".LANG_UPLOAD_SUBMIT."\"/><br/>\n".
         "              </fieldset>\n".
         "            </form>\n";
}
else
{
    $success = true;
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        
        $success = false;
    }

    if ($success === true)
    {
        if (isset($_FILES['file']) !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_UPLOAD_GENERAL_ERROR."</span>\n".
                 "            </p>\n";
        
            $success = false;
        }
    }
    
    if ($success === true)
    {
        if ($_FILES['file']['error'] != 0)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_UPLOAD_SPECIFIC_ERROR_PRE.$_FILES['file']['error'].LANG_UPLOAD_SPECIFIC_ERROR_POST."</span>\n".
                 "            </p>\n";

            $success = false;
        }
    }

    if ($success === true)
    {
        if ($_FILES['file']['size'] > 5242880)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_UPLOAD_FILESIZE_ERROR_PRE."5242880".LANG_UPLOAD_FILESIZE_ERROR_POST."</span>\n".
                 "            </p>\n";

            $success = false;
        }
    }
    
    $id = null;
    
    if ($success === true)
    {
        $id = md5(uniqid(rand(), true));
    
        if (move_uploaded_file($_FILES['file']['tmp_name'], "./projects/user_".$_SESSION['user_id']."/".$id) !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_UPLOAD_CANT_SAVE."</span>\n".
                 "            </p>\n";
            
            $success = false;
        }
    }
    
    $projectConfigurationFile = null;
    
    if ($success === true)
    {
        $projectConfigurationFile = GetProjectConfigurationFile($_POST['project_nr']);

        if (is_string($projectConfigurationFile) !== true)
        {
            $success = false;
        }
    }

    $xml = null;

    if ($success === true)
    {
        $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile);

        if ($xml == false)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
                 "            </p>\n";
        
            $success = false;
        }
    }

    if ($success === true)
    {
        if (isset($xml->in) !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
                 "            </p>\n";
        
            $success = false;
        }
    }
    
    if ($success === true)
    {
        $inFile = $xml->in->addChild("inFile");
        $inFile->addAttribute("path", $id);
        $inFile->addAttribute("display", $_FILES['file']['name']);
        
        $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile, $xml->asXML());
        
        if ($success === false ||
            $success == 0)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_WRITETOPROJECTCONFIGURATIONFAILED."</span>\n".
                 "            </p>\n";
            
            $success = false;
        }
        else
        {
            $success = true;
        }
    }
    
    if ($success === true)
    {
        echo "        <p>\n".
             "          <span class=\"success\">".LANG_UPLOAD_SUCCESS."</span>\n".
             "        </p>\n".
             "        <form action=\"project_edit.php\" method=\"post\">\n".
             "          <fieldset>\n".
             "            <input type=\"submit\" value=\"".LANG_UPLOAD_CONTINUE."\"/>\n".
             "            <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "          </fieldset>\n".
             "        </form>\n";
    }
}

echo "          </div>\n".
     "        </div>\n".
     "        <div class=\"footerbox\">\n".
     "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
     "        </div>\n".
     "    </body>\n".
     "</html>\n";



function GetProjectConfigurationFile($projectNr)
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        
        return -1;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/projects.xml") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
             
        return -2;
    }
    
    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/projects.xml");

    if ($xml == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
    
        return -3;
    }
    
    $i = 1;
    $selectedProject = false;

    foreach ($xml->project as $project)
    {
        $attributes = $project->attributes();
        
        if (isset($attributes) !== true)
        {
            continue;
        }
        
        if (isset($attributes['config']) !== true)
        {
            continue;
        }

        if ($i == (int)$_POST['project_nr'])
        {
            $selectedProject = dom_import_simplexml($attributes['config']);
            
            if ($selectedProject == false)
            {
                $selectedProject = null;
            }
            else
            {
                $selectedProject = $selectedProject->textContent;
            }

            break;
        }

        $i++;
    }
    
    if (is_string($selectedProject) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTINPROJECTLISTFAILED."</span>\n".
             "            </p>\n";

        return -4;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$selectedProject) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
        
        return -5;
    }
    
    return $selectedProject;
}


?>
