<?php
require('wp-load.php');
wp_mail('totestertester@gmail.com', 'Test Email', 'This is a test email.');
echo 'Test email sent.';
?>
