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
 * @file $/web/project_edit.php
 * @brief Edit settings of a project.
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
require_once(getLanguageFile("project_edit"));

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
     "            <form action=\"project_download.php\" method=\"post\">\n".
     "              <fieldset>\n".
     "                <input type=\"submit\" value=\"".LANG_DOWNLOADPDF."\"/>\n".
     "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
     "                <input type=\"hidden\" name=\"format\" value=\"pdf\"/>\n".
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
            
            $selectedProjectType = "type1";
            
            if (isset($attributes['type']) === true)
            {
                $selectedProjectType = dom_import_simplexml($attributes['type']);
            
                if ($selectedProjectType != false)
                {
                    $selectedProjectType = $selectedProjectType->textContent;
                }
            }
            
            if ($selectedProjectType !== "type1")
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
        $html2epub1ConfigID = md5(uniqid(rand(), true));
        $htmlResultID = md5(uniqid(rand(), true));

        $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/".$selectedProject,
                                      "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
                                      "<!-- This file was created by project_edit.php of automated_digital_publishing_server, which is free software licensed under the GNU Affero General Public License 3 or any later version (see https://github.com/skreutzer/automated_digital_publishing_server/). -->\n".
                                      "<project>\n".
                                      "  <in>\n".
                                      "    <!-- Only used for metadata. -->\n".
                                      "    <html2epub1 config=\"".$html2epub1ConfigID.".xml\"/>\n".
                                      "  </in>\n".
                                      "  <out>\n".
                                      "    <outFile path=\"".$htmlResultID.".html\"/>\n".
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
        
        // Fallback for legacy projects with no type, default is
        // source_odt.
        if (isset($attributes['type']) === true)
        {
            if ($attributes['type'] !== "source_odt")
            {
                continue;
            }
        }

        echo "              <form action=\"project_edit.php\" method=\"post\">\n".
             "                <fieldset>\n";
             
        if ($i <= 1)
        {
            echo "                  <input type=\"submit\" name=\"upload_up\" value=\"".LANG_UPLOADFILEUPBUTTON."\" disabled=\"disabled\"/>";
        }
        else
        {
            echo "                  <input type=\"submit\" name=\"upload_up\" value=\"".LANG_UPLOADFILEUPBUTTON."\"/>";
        }

        if ($i >= $inputFilesCount)
        {
            echo "<input type=\"submit\" name=\"upload_down\" value=\"".LANG_UPLOADFILEDOWNBUTTON."\" disabled=\"disabled\"/>\n";
        }
        else
        {
            echo "<input type=\"submit\" name=\"upload_down\" value=\"".LANG_UPLOADFILEDOWNBUTTON."\"/>\n";
        }

        echo "                  <input type=\"submit\" name=\"upload_delete\" value=\"".LANG_UPLOADFILEDELETEBUTTON."\" disabled=\"disabled\"/> ".$attributes['display']."\n".
             "                  <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "                  <input type=\"hidden\" name=\"upload_nr\" value=\"".$i."\"/>\n".
             "                </fieldset>\n".
             "              </form>\n";

        $i++;
    }
    
    if ($inputFilesCount > 0)
    {
        // Multiple input files not supported yet.

        echo "              <form action=\"project_upload.php\" method=\"post\">\n".
             "                <fieldset>\n".
             "                  <input type=\"submit\" value=\"".LANG_UPLOADBUTTON."\" disabled=\"disabled\"/>\n".
             "                  <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "                </fieldset>\n".
             "              </form>\n";
    }
    else
    {
        echo "              <form action=\"project_upload.php\" method=\"post\">\n".
             "                <fieldset>\n".
             "                  <input type=\"submit\" value=\"".LANG_UPLOADBUTTON."\"/>\n".
             "                  <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
             "                </fieldset>\n".
             "              </form>\n";
    }
    
    echo "            </div>\n";
}

