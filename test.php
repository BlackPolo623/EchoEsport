<?php
echo "PHP is working!";
echo "<br>";
echo "Current directory: " . getcwd();
echo "<br>";
echo "Files in directory: ";
print_r(scandir('.'));
