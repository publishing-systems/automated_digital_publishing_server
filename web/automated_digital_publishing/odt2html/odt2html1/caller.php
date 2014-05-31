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
 * @file $/web/automated_digital_publishing/odt2html/odt2html1/caller.php
 * @author Stephan Kreutzer
 * @since 2014-05-31
 */



if (isset($_SESSION['input']) === true)
{
    $id = md5(uniqid(rand(), true));
    $_SESSION['output'] = $id;

    $result = shell_exec("java -classpath ".dirname(__FILE__)." odt2html1 ".dirname(__FILE__)."/../../../input/".$_SESSION['input']." ".dirname(__FILE__)."/../../../output/".$id.".html 2>&1");

    file_put_contents(dirname(__FILE__)."/../../../output/".$id.".log", $result);
    return $result;
}

?>
