<?php

return array(
 
    'driver' => 'smtp',
 
    'host' => 'smtp.gmail.com',
 
    'port' => 587,
 
    'from' => array('address' => 'authapp@awesomeauthapp.com', 'name' => 'Amazon Customer Service'),
 
    'encryption' => 'tls',
 
    'username' => 'tayyab.abdussami',
 
    'password' => 'arsenal4',
 
    'sendmail' => '/usr/sbin/sendmail -bs',
 
    'pretend' => false,
 
);