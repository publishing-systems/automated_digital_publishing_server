<?php
/* Copyright (C) 2013  Stephan Kreutzer
 *
 * This file is part of RPGBGPrototype.
 *
 * RPGBGPrototype is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License version 3 or any later version,
 * as published by the Free Software Foundation.
 *
 * RPGBGPrototype is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License 3 for more details.
 *
 * You should have received a copy of the GNU Affero General Public License 3
 * along with RPGBGPrototype. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * @file $/web/language_selector.php
 * @brief Form to change the language.
 * @author Stephan Kreutzer
 * @since 2013-09-14
 */



require_once(dirname(__FILE__)."/libraries/languagelib.inc.php");



function getHTMLLanguageSelector($targetPage,
                                 $cssClassLanguageselector = "languageselector",
                                 $cssClassLanguageselectorText = "languageselector_text",
                                 $cssClassLanguageselectorForm = "languageselector_form",
                                 $cssClassLanguageselectorFormFieldset = "languageselector_form_fieldset",
                                 $cssClassLanguageselectorFormSelect = "languageselector_form_select",
                                 $cssClassLanguageselectorFormSubmitbutton = "languageselector_form_submitbutton")
{
    $html = "";
    $currentLanguage = getDefaultLanguage();
    $languages = getLanguageList();

    if (isset($_POST['language']) === true &&
        is_array($languages) === true)
    {
        // Select another language.

        if (count($languages) > 0)
        {
            if (array_key_exists($_POST['language'], $languages) === true)
            {
                $currentLanguage = $_POST['language'];
                $_SESSION['language'] = $currentLanguage;
                unset($_POST['language']);
            }
        }
    }

    require_once(getLanguageFile("language_selector", dirname(__FILE__)));

    if (is_array($languages) === true)
    {
        if (count($languages) > 0)
        {
            if (isset($_SESSION['language']) === true)
            {
                $currentLanguage = $_SESSION['language'];
            }
            else
            {
                $currentLanguage = getDefaultLanguage();
            }

            $html .= "<div class=\"".$cssClassLanguageselector."\">\n".
                     "  <span class=\"".$cssClassLanguageselectorText."\">".LANG_LANGUAGESELECTOR_DESCRIPTION."</span><br/>\n".
                     "  <form action=\"".$targetPage."\" method=\"post\" class=\"".$cssClassLanguageselectorForm."\">\n".
                     "    <fieldset class=\"".$cssClassLanguageselectorFormFieldset."\">\n".
                     "      <select name=\"language\" size=\"1\" class=\"".$cssClassLanguageselectorFormSelect."\">\n";

            foreach ($languages as $language => $displayName)
            {
                if ($language == $currentLanguage)
                {
                    // Doesn't work on Mozilla Firefox, but is XHTML standard. Standard
                    // wins over browser implementation.
                    $html .= "        <option value=\"".$language."\" selected=\"selected\">".$displayName."</option>\n";
                }
                else
                {
                    $html .= "        <option value=\"".$language."\">".$displayName."</option>\n";
                }
            }

            $html .= "      </select>\n".
                     "      <input type=\"submit\" value=\"".LANG_LANGUAGESELECTOR_SUBMITBUTTON."\" class=\"".$cssClassLanguageselectorFormSubmitbutton."\"/>\n".
                     "    </fieldset>\n".
                     "  </form>\n".
                     "</div>\n";
        }
    }

    return $html;
}



?>
