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
 * @file $/web/project_generate.php
 * @brief Generates target formats.
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

$step = 1;

if (isset($_POST['step']) === true)
{
    if (is_numeric($_POST['step']) === true)
    {
        $step = (int)$_POST['step'];
    }
    
    if ($step > 3 ||
        $step < 1)
    {
        $step = 1;
    }
}


require_once("./libraries/languagelib.inc.php");
require_once(getLanguageFile("project_generate"));

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

if ($success === true &&
    $step === 1)
{
    if (GenerateHTML($projectConfigurationFile) === 0)
    {
        echo "            <p>\n".
             "              <span class=\"success\">".LANG_GENERATEHTMLSUCCESS."</span>\n".
             "            </p>\n".
             "            <form action=\"project_generate.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_CONTINUE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "                <input type=\"hidden\" name=\"step\" value=\"2\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
    else
    {
        echo "            <form action=\"project_edit.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_LEAVE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    
        $success = false;
    }
}

if ($success === true &&
    $step === 2)
{
    if (GenerateEPUB($projectConfigurationFile) === 0)
    {
        echo "            <p>\n".
             "              <span class=\"success\">".LANG_GENERATEEPUBSUCCESS."</span>\n".
             "            </p>\n".
             "            <form action=\"project_generate.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_CONTINUE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "                <input type=\"hidden\" name=\"step\" value=\"3\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
    else
    {
        echo "            <form action=\"project_edit.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_LEAVE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    
        $success = false;
    }
}

if ($success === true &&
    $step === 3)
{
    if (GeneratePDF($projectConfigurationFile) === 0)
    {
        echo "            <p>\n".
             "              <span class=\"success\">".LANG_GENERATEPDFSUCCESS."</span>\n".
             "            </p>\n".
             "            <form action=\"project_edit.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_CONTINUE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
    else
    {
        echo "            <form action=\"project_edit.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_LEAVE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    
        $success = false;
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

function GenerateHTML($projectConfigurationFile)
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
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
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

    if (isset($xml->in->inFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOODTINPUTFILECONFIGURED."</span>\n".
             "            </p>\n";
    
        return -4;
    }
    
    if (isset($xml->in->inFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOODTINPUTFILEPATHCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -5;
    }

    if (isset($xml->out->outFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFILECONFIGURED."</span>\n".
             "            </p>\n";
    
        return -6;
    }
    
    if (isset($xml->out->outFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFILEPATHCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -7;
    }


    require_once("./automated_digital_publishing/workflows/odt2html2_caller.inc.php");

    $result = odt2html2_caller(dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile, dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/");

    if (is_numeric($result) == true)
    {
        if ($result === -3)
        {
            echo "            <p>\n".
                 "              <span>".LANG_BUSY."</span>\n".
                 "            </p>\n";
             
             return -8;
        }
        else
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERALERROR."</span>\n".
                 "            </p>\n";

            return -9;
        }
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->out->outFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_GENERATEHTMLFAILED."</span>\n".
             "            </p>\n";
    
        return -10;
    }
    
    return 0;
}

function GenerateEPUB($projectConfigurationFile)
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
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
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

    if (isset($xml->out->outFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFILECONFIGURED."</span>\n".
             "            </p>\n";
    
        return -4;
    }
    
    if (isset($xml->out->outFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFILEPATHCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -5;
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->out->outFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFOUND."</span>\n".
             "            </p>\n";
    
        return -6;
    }

    if (isset($xml->in->html2epub1['config']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EPUBCONFIGURATIONNOTCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -7;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->in->html2epub1['config']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EPUBCONFIGURATIONMISSING."</span>\n".
             "            </p>\n";
    
        return -8;
    }
    
    $id = md5(uniqid(rand(), true));

    require_once("./automated_digital_publishing/workflows/html2epub1_caller.inc.php");

    $result = html2epub1_caller("./projects/user_".$_SESSION['user_id']."/".$xml->out->outFile['path'], 
                                "./projects/user_".$_SESSION['user_id']."/".$xml->in->html2epub1['config'],
                                dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/");

    if (is_numeric($result) == true)
    {
        if ($result === -4)
        {
            echo "            <p>\n".
                 "              <span>".LANG_BUSY."</span>\n".
                 "            </p>\n";
             
             return -9;
        }
        else
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERALERROR."</span>\n".
                 "            </p>\n";

            return -10;
        }
    }

    if (file_exists("./automated_digital_publishing/workflows/temp/epub/out.epub") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_GENERATEEPUBFAILED."</span>\n".
             "            </p>\n";
    
        return -10;
    }
    else
    {
        if (copy("./automated_digital_publishing/workflows/temp/epub/out.epub",
                 "./projects/user_".$_SESSION['user_id']."/".substr($xml->out->outFile['path'], 0, strrpos($xml->out->outFile['path'], '.')).".epub") !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERATEEPUBFAILED."</span>\n".
                 "            </p>\n";
        }
    }
    
    return 0;
}


function GeneratePDF($projectConfigurationFile)
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
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
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

    if (isset($xml->out->outFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFILECONFIGURED."</span>\n".
             "            </p>\n";
    
        return -4;
    }
    
    if (isset($xml->out->outFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFILEPATHCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -5;
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->out->outFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOHTMLOUTPUTFOUND."</span>\n".
             "            </p>\n";
    
        return -6;
    }

    $id = md5(uniqid(rand(), true));

    require_once("./automated_digital_publishing/workflows/html2pdf1_caller.inc.php");

    $result = html2pdf1_caller("./projects/user_".$_SESSION['user_id']."/".$xml->out->outFile['path'], 
                               dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/");

    if (is_numeric($result) == true)
    {
        if ($result === -4)
        {
            echo "            <p>\n".
                 "              <span>".LANG_BUSY."</span>\n".
                 "            </p>\n";
             
             return -9;
        }
        else
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERALERROR."</span>\n".
                 "            </p>\n";

            return -10;
        }
    }

    if (file_exists("./automated_digital_publishing/workflows/temp/pdf/output.pdf") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_GENERATEPDFFAILED."</span>\n".
             "            </p>\n";
    
        return -10;
    }
    else
    {
        if (copy("./automated_digital_publishing/workflows/temp/pdf/output.pdf",
                 "./projects/user_".$_SESSION['user_id']."/".substr($xml->out->outFile['path'], 0, strrpos($xml->out->outFile['path'], '.')).".pdf") !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERATEEPUBFAILED."</span>\n".
                 "            </p>\n";
        }
    }
    
    return 0;
}



?>
