<?php
/* Copyright (C) 2013-2014  Stephan Kreutzer
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
 * @file $/web/libraries/languagelib.inc.php
 * @author Stephan Kreutzer
 * @since 2013-09-14
 */



function getLanguageFile($caller, $baseDirectory = ".")
{
    if (empty($_SESSION) === true)
    {
        @session_start();
    }

    $language = getDefaultLanguage();
    $languages = getLanguageList();

    if (isset($_POST['language']) === true &&
        is_array($languages) === true)
    {
        // Select another language.

        if (count($languages) > 0)
        {
            if (array_key_exists($_POST['language'], $languages) === true)
            {
                $language = $_POST['language'];
                $_SESSION['language'] = $language;
                unset($_POST['language']);
            }
        }
    }

    if (isset($_SESSION['language']) === true)
    {
        $language = $_SESSION['language'];
    }

    if (is_string($baseDirectory) === true)
    {
        if (is_dir($baseDirectory) === true)
        {
            $last = substr($baseDirectory, -1);

            if ($last == "/" ||
                $last == "\\")
            {
                $last = substr_replace($last, "", -1);
            }
        }
        else
        {
            $baseDirectory = ".";
        }
    }
    else
    {
        $baseDirectory = ".";
    }

    // If no $baseDirectory is supplied, look relatively to the caller's directory.
    $filePath = $baseDirectory."/lang/".$language."/".$caller.".lang.php";

    if (file_exists($filePath) === true)
    {
        return $filePath;
    }
    else
    {
        $language = getDefaultLanguage();
    }

    // If no $baseDirectory is supplied, look relatively to the caller's directory.
    $filePath = $baseDirectory."/lang/".$language."/".$caller.".lang.php";

    if (file_exists($filePath) === true)
    {
        return $filePath;
    }
    else
    {
        $missingFilePath = dirname($_SERVER['PHP_SELF']);
        $missingFilePath = $missingFilePath."/lang/".$language."/".$caller.".lang.php";

        echo "The language file \$".$missingFilePath." for the default language \"".
             getDefaultLanguage()."\" is missing. This shouldn't have happened.\n";
        exit();
    }
}

function getLanguageList()
{
    return array("de" => "Deutsch",
                 "en" => "English");
}

function getDefaultLanguage()
{
    return "en";
}


?>
