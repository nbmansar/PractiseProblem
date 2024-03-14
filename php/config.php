<?php
$conn = mysqli_connect("localhost","root","","test");
if(!$conn)
echo mysqli_error($conn);
else
echo "connection established";
?>
