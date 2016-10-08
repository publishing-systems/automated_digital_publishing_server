<?php
/* Copyright (C) 2013-2016  Christian Huke, Stephan Kreutzer
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
 * @file $/web/install/install.php
 * @brief Installation routine to set up the system.
 * @author Christian Huke, Stephan Kreutzer
 * @since 2013-09-13
 */



require_once("../libraries/languagelib.inc.php");
require_once(getLanguageFile("install"));



echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
     "<!DOCTYPE html\n".
     "    PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n".
     "    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n".
     "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n".
     "  <head>\n".
     "    <title>".LANG_PAGETITLE."</title>\n".
     "    <link rel=\"stylesheet\" type=\"text/css\" href=\"../mainstyle.css\"/>\n".
     "    <link rel=\"stylesheet\" type=\"text/css\" href=\"install.css\"/>\n".
     "    <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=UTF-8\"/>\n".
     "  </head>\n".
     "  <body>\n";


$step = 0;

if (isset($_POST['step']) === true)
{
    if (is_numeric($_POST['step']) === true)
    {
        $step = (int)$_POST['step'];

/*
        if ($step == 4 &&
            isset($_POST['retry']) === true)
        {
            // Special handling for step 3 (retry other database connection
            // settings after one connection was already established successfully).
            $step = 3;
        }
*/

        if ($step == 5 &&
            isset($_POST['init']) === true)
        {
            // Special handling for step 4 (redo database initialization after
            // initialization was already completed successfully).
            $step = 4;
        }
    }
}

if (isset($_GET['stepjump']) === true)
{
    if (is_numeric($_GET['stepjump']) === true)
    {
        $step = (int)$_GET['stepjump'];
    }
}


