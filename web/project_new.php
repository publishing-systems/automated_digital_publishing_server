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
 * @file $/web/project_new.php
 * @brief Create a new project.
 * @author Stephan Kreutzer
 * @since 2014-06-08
 */



session_start();

if (isset($_SESSION['user_id']) !== true)
{
    exit();
}


require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("project_new"));

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
     
if (isset($_POST['title']) == false ||
    isset($_POST['type']) == false)
{
    echo "            <form action=\"project_new.php\" method=\"post\">\n".
         "              <fieldset>\n".
         "                <input name=\"title\" type=\"text\" size=\"20\" maxlength=\"40\"/> ".LANG_PROJECTTITLECAPTION."<br/>\n".
         "                <select name=\"type\" size=\"1\">\n".
         "                  <option value=\"1\">".LANG_PROJECTTYPE1."</option>\n".
         "                  <option value=\"2\">".LANG_PROJECTTYPE2."</option>\n".
         "                </select> ".LANG_PROJECTTYPECAPTION."<br/>\n".
         "                <input type=\"submit\" value=\"".LANG_PROJECTNEWBUTTON."\"/>\n".
         "              </fieldset>\n".
         "            </form>\n";

}
else
{
    $success = true;

    if (AddNewProject($_POST['title'], $_POST['type']) !== 0)
    {
        $success = false;
    }

    if ($success === true)
    {
        echo "            <p>\n".
             "              <span class=\"success\">".LANG_PROJECTCREATEDSUCCESSFULLY."</span>\n".
             "            </p>\n".
             "            <form action=\"projects.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_CONTINUE."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
}

echo "          </div>\n".
     "        </div>\n".
     "        <div class=\"footerbox\">\n".
     "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
     "        </div>\n".
     "    </body>\n".
     "</html>\n";



function AddNewProject($title, $type)
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

    $id = md5(uniqid(rand(), true));

    $project = $xml->addChild("project", htmlspecialchars($title));
    $project->addAttribute("config", $id.".xml");
    
    if ($_POST['type'] == "1")
    {
        $project->addAttribute("type", "type1");
    }
    else
    {
        $project->addAttribute("type", "type2");
    }
    
    $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/projects.xml", $xml->asXML());
    
    if ($success === false ||
        $success == 0)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_WRITETOPROJECTLISTFAILED."</span>\n".
             "            </p>\n";
        
        return -4;
    }
    
    return 0;
}



?>
