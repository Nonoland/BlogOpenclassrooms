<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Mail;

use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private PHPMailer $PHPMailer;

    public function __construct(
        PHPMailer $PHPMailer = null
    )
    {
        $this->PHPMailer = $PHPMailer ?: new PHPMailer();
        $this->PHPMailer->isSMTP();
        $this->PHPMailer->Host = $_ENV['MAIL_SMTP_HOSTNAME'];
        $this->PHPMailer->SMTPAuth = true;
        $this->PHPMailer->Username = $_ENV['MAIL_SMTP_USERNAME'];
        $this->PHPMailer->Password = $_ENV['MAIL_SMTP_PASSWORD'];
        $this->PHPMailer->SMTPSecure = $_ENV['MAIL_SMTP_SECURE'];
        $this->PHPMailer->Port = (int) $_ENV['MAIL_SMTP_PORT'];
        $this->PHPMailer->CharSet = 'UTF-8';
    }

    /**
     * @throws Exception
     */
    public function sendMail(string $to, string $subject, string $body, string $altBody, string $toName = '', bool $isHTML = false): bool
    {
        if (!PHPMailer::validateAddress($to)) {
            throw new \Exception("Adresse e-mail '$to' invalide !");
        }

        $from = Configuration::getConfiguration("blog_name");

        $this->PHPMailer->setFrom($_ENV['MAIL_ADDRESS'], $from);
        $this->PHPMailer->addAddress($to, $toName);
        $this->PHPMailer->isHTML($isHTML);
        $this->PHPMailer->Subject = $subject;
        $this->PHPMailer->Body = $body;
        $this->PHPMailer->AltBody = $altBody;

        try {
            return $this->PHPMailer->send();
        } catch (\Exception $e) {
            error_log("Erreur lors de l'envoi du mail: " . $e->getMessage());
            return false;
        }
    }

    public static function sendMailToUser(int $userId, string $subject, string $body, string $altBody, bool $isHTML = false): bool
    {
        $user = new User($userId);
        $mail = new Mail();

        return $mail->sendMail(
            $user->getEmail(),
            $subject,
            $body,
            $altBody,
            $user->getFirstname() . ' ' . $user->getLastname(),
            $isHTML
        );
    }
}