function GetHtml2epub1ConfigurationFile($projectConfigurationFile)
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
    
        return -3;
    }
    
    if (isset($xml->in->html2epub1) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -3;
    }
    
    $attributes = $xml->in->html2epub1->attributes();
    
    if (isset($attributes) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -3;
    }

    if (isset($attributes['config']) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -4;
    }

    $html2epub1ConfigurationFile = dom_import_simplexml($attributes['config']);
    
    if ($html2epub1ConfigurationFile == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READPROJECTCONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -4;
    }
    else
    {
        $html2epub1ConfigurationFile = $html2epub1ConfigurationFile->textContent;
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile) !== true)
    {
        require_once("./automated_digital_publishing/html2epub/html2epub1/gui/html2epub1_config_create_new/html2epub1_config_create_new_caller.inc.php");
        
        $result = html2epub1_config_create_new_caller(dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile);

        if (file_exists(dirname(__FILE__)."/projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile) !== true)
        {
            echo "            <p>\n".
                 "              <span class=\"error\">".LANG_FINDHTML2EPUB1CONFIGFAILED."</span>\n".
                 "            </p>\n";
            
            return -5;
        }
    }
    
    return $html2epub1ConfigurationFile;
}

function UpdateMetadata($html2epub1ConfigurationFile)
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        
        return -1;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDHTML2EPUB1CONFIGFAILED."</span>\n".
             "            </p>\n";
        
        return -2;
    }
    
    
    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile);

    if ($xml == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READHTML2EPUB1CONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -3;
    }

    if (isset($xml->out->metaData) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READHTML2EPUB1CONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -4;
    }
    
    if (isset($xml->out->metaData->title) === true)
    {
        unset($xml->out->metaData->title);
    }

    if (isset($_POST['title']) === true)
    {
        $xml->out->metaData->addChild("title", htmlspecialchars($_POST['title']));
    }
    
    if (isset($xml->out->metaData->creator) === true)
    {
        unset($xml->out->metaData->creator);
    }

    if (isset($_POST['creator']) === true)
    {
        $xml->out->metaData->addChild("creator", htmlspecialchars($_POST['creator']));
    }
    
    if (isset($xml->out->metaData->subject) === true)
    {
        unset($xml->out->metaData->subject);
    }

    if (isset($_POST['subject']) === true)
    {
        $xml->out->metaData->addChild("subject", htmlspecialchars($_POST['subject']));
    }
    
    if (isset($xml->out->metaData->description) === true)
    {
        unset($xml->out->metaData->description);
    }

    if (isset($_POST['description']) === true)
    {
        $xml->out->metaData->addChild("description", htmlspecialchars($_POST['description']));
    }
    
    if (isset($xml->out->metaData->publisher) === true)
    {
        unset($xml->out->metaData->publisher);
    }

    if (isset($_POST['publisher']) === true)
    {
        $xml->out->metaData->addChild("publisher", htmlspecialchars($_POST['publisher']));
    }
    
    if (isset($xml->out->metaData->contributor) === true)
    {
        unset($xml->out->metaData->contributor);
    }

    if (isset($_POST['contributor']) === true)
    {
        $xml->out->metaData->addChild("contributor", htmlspecialchars($_POST['contributor']));
    }
   
    if (isset($xml->out->metaData->identifier) === true)
    {
        unset($xml->out->metaData->identifier);
    }

    if (isset($_POST['identifier']) === true)
    {
        $xml->out->metaData->addChild("identifier", htmlspecialchars($_POST['identifier']));
    }
    
    if (isset($xml->out->metaData->source) === true)
    {
        unset($xml->out->metaData->source);
    }

    if (isset($_POST['source']) === true)
    {
        $xml->out->metaData->addChild("source", htmlspecialchars($_POST['source']));
    }
    
    if (isset($xml->out->metaData->language) === true)
    {
        unset($xml->out->metaData->language);
    }

    if (isset($_POST['language']) === true)
    {
        $xml->out->metaData->addChild("language", htmlspecialchars($_POST['language']));
    }
    
    if (isset($xml->out->metaData->coverage) === true)
    {
        unset($xml->out->metaData->coverage);
    }

    if (isset($_POST['coverage']) === true)
    {
        $xml->out->metaData->addChild("coverage", htmlspecialchars($_POST['coverage']));
    }
    
    if (isset($xml->out->metaData->rights) === true)
    {
        unset($xml->out->metaData->rights);
    }

    if (isset($_POST['rights']) === true)
    {
        $xml->out->metaData->addChild("rights", htmlspecialchars($_POST['rights']));
    }

    $success = @file_put_contents("./projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile, $xml->asXML());
    
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

function PrintMetadata($html2epub1ConfigurationFile)
{
    if (file_exists("./projects/user_".$_SESSION['user_id']."/") !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDPROJECTSDIRECTORYFAILED."</span>\n".
             "            </p>\n";
        
        return -1;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_FINDHTML2EPUB1CONFIGFAILED."</span>\n".
             "            </p>\n";
        
        return -2;
    }


    $xml = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$html2epub1ConfigurationFile);

    if ($xml == false)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READHTML2EPUB1CONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -3;
    }

    if (isset($xml->out->metaData) !== true)
    {
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READHTML2EPUB1CONFIGURATIONFAILED."</span>\n".
             "            </p>\n";
    
        return -4;
    }
    
    echo "            <form action=\"project_edit.php\" method=\"post\">\n".
         "              <fieldset>\n";
         
    $value = "";
         
    if (isset($xml->out->metaData->title) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->title)->textContent;
    }
    
    echo "                <input name=\"title\" type=\"text\" size=\"20\" maxlength=\"40\" value=\"".$value."\"/> ".LANG_METADATA_TITLE."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->creator) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->creator)->textContent;
    }
    
    echo "                <input name=\"creator\" type=\"text\" size=\"20\" maxlength=\"40\" value=\"".$value."\"/> ".LANG_METADATA_CREATOR."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->subject) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->subject)->textContent;
    }
    
    echo "                <input name=\"subject\" type=\"text\" size=\"20\" maxlength=\"40\" value=\"".$value."\"/> ".LANG_METADATA_SUBJECT."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->description) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->description)->textContent;
    }
    
    echo "                <input name=\"description\" type=\"text\" size=\"20\" maxlength=\"400\" value=\"".$value."\"/> ".LANG_METADATA_DESCRIPTION."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->publisher) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->publisher)->textContent;
    }
    
    echo "                <input name=\"publisher\" type=\"text\" size=\"20\" maxlength=\"40\" value=\"".$value."\"/> ".LANG_METADATA_PUBLISHER."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->contributor) === true)
    {
        foreach ($xml->out->metaData->contributor as $contributor)
        {
            if (strlen($value) > 0)
            {
                $value = $value.", ".dom_import_simplexml($contributor)->textContent;
            }
            else
            {
                $value = dom_import_simplexml($contributor)->textContent;
            }
        }
    }

    echo "                <input name=\"contributor\" type=\"text\" size=\"20\" maxlength=\"400\" value=\"".$value."\"/> ".LANG_METADATA_CONTRIBUTOR."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->identifier) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->identifier)->textContent;
    }
    
    echo "                <input name=\"identifier\" type=\"text\" size=\"20\" maxlength=\"40\" value=\"".$value."\"/> ".LANG_METADATA_IDENTIFIER."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->source) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->source)->textContent;
    }
    
    echo "                <input name=\"source\" type=\"text\" size=\"20\" maxlength=\"40\" value=\"".$value."\"/> ".LANG_METADATA_SOURCE."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->language) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->language)->textContent;
    }
    
    echo "                <input name=\"language\" type=\"text\" size=\"20\" maxlength=\"10\" value=\"".$value."\"/> ".LANG_METADATA_LANGUAGE."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->coverage) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->coverage)->textContent;
    }
    
    echo "                <input name=\"coverage\" type=\"text\" size=\"20\" maxlength=\"100\" value=\"".$value."\"/> ".LANG_METADATA_COVERAGE."<br/>\n";
    $value = "";
    
    if (isset($xml->out->metaData->rights) === true)
    {
        $value = dom_import_simplexml($xml->out->metaData->rights)->textContent;
    }
    
    echo "                <input name=\"rights\" type=\"text\" size=\"20\" maxlength=\"100\" value=\"".$value."\"/> ".LANG_METADATA_RIGHTS."<br/>\n";
    $value = "";
        
    echo "                <input type=\"submit\" name=\"update_metadata\" value=\"".LANG_METADATA_SUBMIT."\"/>\n".
         "                <input type=\"hidden\" name=\"project_nr\" value=\"".$_POST['project_nr']."\"/>\n".
         "              </fieldset>\n".
         "            </form>\n";
}



?>
