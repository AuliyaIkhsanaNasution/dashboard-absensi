<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class WelcomeKaryawanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $karyawan;
    public $password;

    public function __construct($karyawan, $password)
    {
        $this->karyawan = $karyawan;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Akun Karyawan Anda'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome_karyawan',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}