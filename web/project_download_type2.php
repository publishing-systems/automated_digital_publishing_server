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
 * @file $/web/project_download_type2.php
 * @brief Download EPUB extraction results.
 * @author Stephan Kreutzer
 * @since 2014-10-04
 */



session_start();

if (isset($_SESSION['user_id']) !== true ||
    isset($_POST['project_nr']) !== true ||
    isset($_POST['file']) !== true)
{
    exit();
}

if (is_numeric($_POST['project_nr']) !== true ||
    is_numeric($_POST['file']) !== true)
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
    ProvideDownload($projectConfigurationFile, $_POST['file']);
}




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

function ProvideDownload($projectConfigurationFile, $file)
{
    if ($file < 0)
    {
        return -1;
    }

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

    if (isset($xml->extraction->extractionDirectory) !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_PROJECTCONFIGURATIONMISSINGEXTRACTIONDIRECTORY."</span>\n".
             "            </p>\n";
        */

        return -4;
    }

    if (isset($xml->extraction->extractionDirectory['path']) !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_PROJECTCONFIGURATIONEXTRACTIONDIRECTORYINCOMPLETE."</span>\n".
             "            </p>\n";
        */

        return -5;
    }

    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']) !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EXTRACTIONDIRECTORYMISSING."</span>\n".
             "            </p>\n";
        */

        return -6;
    }

    if (is_dir("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']) !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EXTRACTIONDIRECTORYMISSING."</span>\n".
             "            </p>\n";
        */

        return -7;
    }
    
    if (file_exists("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/index.xml") !== true)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_EXTRACTIONINDEXXMLRESULTFILEMISSING."</span>\n".
             "            </p>\n";
        */

        return -8;
    }

    $xmlExtractedList = @simplexml_load_file("./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/index.xml");

    if ($xmlExtractedList == false)
    {
        /*
        echo "            <p>\n".
             "              <span class=\"error\">".LANG_READEXTRACTIONINDEXXMLRESULTFILEFAILED."</span>\n".
             "            </p>\n";
        */
    
        return -9;
    }

    $htmlFilesCount = count($xmlExtractedList);

    if ($htmlFilesCount <= 0)
    {
        return 0;
    }
   
    if ($file >= $htmlFilesCount)
    {
        return 1;
    }


    $filePath = "./projects/user_".$_SESSION['user_id']."/".$xml->extraction->extractionDirectory['path']."/".$xmlExtractedList->file[(int)$file]."_transformed.html";

    if (file_exists($filePath) === true)
    {
        header("Content-type:application/xhtml+xml");
        header("Content-Disposition:attachment;filename=page_".($file + 1).".html");

        readfile($filePath);
    }
}



?>
