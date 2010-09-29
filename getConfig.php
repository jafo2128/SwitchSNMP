#!/usr/bin/php
<?php

/**
 * Test script for debugging/development - identify the module used to handle a switch
 *
 * SwitchSNMP <http://switchsnmp.jasonantman.com>
 * A collection of classes and scripts to communicate with network devices via SNMP.
 *
 * Dependencies:
 * - PHP snmp
 * - PEAR Net_Ping
 *
 * Copyright (c) 2009 Jason Antman.
 * @author Jason Antman <jason@jasonantman.com> <http://www.jasonantman.com>
 *
 ******************************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or   
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to:
 * 
 * Free Software Foundation, Inc.
 * 59 Temple Place - Suite 330
 * Boston, MA 02111-1307, USA.
 ******************************************************************************
 * ADDITIONAL TERMS (pursuant to GPL Section 7):
 * 1) You may not remove any of the "Author" or "Copyright" attributions
 *     from this file or any others distributed with this software.
 * 2) If modified, you must make substantial effort to differentiate
 *     your modified version from the original, while retaining all
 *     attribution to the original project and authors.    
 ******************************************************************************
 * Please use the above URL for bug reports and feature/support requests.
 ******************************************************************************
 * $LastChangedRevision$
 * $HeadURL$
 ******************************************************************************
 */

require_once('com_jasonantman_SwitchSNMP.php');

if(! isset($argv[1])){ die("USAGE: getConfig.php <hostname> <server ip> <path> [community]\n");}
$hostname = $argv[1];
if(isset($argv[2])){ $server = $argv[2];} else { die("no TFTP server specified. DIEING.\n");}
if(isset($argv[3])){ $path = $argv[3];} else { die("no path specified. DIEING.\n");}
if(isset($argv[4])){ $community = $argv[4];} else { $community = "private";}

try
{
  $switch = new com_jasonantman_SwitchSNMP($hostname, $community, true);
}
catch (Exception $e)
{
  fwrite(STDERR, "ERROR: Attempt to construct com_jasonantman_SwitchSNMP threw exception: ".$e->getMessage()."\n");
}

$localpath = "/tftpboot".$path;

// make sure upload file is there
if(! file_exists($localpath)){ touch($localpath);}
if(! file_exists($localpath))
  {
    die("Error: file $localpath does not exist, though it should have been created by this script. Dieing...\n");
  }
chmod($localpath, 0777);

$res = $switch->copyRunningConfigTftp($server, $path, $localpath);

if($res)
  {
    echo "Success.\n";
  }
 else
  {
    echo "Failed: $res\n";
  }


?>
