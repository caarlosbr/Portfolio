<?php

// config/EmailConfig.php
namespace App\Core;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;

class EmailConfig
{
    public static function getMailer()
    {
        $dsn = 'smtp://a24boreca%40iesgrancapitan.org:fsjjpggnoszgrefj@smtp.gmail.com:587';
        $transport = Transport::fromDsn($dsn);
        return new Mailer($transport);
    }
}
