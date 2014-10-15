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
 * @file $/web/project_generate_type2.php
 * @brief Handles EPUB extraction and transmission to WordPress.
 * @author Stephan Kreutzer
 * @since 2014-09-20
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
require_once(getLanguageFile("project_generate_type2"));

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
    isset($_POST['extract']) === true)
{
    if (ExtractEPUB($projectConfigurationFile) === 0)
    {
        echo "            <p>\n".
             "              <span class=\"success\">".LANG_EXTRACTEPUBSUCCESS."</span>\n".
             "            </p>\n".
             "            <form action=\"project_edit_type2.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_CONTINUE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
    else
    {
        echo "            <form action=\"project_edit_type2.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_LEAVE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    
        $success = false;
    }
}
else if ($success === true &&
         isset($_POST['prepare']) === true)
{
    if (PrepareHTML($projectConfigurationFile) === 0)
    {
        echo "            <p>\n".
             "              <span class=\"success\">".LANG_PREPAREHTMLSUCCESS."</span>\n".
             "            </p>\n".
             "            <form action=\"project_edit_type2.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_CONTINUE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
    else
    {
        echo "            <form action=\"project_edit_type2.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_LEAVE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    
        $success = false;
    }
}
else if ($success === true &&
         isset($_POST['publish']) === true &&
         isset($_POST['file']) === true)
{
    if (PublishHTML($projectConfigurationFile, $_POST['file']) === 0)
    {
        echo "            <form action=\"project_edit_type2.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_LEAVE."\"/>\n".
             "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
    else
    {
        echo "            <form action=\"project_edit_type2.php\" method=\"post\">\n".
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

function ExtractEPUB($projectConfigurationFile)
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
             "              <span class=\"error\">".LANG_NOEPUBINPUTFILECONFIGURED."</span>\n".
             "            </p>\n";
    
        return -4;
    }

    if (isset($xml->in->inFile['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOEPUBINPUTFILEPATHCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -5;
    }

    if (isset($xml->extraction->extractionDirectory) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOEXTRACTIONDIRECTORYCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -6;
    }

    if (isset($xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOEXTRACTIONDIRECTORYPATHCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -7;
    }

    $id = md5(uniqid(rand(), true));

    require_once("./automated_digital_publishing/epub2html/epub2html1/workflows/epub2html1_config_create_new1/epub2html1_config_create_new1_caller.inc.php");

    $result = epub2html1_config_create_new1_caller(dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/".$id.".xml");

    if (strpos($result, $id.".xml' created.") === false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_CREATINGEPUB2HTML1CONFIGURATIONFAILED."</span>\n".
             "            </p>\n";

        return -8;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$id.".xml") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_CREATINGEPUB2HTML1CONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -9;
    }
    
    {
        /**
         * @todo: Check, if already existing.
         */

        $epub2html1Configuration = $xml->extraction->addChild("epub2html1ConfigurationFile");
        $epub2html1Configuration->addAttribute("path", $id.".xml");


        $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile, $xml->asXML());
        
        if ($success === false ||
            $success == 0)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_WRITEPROJECTCONFIGURATIONFAILED."</span>\n".
                 "            </p>\n";

            return -10;
        }
    }

    {
        $epub2html1ConfigurationFile = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$id.".xml");

        if ($epub2html1ConfigurationFile == false)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_READEXTRACTIONCONFIGURATIONFAILED."</span>\n".
                 "            </p>\n";
        
            return -11;
        }

        if (isset($epub2html1ConfigurationFile->in->inFile) !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_NOEPUBINPUTFILECONFIGUREDINEXTRACTIONCONFIGURATION."</span>\n".
                 "            </p>\n";
        
            return -12;
        }
        
        if (isset($epub2html1ConfigurationFile->out->outDirectory) !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_NOEXTRACTIONDIRECTORYEXTRACTIONCONFIGURATION."</span>\n".
                 "            </p>\n";
        
            return -13;
        }

        $epub2html1ConfigurationFile->in->inFile = $xml->in->inFile['path'];
        $epub2html1ConfigurationFile->out->outDirectory = $xml->extraction->extractionDirectory['path'];
    
        $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/".$id.".xml", $epub2html1ConfigurationFile->asXML());
        
        if ($success === false ||
            $success == 0)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_WRITEEXTRACTIONCONFIGURATIONFAILED."</span>\n".
                 "            </p>\n";

            return -14;
        }
    }

    if (mkdir("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_CREATINGEXTRACTIONDIRECTORYFAILED."</span>\n".
             "            </p>\n";

        return -15;
    }
    
    require_once("./automated_digital_publishing/epub2html/epub2html1/epub2html1_caller.inc.php");

    $result = epub2html1_caller(dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/".$id.".xml",
                                dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/");

    if (is_numeric($result) == true)
    {
        if ($result === -4)
        {
            echo "            <p>\n".
                 "              <span>".LANG_BUSY."</span>\n".
                 "            </p>\n";
             
             return -16;
        }
        else
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERALERROR."</span>\n".
                 "            </p>\n";

            return -17;
        }
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/index.xml") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EXTRACTIONNOINDEXXMLRESULTFILE."</span>\n".
             "            </p>\n";

        return -18;
    }

    return 0;
}

function PrepareHTML($projectConfigurationFile)
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

    if (isset($xml->extraction->epub2html1ConfigurationFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";
    
        return -4;
    }

    if (isset($xml->extraction->extractionDirectory) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_PROJECTCONFIGURATIONMISSINGEXTRACTIONDIRECTORY."</span>\n".
             "            </p>\n";

        return -5;
    }

    if (isset($xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_PROJECTCONFIGURATIONEXTRACTIONDIRECTORYINCOMPLETE."</span>\n".
             "            </p>\n";

        return -6;
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";

        return -7;
    }

    if (is_dir("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";

        return -8;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/index.xml") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";

        return -9;
    }

    $xmlExtractedList = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/index.xml");

    if ($xmlExtractedList == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READEXTRACTIONINDEXXMLRESULTFILEFAILED."</span>\n".
             "            </p>\n";
    
        return -10;
    }

    $htmlFilesCount = count($xmlExtractedList);

    if ($htmlFilesCount <= 0)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EXTRACTEDEPUBEMPTY."</span>\n".
             "            </p>\n";
    
        return 0;
    }

    /**
     * @todo Put this into a separate Java program or XSLT?
     */

    $transformationConfiguration = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
                                   "<!-- This file was created by project_generate_type2.php of automated_digital_publishing_server, which is free software licensed under the GNU Affero General Public License 3 or any later version (see https://github.com/publishing-systems/automated_digital_publishing/). -->\n".
                                   "<multitransform1-input-files>\n";

    for ($i = 0; $i < $htmlFilesCount; $i++)
    {
        $transformationConfiguration .= "  <input-file source=\"".$xmlExtractedList->file[$i]."\" destination=\"".$xmlExtractedList->file[$i]."_transformed.html\"/>\n";
    }

    $transformationConfiguration .= "</multitransform1-input-files>\n";

    $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/transformation_list.xml", $transformationConfiguration);
    
    if ($success === false ||
        $success == 0)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_WRITETRANSFORMATIONCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";

        return -11;
    }

    require_once("./automated_digital_publishing/xsltransformator/xsltransformator1/workflows/multitransform1/multitransform1_caller.inc.php");

    $result = multitransform1_caller(dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/transformation_list.xml",
                                     dirname(__FILE__)."/automated_digital_publishing/workflows/epub2wordpress/epub2wordpress1/html2wordpress1.xsl",
                                     dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/");

    if (is_numeric($result) == true)
    {
        if ($result === -5)
        {
            echo "            <p>\n".
                 "              <span>".LANG_BUSY."</span>\n".
                 "            </p>\n";
             
             return -12;
        }
        else
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERALERROR."</span>\n".
                 "            </p>\n";

            return -13;
        }
    }

    return 0;
}

function PublishHTML($projectConfigurationFile, $fileNumber)
{
    if (is_numeric($fileNumber) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FILENUMBERNOTANUMBER."</span>\n".
             "            </p>\n";
        
        return -1;
    }
    
    $fileNumber = (int)$fileNumber;
    
    if (file_exists("./projects/jobfile.xml") !== true)
    {
        echo "            <p>\n".
             "              ".LANG_HTMLTOWORDPRESSFEATURENOTCONFIGURED."\n".
             "            </p>\n";
             
        return 1;
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        
        return -2;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
        
        return -3;
    }

    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$projectConfigurationFile);

    if ($xml == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -4;
    }

    if (isset($xml->extraction->epub2html1ConfigurationFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";
    
        return -5;
    }

    if (isset($xml->extraction->extractionDirectory) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_PROJECTCONFIGURATIONMISSINGEXTRACTIONDIRECTORY."</span>\n".
             "            </p>\n";

        return -6;
    }

    if (isset($xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_PROJECTCONFIGURATIONEXTRACTIONDIRECTORYINCOMPLETE."</span>\n".
             "            </p>\n";

        return -7;
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";

        return -8;
    }

    if (is_dir("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";

        return -9;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/index.xml") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_NOTEXTRACTED."</span>\n".
             "            </p>\n";

        return -10;
    }

    $xmlExtractedList = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/index.xml");

    if ($xmlExtractedList == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READEXTRACTIONINDEXXMLRESULTFILEFAILED."</span>\n".
             "            </p>\n";
    
        return -11;
    }

    $htmlFilesCount = count($xmlExtractedList);

    if ($htmlFilesCount <= 0)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EXTRACTEDEPUBEMPTY."</span>\n".
             "            </p>\n";
    
        return 0;
    }
    
    if ($fileNumber >= $htmlFilesCount)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FILENUMBERFILEDOESNTEXIST."</span>\n".
             "            </p>\n";
    
        return -12;
    }
    
    $jobfile = @simplexml_load_file("./projects/jobfile.xml");
    
    if ($jobfile == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_HTMLTOWORDPRESSFEATURENOTCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -13;
    }
    
    $settingInputHTMLFileName = "input-html-file";

    if (isset($jobfile->$settingInputHTMLFileName) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_HTMLTOWORDPRESSFEATURENOTCONFIGURED."</span>\n".
             "            </p>\n";
    
        return -14;
    }

    $jobfile->$settingInputHTMLFileName = dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/".$xmlExtractedList->file[$fileNumber]."_transformed.html";

    if (file_exists($jobfile->$settingInputHTMLFileName) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FILENUMBERFILEDOESNTEXIST."</span>\n".
             "            </p>\n";
    
        return -15;
    }

    $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/jobfile.xml", $jobfile->asXML());
    
    if ($success === false ||
        $success == 0)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_WRITEJOBFILEFAILED."</span>\n".
             "            </p>\n";

        return -16;
    }

    require_once("./automated_digital_publishing/html2wordpress/html2wordpress1/html2wordpress1_caller.inc.php");

    $result = html2wordpress1_caller(dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/jobfile.xml",
                                     dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/");

    if (is_numeric($result) == true)
    {
        if ($result === -4)
        {
            echo "            <p>\n".
                 "              <span>".LANG_BUSY."</span>\n".
                 "            </p>\n";
             
             return -17;
        }
        else
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_GENERALERROR."</span>\n".
                 "            </p>\n";

            return -18;
        }
    }

    $resultXMLPos = strpos($result, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>");
    
    if ($resultXMLPos <= 0)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_RESULTINCOMPLETE."</span>\n".
             "            </p>\n";

        return -19;
    }
    
    $resultXML = substr($result, $resultXMLPos);
    $resultXML = @simplexml_load_string($resultXML);
    
    if ($resultXML == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READRESULTXMLPROBLEM."</span>\n".
             "            </p>\n";
    
        return -20;
    }
    
    if (isset($resultXML->params->param[0]->value->string) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_RESULTXMLINCOMPLETE."</span>\n".
             "            </p>\n";

        return -21;
    }
    
    $postID = dom_import_simplexml($resultXML->params->param[0]->value->string);
    
    if ($postID == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READRESULTXMLVALUEERROR."</span>\n".
             "            </p>\n";

        return -22;
    }
    else
    {
        $postID = $postID->textContent;
    }
    
    if (is_numeric($postID) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_POSTIDISNTNUMERIC."</span>\n".
             "            </p>\n";

        return -23;
    }
    
    echo "            <p>\n".
         "              <span class=\"success\">".LANG_HTMLTOWORDPRESSUPLOADSUCCESS." ".$postID."</span>\n".
         "            </p>\n";
    
    return 0;
}



?>