if ($step == 0)
{
    // Language selection only for the first step.
    require_once("../language_selector.inc.php");
    echo getHTMLLanguageSelector("install.php");

    echo "    <div class=\"mainbox\">\n".
         "      <div class=\"mainbox_header\">\n".
         "        <h1 class=\"mainbox_header_h1\">".LANG_STEP0_HEADER."</h1>\n".
         "      </div>\n".
         "      <div class=\"mainbox_body\">\n".
         "        <p>\n".
         "          ".LANG_STEP0_INTROTEXT."\n".
         "        </p>\n".
         "        <div>\n".
         "          <form action=\"install.php\" method=\"post\">\n".
         "            <fieldset>\n".
         "              <input type=\"hidden\" name=\"step\" value=\"1\"/>\n".
         "              <input type=\"submit\" value=\"".LANG_STEP0_PROCEEDTEXT."\" class=\"mainbox_proceed\"/>\n".
         "            </fieldset>\n".
         "          </form>\n".
         "        </div>\n".
         "      </div>\n".
         "    </div>\n";
}
else if ($step == 1)
{
    echo "    <div class=\"mainbox\">\n".
         "      <div class=\"mainbox_header\">\n".
         "        <h1 class=\"mainbox_header_h1\">".LANG_STEP1_HEADER."</h1>\n".
         "      </div>\n".
         "      <div class=\"mainbox_body\">\n";

    require_once("../license.inc.php");
    echo getHTMLLicenseNotification("license");
    echo "<hr/>\n";
    echo getHTMLLicenseFull("license");

    echo "        <div>\n".
         "          <form action=\"install.php\" method=\"post\">\n".
         "            <fieldset>\n".
         "              <input type=\"hidden\" name=\"step\" value=\"2\"/>\n".
         "              <input type=\"submit\" value=\"".LANG_STEP1_PROCEEDTEXT."\" class=\"mainbox_proceed\"/>\n".
         "            </fieldset>\n".
         "          </form>\n".
         "        </div>\n".
         "      </div>\n".
         "    </div>\n";
}
else if ($step == 2)
{
    echo "    <div class=\"mainbox\">\n".
         "      <div class=\"mainbox_header\">\n".
         "        <h1 class=\"mainbox_header_h1\">".LANG_STEP2_HEADER."</h1>\n".
         "      </div>\n".
         "      <div class=\"mainbox_body\">\n";

    if (ini_get('safe_mode') == true)
    {
        echo "        <p>\n".
             "          <span class=\"error\">".LANG_STEP2_SAFEMODEON."</span>\n".
             "        </p>\n";
    }
    else
    {
        echo "        <p>\n".
             "          ".LANG_STEP2_JAVAVERSIONCHECK_PRE."\n".
             "        </p>\n".
             "        <pre>\n";

        $javaVersion = shell_exec("java -version 2>&1");
        print_r($javaVersion, false);

        echo "        </pre>\n".
             "        <p>\n".
             "          ".LANG_STEP2_JAVAVERSIONCHECK_POST."\n".
             "        </p>\n";

        $filesFound = true;

        if (file_exists("../automated_digital_publishing/workflows/odt2html1.class") != true)
        {
            echo "        <p>\n".
                 "          <span class=\"error\">".LANG_STEP2_ADPMISSING."</span>\n".
                 "        </p>\n";
                 
            $filesFound = false;
        }
        
        if (file_exists("../automated_digital_publishing/html_flat2hierarchical/html_flat2hierarchical1/entities/xhtml1-strict.dtd") != true)
        {
            echo "        <p>\n".
                 "          <span class=\"error\">".LANG_STEP2_ADPNOSETUP."</span>\n".
                 "        </p>\n";
                 
            $filesFound = false;
        }
        
        if ($filesFound === true)
        {
            echo "        <div>\n".
                 "          <form action=\"install.php\" method=\"post\">\n".
                 "            <fieldset>\n".
                 "              <input type=\"hidden\" name=\"step\" value=\"3\"/>\n".
                 "              <input type=\"submit\" value=\"".LANG_STEP2_CONTINUE."\" class=\"mainbox_proceed\"/>\n".
                 "            </fieldset>\n".
                 "          </form>\n".
                 "        </div>\n";
        }
        else
        {
            echo "        <div>\n".
                 "          <form action=\"install.php\" method=\"post\">\n".
                 "            <fieldset>\n".
                 "              <input type=\"hidden\" name=\"step\" value=\"2\"/>\n".
                 "              <input type=\"submit\" value=\"".LANG_STEP2_RETRY."\" class=\"mainbox_proceed\"/>\n".
                 "            </fieldset>\n".
                 "          </form>\n".
                 "        </div>\n";
        }
    }

    echo "      </div>\n".
         "    </div>\n";

}
else if ($step == 3)
{
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "adps";
    $prefix = "";

    if (isset($_POST['host']) === true)
    {
        $host = $_POST['host'];
    }

    if (isset($_POST['username']) === true)
    {
        $username = $_POST['username'];
    }

    if (isset($_POST['password']) === true)
    {
        $password = $_POST['password'];
    }

    if (isset($_POST['database']) === true)
    {
        $database = $_POST['database'];
    }

    if (isset($_POST['prefix']) === true)
    {
        $prefix = $_POST['prefix'];
    }

    echo "    <div class=\"mainbox\">\n".
         "      <div class=\"mainbox_header\">\n".
         "        <h1 class=\"mainbox_header_h1\">".LANG_STEP3_HEADER."</h1>\n".
         "      </div>\n".
         "      <div class=\"mainbox_body\">\n".
         "        <p>\n".
         "          ".LANG_STEP3_REQUIREMENTS."\n".
         "        </p>\n";

    if (file_exists("../libraries/database_connect.inc.php") !== true)
    {
        $file = @fopen("../libraries/database_connect.inc.php", "w");

        if ($file != false)
        {
            @fclose($file);
        }
        else
        {
            echo "        <p>\n".
                 "          <span class=\"error\">".LANG_STEP3_DATABASECONNECTFILECREATEFAILED."</span>\n".
                 "        </p>\n";
        }
    }

    if (is_writable("../libraries/database_connect.inc.php") === true)
    {
        echo "        <p>\n".
             "          <span class=\"success\">".LANG_STEP3_DATABASECONNECTFILEISWRITABLE."</span>\n".
             "        </p>\n";

        $php_code = "<?php\n".
                    "// This file was automatically generated by the installation routine.\n".
                    "\n".
                    "\$pdo = false;\n".
                    "\$db_table_prefix = \"$prefix\"; // Prefix for database tables.\n".
                    "\$exceptionConnectFailure = NULL;\n".
                    "\n".
                    "\n".
                    "try\n".
                    "{\n".
                    "    \$pdo = @new PDO('mysql:host=".$host.";dbname=".$database.";charset=utf8', \"".$username."\", \"".$password."\");\n".
                    "}\n".
                    "catch (PDOException \$ex)\n".
                    "{\n".
                    "    \$pdo = false;\n".
                    "    \$exceptionConnectFailure = \$ex;\n".
                    "}\n".
                    "\n".
                    "?>\n";

        $file = @fopen("../libraries/database_connect.inc.php", "wb");

        if ($file != false)
        {
            if (@fwrite($file, $php_code) != false)
            {
                echo "        <p>\n".
                     "          <span class=\"success\">".LANG_STEP3_DATABASECONNECTFILEWRITESUCCEEDED."</span>\n".
                     "        </p>\n";
            }
            else
            {
                echo "        <p>\n".
                     "          <span class=\"error\">".LANG_STEP3_DATABASECONNECTFILEWRITEFAILED."</span>\n".
                     "        </p>\n";
            }

            @fclose($file);
        }
        else
        {
            echo "        <p>\n".
                 "          <span class=\"error\">".LANG_STEP3_DATABASECONNECTFILEWRITABLEOPENFAILED."</span>\n".
                 "        </p>\n";
        }
    }
    else
    {
        echo "        <p>\n".
             "          <span class=\"error\">".LANG_STEP3_DATABASECONNECTFILEISNTWRITABLE."</span>\n".
             "        </p>\n";
    }


    $successConnect = false;

    clearstatcache();

    if (file_exists("../libraries/database_connect.inc.php") === true)
    {
        if (is_readable("../libraries/database_connect.inc.php") === true)
        {
            echo "        <p>\n".
                 "          <span class=\"success\">".LANG_STEP3_DATABASECONNECTFILEISREADABLE."</span>\n".
                 "        </p>\n";

            require_once("../libraries/database.inc.php");

            if (Database::Get()->IsConnected() === true)
            {
                $successConnect = true;

                echo "            <p>\n".
                     "              <span class=\"success\">".LANG_STEP3_DBCONNECTSUCCEEDED."</span>\n".
                     "            </p>\n";
            }
            else
            {
                if (strlen(Database::Get()->GetLastErrorMessage()) > 0)
                {
                    echo "        <p>\n".
                         "          <span class=\"error\">".LANG_STEP3_DBCONNECTFAILED." ".Database::Get()->GetLastErrorMessage()."</span>\n".
                         "        </p>\n";
                }
                else
                {
                    echo "        <p>\n".
                         "          <span class=\"error\">".LANG_STEP3_DBCONNECTFAILED." ".LANG_STEP3_DBCONNECTFAILEDNOERRORINFO."</span>\n".
                         "        </p>\n";
                }
            }
        }
        else
        {
            echo "        <p>\n".
                 "          <span class=\"error\">".LANG_STEP3_DATABASECONNECTFILEISNTREADABLE."</span>\n".
                 "        </p>\n";
        }
    }
    else
    {
        echo "        <p>\n".
             "          <span class=\"error\">".LANG_STEP3_DATABASECONNECTFILEDOESNTEXIST."</span>\n".
             "        </p>\n";
    }

    if (isset($_POST['save']) == false ||
        $successConnect == false)
    {
        echo "        <div>\n".
             "          <form action=\"install.php\" method=\"post\">\n".
             "            <fieldset>\n".
             "              <input type=\"hidden\" name=\"step\" value=\"3\"/>\n".
             "              <input type=\"text\" name=\"host\" value=\"".$host."\"/> ".LANG_STEP3_HOSTDESCRIPTION."<br/>\n".
             "              <input type=\"text\" name=\"username\" value=\"".$username."\"/> ".LANG_STEP3_USERNAMEDESCRIPTION."<br/>\n".
             "              <input type=\"text\" name=\"password\" value=\"".$password."\"/> ".LANG_STEP3_PASSWORDDESCRIPTION."<br/>\n".
             "              <input type=\"text\" name=\"database\" value=\"".$database."\"/> ".LANG_STEP3_DATABASENAMEDESCRIPTION."<br/>\n".
             "              <input type=\"text\" name=\"prefix\" value=\"".$prefix."\"/> ".LANG_STEP3_TABLEPREFIXDESCRIPTION."<br/>\n".
             "              <input type=\"submit\" name=\"save\" value=\"".LANG_STEP3_SAVETEXT."\" class=\"mainbox_proceed\"/>\n".
             "            </fieldset>\n".
             "          </form>\n".
             "        </div>\n";
    }
    else
    {
        echo "        <div>\n".
             "          <fieldset>\n".
             "            <form action=\"install.php\" method=\"post\">\n".
             "              <input type=\"hidden\" name=\"step\" value=\"3\"/>\n".
             "              <input type=\"hidden\" name=\"host\" value=\"".$host."\"/>\n".
             "              <input type=\"hidden\" name=\"username\" value=\"".$username."\"/>\n".
             "              <input type=\"hidden\" name=\"password\" value=\"".$password."\"/>\n".
             "              <input type=\"hidden\" name=\"database\" value=\"".$database."\"/>\n".
             "              <input type=\"hidden\" name=\"prefix\" value=\"".$prefix."\"/>\n".
             "              <input type=\"submit\" value=\"".LANG_STEP3_EDITTEXT."\" class=\"mainbox_proceed\"/>\n".
             "            </fieldset>\n".
             "          </form>\n".
             "        </div>\n".
             "        <div>\n".
             "          <form action=\"install.php\" method=\"post\">\n".
             "            <fieldset>\n".
             "              <input type=\"hidden\" name=\"step\" value=\"4\"/>\n".
             "              <input type=\"submit\" value=\"".LANG_STEP3_PROCEEDTEXT."\" class=\"mainbox_proceed\"/>\n".
             "            </fieldset>\n".
             "          </form>\n".
             "        </div>\n";
    }

    echo "      </div>\n".
         "    </div>\n";
}
else if ($step == 4)
{
    $dropExistingTables = false;
    $keepExistingTables = false;

    if (isset($_POST['drop_existing_tables']) === true)
    {
        $dropExistingTables = true;
    }

    if (isset($_POST['keep_existing_tables']) === true)
    {
        $keepExistingTables = true;
    }


    echo "    <div class=\"mainbox\">\n".
         "      <div class=\"mainbox_header\">\n".
         "        <h1 class=\"mainbox_header_h1\">".LANG_STEP4_HEADER."</h1>\n".
         "      </div>\n".
         "      <div class=\"mainbox_body\">\n".
         "        <p>\n".
         "          ".LANG_STEP4_INITIALIZATIONDESCRIPTION."\n".
         "        </p>\n";


    $successInit = false;

    if (isset($_POST['init']) === true)
    {
        require_once("../libraries/database.inc.php");

        if (Database::Get()->IsConnected() === true)
        {
            $success = Database::Get()->BeginTransaction();

            /**
             * @todo No preparation of SQL query strings, because no user input is
             *     involved. If user input gets inserted in the future, update
             *     the method calls!
             */

            // Table users

            if ($success === true)
            {
                if ($dropExistingTables === true)
                {
                    if (Database::Get()->ExecuteUnsecure("DROP TABLE IF EXISTS ".Database::Get()->GetPrefix()."users") !== true)
                    {
                        $success = false;
                    }
                }
            }

            if ($success === true)
            {
                $sql = "CREATE TABLE ";

                if ($keepExistingTables === true)
                {
                    $sql .= "IF NOT EXISTS ";
                }
                
                $sql .= "`".Database::Get()->GetPrefix()."users` (".
                        "  `id` int(11) NOT NULL AUTO_INCREMENT,".
                        "  `name` varchar(40) COLLATE utf8_bin NOT NULL,".
                        "  `salt` varchar(255) COLLATE utf8_bin NOT NULL,".
                        "  `password` varchar(255) COLLATE utf8_bin NOT NULL,".
                        "  PRIMARY KEY (`id`),".
                        "  UNIQUE KEY `name` (`name`)".
                        ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";

                if (Database::Get()->ExecuteUnsecure($sql) !== true)
                {
                    $success = false;
                }
            }
            
            // Table messages

            if ($success === true)
            {
                if ($dropExistingTables === true)
                {
                    if (Database::Get()->ExecuteUnsecure("DROP TABLE IF EXISTS ".Database::Get()->GetPrefix()."messages") !== true)
                    {
                        $success = false;
                    }
                }
            }

            if ($success === true)
            {
                $sql = "CREATE TABLE ";

                if ($keepExistingTables === true)
                {
                    $sql .= "IF NOT EXISTS ";
                }
                
                $sql .= "`".Database::Get()->GetPrefix()."messages` (".
                        "  `id` int(11) NOT NULL AUTO_INCREMENT,".
                        "  `message` text COLLATE utf8_bin NOT NULL,".
                        "  `id_user` int(11) NOT NULL,".
                        "  PRIMARY KEY (`id`)".
                        ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin";

                if (Database::Get()->ExecuteUnsecure($sql) !== true)
                {
                    $success = false;
                }
            }


            if ($success === true)
            {
                if (Database::Get()->commitTransaction() === true)
                {
                    echo "        <p>\n".
                         "          <span class=\"success\">".LANG_STEP4_DBOPERATIONSUCCEEDED."</span>\n".
                         "        </p>\n";

                    $successInit = true;
                }
                else
                {
                    echo "        <p>\n".
                         "          <span class=\"error\">".LANG_STEP4_DBCOMMITFAILED."</span>\n".
                         "        </p>\n";
                }
            }
            else
            {
                if (strlen(Database::Get()->GetLastErrorMessage()) > 0)
                {
                    echo "        <p>\n".
                         "          <span class=\"error\">".LANG_STEP4_DBOPERATIONFAILED." ".Database::Get()->GetLastErrorMessage()."</span>\n".
                         "        </p>\n";
                }
                else
                {
                    echo "        <p>\n".
                         "          <span class=\"error\">".LANG_STEP4_DBOPERATIONFAILED." ".LANG_STEP4_DBOPERATIONFAILEDNOERRORINFO."</span>\n".
                         "        </p>\n";
                }

                Database::Get()->RollbackTransaction();
            }
        }
        else
        {
            if (strlen(Database::Get()->GetLastErrorMessage()) > 0)
            {
                echo "        <p>\n".
                     "          <span class=\"error\">".LANG_STEP4_DBCONNECTFAILED." ".Database::Get()->GetLastErrorMessage()."</span>\n".
                     "        </p>\n";
            }
            else
            {
                echo "        <p>\n".
                     "          <span class=\"error\">".LANG_STEP4_DBCONNECTFAILED." ".LANG_STEP3_DBCONNECTFAILEDNOERRORINFO."</span>\n".
                     "        </p>\n";
            }
        }
    }

    echo "        <div>\n".
         "          <form action=\"install.php\" method=\"post\">\n".
         "            <fieldset>\n";

    if ($successInit === true)
    {
        echo "              <input type=\"hidden\" name=\"step\" value=\"5\"/>\n";
    }
    else
    {
        echo "              <input type=\"hidden\" name=\"step\" value=\"4\"/>\n";
    }

    if ($dropExistingTables === true)
    {
        echo "              <input type=\"checkbox\" name=\"drop_existing_tables\" value=\"drop\" checked=\"checked\"/> ".LANG_STEP4_CHECKBOXDESCRIPTIONDROPEXISTINGTABLES."<br/>\n";
    }
    else
    {
        echo "              <input type=\"checkbox\" name=\"drop_existing_tables\" value=\"drop\"/> ".LANG_STEP4_CHECKBOXDESCRIPTIONDROPEXISTINGTABLES."<br/>\n";
    }

    if ($keepExistingTables === true)
    {
        echo "              <input type=\"checkbox\" name=\"keep_existing_tables\" value=\"keep\" checked=\"checked\"/> ".LANG_STEP4_CHECKBOXDESCRIPTIONKEEPEXISTINGTABLES."<br/>\n";
    }
    else
    {
        echo "              <input type=\"checkbox\" name=\"keep_existing_tables\" value=\"keep\"/> ".LANG_STEP4_CHECKBOXDESCRIPTIONKEEPEXISTINGTABLES."<br/>\n";
    }

    echo "              <input type=\"submit\" name=\"init\" value=\"".LANG_STEP4_INITIALIZETEXT."\" class=\"mainbox_proceed\"/>\n";

    if ($successInit === true)
    {
        echo "              <input type=\"submit\" value=\"".LANG_STEP4_COMPLETETEXT."\" class=\"mainbox_proceed\"/>\n";
    }

    echo "            </fieldset>\n".
         "          </form>\n".
         "        </div>\n".
         "      </div>\n".
         "    </div>\n";
}
else if ($step == 5)
{
    echo "    <div class=\"mainbox\">\n".
         "      <div class=\"mainbox_header\">\n".
         "        <h1 class=\"mainbox_header_h1\">".LANG_STEP5_HEADER."</h1>\n".
         "      </div>\n".
         "      <div class=\"mainbox_body\">\n".
         "        <p>\n".
         "          ".LANG_STEP5_COMPLETETEXT."\n".
         "        </p>\n".
         "        <div>\n".
         "          <form action=\"../index.php\" method=\"post\">\n".
         "            <fieldset>\n".
         "              <input type=\"hidden\" name=\"install_done\" value=\"install_done\"/>\n".
         "              <input type=\"submit\" value=\"".LANG_STEP5_EXITTEXT."\" class=\"mainbox_proceed\"/>\n".
         "            </fieldset>\n".
         "          </form>\n".
         "        </div>\n".
         "      </div>\n".
         "    </div>\n";
}

echo "  </body>\n".
     "</html>\n";



?>
