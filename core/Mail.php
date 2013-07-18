<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 23/02/13
 * File: Mail.php
 */
class Mail
{
    public static function getMailerInstance()
    {
        $encrypt = Config::Get('smtp', 'encryption');
        if (empty($encrypt)) {
            $transport = Swift_SmtpTransport::newInstance(Config::Get('smtp', 'server'), Config::Get('smtp', 'port'))
                ->setUsername(Config::Get('smtp', 'username'))
                ->setPassword(Config::Get('smtp', 'password'));
        } else {
            $transport = Swift_SmtpTransport::newInstance(Config::Get('smtp', 'server'), Config::Get('smtp', 'port'), $encrypt)
                ->setUsername(Config::Get('smtp', 'username'))
                ->setPassword(Config::Get('smtp', 'password'));
        }
        return Swift_Mailer::newInstance($transport);
    }
}
