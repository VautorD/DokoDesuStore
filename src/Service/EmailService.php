<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmail(User $user, float $total): void
    {
        $email = (new Email())
            ->from('no-reply@dokodesustore.com')
            ->to($user->getEmail())
            ->subject('Confirmation de commande')
            ->text("Bonjour {$user->getPrenom()},\nVotre commande a été confirmée avec succès.\nMontant total : $total EUR\nBien à vous,\nDoko Desu Store.");

        $this->mailer->send($email);
    }

    public function sendNewOrderNotification(User $proprietaire): void
    {
        $email = (new Email())
            ->from('no-reply@dokodesustore.com')
            ->to($proprietaire->getEmail())
            ->subject('Nouvelle commande passée')
            ->text("Bonjour {$proprietaire->getPrenom()},\nUne nouvelle commande a été passée dans votre boutique.\nBien à vous,\nDoko Desu Store.");

        $this->mailer->send($email);
    }
}
