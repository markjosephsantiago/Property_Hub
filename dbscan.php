<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pythonPath = "C:\\Users\\santi\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
$scriptPath = "C:\\wamp64\\www\\Property_Hub\\dbscan.py";

$command = "\"$pythonPath\" \"$scriptPath\"";
$output = shell_exec($command);

echo "<pre>$command\n\n$output</pre>";
?>
