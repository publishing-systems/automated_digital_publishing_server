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
 * @file $/web/project_edit_type2.php
 * @brief Edit settings of a project based on an EPUB2 source.
 * @author Stephan Kreutzer
 * @since 2014-07-05
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
require_once(getLanguageFile("project_edit_type2"));

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

$success = true;
$projectConfigurationFile = GetProjectConfigurationFile($_POST['project_nr']);

if (is_string($projectConfigurationFile) !== true)
{
    $success = false;
}

if ($success === true)
{
    PrintInputFiles($projectConfigurationFile);
}

echo "            <form action=\"projects.php\" method=\"post\">\n".
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
    $selectedProjectType = "";

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
            
            $selectedProjectType = "";
            
            if (isset($attributes['type']) === true)
            {
                $selectedProjectType = dom_import_simplexml($attributes['type']);
            
                if ($selectedProjectType == false)
                {
                    $selectedProjectType = "";
                }
                else
                {
                    $selectedProjectType = $selectedProjectType->textContent;
                }
            }
            
            if ($selectedProjectType !== "type2")
            {
                echo "            <p>\n".
                     "              <span class=\"error\">".LANG_WRONGPROJECTTYPE."</span>\n".
                     "            </p>\n";
                     
                return -6;
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
        $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/".$selectedProject,
                                      "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
                                      "<!-- This file was created by project_edit_type2.php of automated_digital_publishing_server, which is free software licensed under the GNU Affero General Public License 3 or any later version (see https://github.com/skreutzer/automated_digital_publishing_server/). -->\n".
                                      "<project>\n".
                                      "  <in>\n".
                                      "  </in>\n".
                                      "  <out>\n".
                                      "  </out>\n".
                                      "</project>\n");

        if ($success === false ||
            $success == 0)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_WRITEPROJECTCONFIGURATIONFAILED."</span>\n".
                 "            </p>\n";
            
            return -5;
        }
    }
    
    return $selectedProject;
}

function PrintInputFiles($projectConfigurationFile)
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        
        return -1;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
        
        return -2;
    }
    
    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile);

    if ($xml == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -3;
    }

    if (isset($xml->in) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -4;
    }

    $inputFilesCount = 0;

    foreach ($xml->in->children() as $child)
    {
        if ($child->getName() != "inFile")
        {
            continue;
        }
        
        $attributes = $child->attributes();
        
        if (isset($attributes) !== true)
        {
            continue;
        }
        
        if (isset($attributes['path']) !== true ||
            isset($attributes['display']) !== true)
        {
            continue;
        }

        $inputFilesCount++;
    }


    echo "            <div>\n".
         "              <span>".LANG_UPLOADCAPTION."</span>\n";

    $i = 1;

    foreach ($xml->in->children() as $child)
    {
        if ($child->getName() != "inFile")
        {
            continue;
        }
        
        $attributes = $child->attributes();
        
        if (isset($attributes) !== true)
        {
            continue;
        }
        
        if (isset($attributes['path']) !== true ||
            isset($attributes['display']) !== true)
        {
            continue;
        }

        echo "              <form action=\"project_edit_type2.php\" method=\"post\">\n".
             "                <fieldset>\n".
             "                  <input type=\"submit\" name=\"upload_delete\" value=\"".LANG_UPLOADFILEDELETEBUTTON."\" disabled=\"disabled\"/> ".$attributes['display']."\n".
             "                  <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "                  <input type=\"hidden\" name=\"upload_nr\" value=\"".$i."\"/>\n".
             "                </fieldset>\n".
             "              </form>\n";

        $i++;
    }
    
    if ($inputFilesCount <= 0)
    {
        echo "              <form action=\"project_upload_type2.php\" method=\"post\">\n".
             "                <fieldset>\n".
             "                  <input type=\"submit\" value=\"".LANG_UPLOADBUTTON."\"/>\n".
             "                  <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "                </fieldset>\n".
             "              </form>\n";
    }
    
    echo "            </div>\n";
}



?>
