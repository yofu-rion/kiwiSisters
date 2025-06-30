<?php
$command = "close";  
if ($command === "open") {
    $doorState = "open";
} elseif ($command === "close") {
    $doorState = "close";
} else {
    $doorState = "unknown";
}
?>