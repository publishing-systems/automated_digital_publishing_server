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
 * @file $/web/project_download.php
 * @brief Download conversion results of a project.
 * @author Stephan Kreutzer
 * @since 2014-06-09
 */



session_start();

if (isset($_SESSION['user_id']) !== true ||
    isset($_POST['project_nr']) !== true ||
    isset($_POST['format']) !== true)
{
    exit();
}

if (is_numeric($_POST['project_nr']) !== true ||
    is_string($_POST['format']) !== true)
{
    exit();
}

if ($_POST['format'] !== "html" &&
    $_POST['format'] !== "epub")
{
    exit();
}


$success = true;
$projectConfigurationFile = GetProjectConfigurationFile($_POST['project_nr']);

if (is_string($projectConfigurationFile) !== true)
{
    $success = false;
}

if ($success === true)
{
    ProvideDownload($projectConfigurationFile, $_POST['format']);
}

/*
require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("project_download"));

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


if ($success === true)
{
    PrintInputFiles($projectConfigurationFile);
}

$html2epub1ConfigurationFile = null;

if ($success === true)
{
    $html2epub1ConfigurationFile = GetHtml2epub1ConfigurationFile($projectConfigurationFile);

    if (is_string($html2epub1ConfigurationFile) !== true)
    {
        $success = false;
    }
    else
    {
        $success = true;
    }
}

if ($success === true &&
    isset($_POST['update_metadata']) === true)
{
    UpdateMetadata($html2epub1ConfigurationFile);
}

if ($success === true)
{
    PrintMetadata($html2epub1ConfigurationFile);
}

echo "            <form action=\"project_generate.php\" method=\"post\">\n".
     "              <fieldset>\n".
     "                <input type=\"submit\" value=\"".LANG_GENERATEPROJECT."\"/>\n".
     "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
     "              </fieldset>\n".
     "            </form>\n".
     "            <form action=\"project_download.php\" method=\"post\">\n".
     "              <fieldset>\n".
     "                <input type=\"submit\" value=\"".LANG_DOWNLOADHTML."\"/>\n".
     "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
     "                <input type=\"hidden\" name=\"format\" value=\"html\"/>\n".
     "              </fieldset>\n".
     "            </form>\n".
     "            <form action=\"project_download.php\" method=\"post\">\n".
     "              <fieldset>\n".
     "                <input type=\"submit\" value=\"".LANG_DOWNLOADEPUB."\"/>\n".
     "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
     "                <input type=\"hidden\" name=\"format\" value=\"epub\"/>\n".
     "              </fieldset>\n".
     "            </form>\n".
     "            <form action=\"projects.php\" method=\"post\">\n".
     "              <fieldset>\n".
     "                <input type=\"submit\" value=\"".LANG_DONE."\"/>\n".
     "              </fieldset>\n".
     "            </form>\n".
     "          </div>\n".
     "        </div>\n".
     "        <div class=\"footerbox\">\n".
     "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
     "        </div>\n".
     "    </body>\n".
     "</html>\n";

*/

function GetProjectConfigurationFile($projectNr)
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        */
        
        return -1;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/projects.xml") !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
        */
             
        return -2;
    }
    
    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/projects.xml");

    if ($xml == false)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
        */
    
        return -3;
    }
    
    $i = 1;
    $selectedProject = false;

    foreach ($xml->project as $project)
    {
        if (isset($project->attributes()['config']) !== true)
        {
            continue;
        }

        if ($i == (int)$_POST['project_nr'])
        {
            $selectedProject = dom_import_simplexml($project->attributes()['config'])->textContent;
            break;
        }

        $i++;
    }
    
    if (is_string($selectedProject) !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTINPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
        */

        return -4;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$selectedProject) !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
        */
        
        return -5;
    }
    
    return $selectedProject;
}

function ProvideDownload($projectConfigurationFile, $format)
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        */
        
        return -1;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile) !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
        */
        
        return -2;
    }
    
    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile);

    if ($xml == false)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
        */
    
        return -3;
    }

    if (isset($xml->out->outFile['path']) !== true)
    {
        return -4;
    }

    $filePath = "./projects/user_".$_SESSION['user_id']."/".substr($xml->out->outFile['path'], 0, strrpos($xml->out->outFile['path'], '.')).".".$format;

    if (file_exists($filePath) === true)
    {
        if ($format === "html")
        {
            header("Content-type:application/xhtml+xml");
            header("Content-Disposition:attachment;filename=download.html");
        }
        else if ($format === "epub")
        {
            header("Content-type:application/epub+zip");
            header("Content-Disposition:attachment;filename=download.epub");
        }
    
        readfile($filePath);
    }
}



?>
