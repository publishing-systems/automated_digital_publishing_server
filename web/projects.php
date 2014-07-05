<?php
/* Copyright (C) 2012-2014  Stephan Kreutzer
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
 * @file $/web/projects.php
 * @brief Project management.
 * @author Stephan Kreutzer
 * @since 2014-06-08
 */



session_start();

if (isset($_SESSION['user_id']) !== true)
{
    exit();
}


require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("projects"));

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

$success = CreateProjectListIfNotExisting();

if ($success === 0)
{
    $success = ReadProjectList();
}

if ($success === 0)
{
    echo "            <form action=\"project_new.php\" method=\"post\">\n".
         "              <fieldset>\n".
         "                <input type=\"submit\" value=\"".LANG_PROJECTNEWBUTTON."\"/>\n".
         "              </fieldset>\n".
         "            </form>\n";
}

echo "          </div>\n".
     "        </div>\n".
     "        <div class=\"footerbox\">\n".
     "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
     "        </div>\n".
     "    </body>\n".
     "</html>\n";




function CreateProjectListIfNotExisting()
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        if (@mkdir("./projects/user_".$_SESSION['user_id']."/") !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_CREATEPROJECTSDIRECTORYFAILED."</span>\n".
                 "            </p>\n";
            return -1;
        }
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/projects.xml") !== true)
    {
        $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/projects.xml",
                                      "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
                                      "<!-- This file was created by projects.php of automated_digital_publishing_server, which is free software licensed under the GNU Affero General Public License 3 or any later version (see https://github.com/skreutzer/automated_digital_publishing_server/). -->\n".
                                      "<project-list>\n".
                                      "</project-list>\n");
        
        if ($success === false ||
            $success == 0)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_WRITEPROJECTLISTFAILED."</span>\n".
                 "            </p>\n";
        
            return -2;
        }
    }
    
    return 0;
}

function ReadProjectList()
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/projects.xml") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
    
        return -1;
    }

    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/projects.xml");

    if ($xml == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
    
        return -2;
    }

    $i = 1;

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

        $title = dom_import_simplexml($project);
        
        if ($title == false)
        {
            $title = "";
        }
        else
        {
            $title = $title->textContent;
        }
        
        $type = "type1";
        
        if (isset($attributes['type']) === true)
        {
            $type = $attributes['type'];
        }

        switch ($type)
        {
        case "type2":
            echo "            <form action=\"project_edit_type2.php\" method=\"post\">\n";
            break;
        case "type1":
        default:
            echo "            <form action=\"project_edit.php\" method=\"post\">\n";
            break;
        }

        echo "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_PROJECTEDITBUTTON."\"/> ".htmlspecialchars($title)."\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$i."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
        
        $i++;
    }
    
    return 0;
}

?>
