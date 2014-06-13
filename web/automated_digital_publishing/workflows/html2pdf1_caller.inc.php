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
 * @file $/web/automated_digital_publishing/workflows/html2pdf1_caller.inc.php
 * @author Stephan Kreutzer
 * @since 2014-06-13
 */


function html2pdf1_caller($inputHTML, $logDirectory)
{
    if (file_exists($inputHTML) !== true)
    {
        return -1;
    }
    
    if (file_exists($logDirectory) !== true)
    {
        return -3;
    }

    $locked = false;

    if (file_exists(dirname(__FILE__)."/locked.txt") === true)
    {
        return -4;
    }
    else
    {
        file_put_contents(dirname(__FILE__)."/locked.txt", "locked");
        $locked = true;
    }

    $result = shell_exec("java -classpath ".dirname(__FILE__)." html2pdf1 ".$inputHTML." 2>&1");

    file_put_contents($logDirectory.md5(uniqid(rand(), true)).".log", $result);

    if ($locked === true)
    {
        unlink(dirname(__FILE__)."/locked.txt");
    }
    
    return $result;
}


?>
