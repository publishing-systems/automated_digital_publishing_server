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
 * @file $/web/index.php
 * @brief Start page.
 * @author Stephan Kreutzer
 * @since 2012-06-01
 */



if (empty($_SESSION) === true)
{
    @session_start();
}

require_once("./libraries/languagelib.inc.php");

if (isset($_GET['lang']) === true)
{
    $currentLanguage = getDefaultLanguage();
    $languages = getLanguageList();

    if (is_array($languages) === true)
    {
        if (count($languages) > 0)
        {
            if (array_key_exists($_GET['lang'], $languages) === true)
            {
                $currentLanguage = $_GET['lang'];
                $_SESSION['language'] = $currentLanguage;
                unset($_GET['lang']);
            }
        }
    }
}

require_once(getLanguageFile("index"));

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
     "    <body>\n";

if (isset($_POST['name']) !== true ||
    isset($_POST['passwort']) !== true)
{
    require_once("./language_selector.inc.php");
    echo getHTMLLanguageSelector("index.php");

    echo "        <div class=\"mainbox\">\n".
         "          <div class=\"mainbox_header\">\n".
         "            <h1 class=\"mainbox_header_h1\">".LANG_HEADER."</h1>\n".
         "          </div>\n".
         "          <div class=\"mainbox_body\">\n";

    if (isset($_POST['install_done']) == true)
    {
        if (@unlink(dirname(__FILE__)."/install/install.php") === true)
        {
            clearstatcache();
        }
        else
        {
            echo "            <p class=\"error\">\n".
                 "              ".LANG_INSTALLDELETEFAILED."\n".
                 "            </p>\n";
        }
    }

    if (file_exists("./install/install.php") === true &&
        isset($_GET['skipinstall']) != true)
    {
        echo "            <form action=\"install/install.php\" method=\"post\" class=\"installbutton_form\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_INSTALLBUTTON."\"/><br/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }
    else
    {
        echo "            <p>\n".
             "              ".LANG_LOGINDESCRIPTION."\n".
             "            </p>\n".
             "            <form action=\"index.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input name=\"name\" type=\"text\" size=\"20\" maxlength=\"40\"/> ".LANG_NAMEFIELD_CAPTION."<br />\n".
             "                <input name=\"passwort\" type=\"password\" size=\"20\" maxlength=\"40\"/> ".LANG_PASSWORDFIELD_CAPTION."<br />\n".
             "                <input type=\"submit\" value=\"".LANG_SUBMITBUTTON."\"/><br/>\n".
             "              </fieldset>\n".
             "            </form>\n";
    }

    require_once("./license.inc.php");
    echo getHTMLLicenseNotification("license");

    echo "          </div>\n".
         "        </div>\n".
         "        <div class=\"footerbox\">\n".
         "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
         "        </div>\n".
         "    </body>\n".
         "</html>\n".
         "\n";

    exit();
}


require_once("./libraries/database.inc.php");

if (Database::Get()->IsConnected() !== true)
{
    echo "        <div class=\"mainbox\">\n".
         "          <div class=\"mainbox_body\">\n".
         "            <p class=\"error\">\n".
         "              ".LANG_DBCONNECTFAILED."\n".
         "            </p>\n".
         "          </div>\n".
         "        </div>\n".
         "        <div class=\"footerbox\">\n".
         "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
         "        </div>\n".
         "    </body>\n".
         "</html>\n";

    exit();
}


$user = NULL;

$result = Database::Get()->Query("SELECT `id`,\n".
                                 "    `salt`,\n".
                                 "    `password`\n".
                                 "FROM `".Database::Get()->GetPrefix()."users`\n".
                                 "WHERE `name` LIKE ?\n",
                                 array($_POST['name']),
                                 array(Database::TYPE_STRING));

if (is_array($result) !== true)
{
    echo "        <div class=\"mainbox\">\n".
         "          <div class=\"mainbox_body\">\n".
         "            <p class=\"error\">\n".
         "              ".LANG_DBCONNECTFAILED."\n".
         "            </p>\n".
         "          </div>\n".
         "        </div>\n".
         "        <div class=\"footerbox\">\n".
         "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
         "        </div>\n".
         "    </body>\n".
         "</html>\n";

    exit();
}


if (count($result) === 0)
{
    // The user doesn't exist, so insert him.

    require_once("./libraries/user_management.inc.php");

    $id = insertNewUser($_POST['name'], $_POST['passwort']);

    if ($id > 0)
    {
        $user = array("id" => $id);
    }
    else
    {
        echo "        <div class=\"mainbox\">\n".
             "          <div class=\"mainbox_body\">\n".
             "            <p class=\"error\">\n".
             "              ".LANG_DBCONNECTFAILED."\n".
             "            </p>\n".
             "          </div>\n".
             "        </div>\n".
             "        <div class=\"footerbox\">\n".
             "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
             "        </div>\n".
             "    </body>\n".
             "</html>\n";

        exit();
    }
}
else
{
    // The user does already exist, he wants to login.

    if ($result[0]['password'] === hash('sha512', $result[0]['salt'].$_POST['passwort']))
    {
        $user = array("id" => $result[0]['id']);
    }
    else
    {
        /**
         * @todo Security could be improved by not telling that the user
         *     actually exists and just the password was incorrect.
         */

        echo "        <div class=\"mainbox\">\n".
             "          <div class=\"mainbox_body\">\n".
             "            <p class=\"error\">\n".
             "              ".LANG_LOGINFAILED."\n".
             "            </p>\n".
             "            <form action=\"index.php\" method=\"post\">\n".
             "              <fieldset>\n".
             "                <input type=\"submit\" value=\"".LANG_RETRYLOGINBUTTON."\"/><br/>\n".
             "              </fieldset>\n".
             "            </form>\n".
             "          </div>\n".
             "        </div>\n".
             "        <div class=\"footerbox\">\n".
             "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
             "        </div>\n".
             "    </body>\n".
             "</html>\n";

        exit();
    }
}

if (is_array($user) === true)
{
    $_SESSION['user_id'] = $user['id'];
    /**
     * @todo Escape $_POST['name'] for use in session (may find its way
     *     into HTML output and SQL queries)!
     */
    $_SESSION['user_name'] = $_POST['name'];

    echo "        <div class=\"mainbox\">\n".
         "          <div class=\"mainbox_body\">\n".
         "            <p class=\"success\">\n".
         "              ".LANG_LOGINSUCCESS."\n".
         "            </p>\n".
         "            <form action=\"projects.php\" method=\"post\">\n".
         "              <fieldset>\n".
         "                <input type=\"submit\" value=\"".LANG_ENTERBUTTON."\"/><br/>\n".
         "              </fieldset>\n".
         "            </form>\n".
         "          </div>\n".
         "        </div>\n".
         "        <div class=\"footerbox\">\n".
         "          <a href=\"license.php\" class=\"footerbox_link\">".LANG_LICENSE."</a>\n".
         "        </div>\n";
}

echo "    </body>\n".
     "</html>\n";



?>
