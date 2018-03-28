<?php

$container->setParameter('database_host',      getenv('DATABASE_HOST')      ?: '127.0.0.1') ;
$container->setParameter('database_port',      getenv('DATABASE_PORT')      ?: '3306') ;
$container->setParameter('database_name',      getenv('DATABASE_NAME')      ?: 'dakota_booking') ;
$container->setParameter('database_user',      getenv('DATABASE_USER')      ?: 'root') ;
$container->setParameter('database_password',  getenv('DATABASE_PASSWORD')  ?: '') ;

$container->setParameter('mailer_transport',  getenv('MAILER_TRANSPORT')  ?: 'smtp') ;
$container->setParameter('mailer_host',       getenv('MAILER_HOST')       ?: 'ssl0.ovh.net') ;
$container->setParameter('mailer_port',       getenv('MAILER_PORT')       ?: '587') ;
$container->setParameter('mailer_user',       getenv('MAILER_USER')       ?: 'cap-adrenaline.com') ;
$container->setParameter('mailer_encryption', getenv('MAILER_ENCRYPTION') ?: 'tls') ;
$container->setParameter('mailer_password',   getenv('MAILER_PASSWORD')   ?: 'hjPi7CAGHOMp') ;
$container->setParameter('mail_backup',       getenv('MAILER_BACKUP')     ?: 'Maildakotabox2@gmail.com') ;
$container->setParameter('mailer_develloper', getenv('MAILER_DEVELOPPER') ?: 'johann.marie-reine@cap-adrenaline.com') ;
$container->setParameter('mailer_debug',      '%mailer_develloper%') ;
$container->setParameter('mailer_logs',       '%mailer_develloper%') ;

$container->setParameter('locale',            getenv('locale')            ?: 'zn') ;


$container->setParameter('secret',            getenv('SECRET')            ?: 'ThisTokenIsNotSoSecretChangeIt') ;

?>