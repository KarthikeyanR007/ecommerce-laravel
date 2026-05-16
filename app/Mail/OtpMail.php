<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $otp,
        public readonly int $expiresIn  // seconds
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your One-Time Password (OTP)',
        );
    }

    public function content(): Content
    {
        $minutes = $this->expiresIn / 60;
        $otp     = $this->otp;

        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
                .container { max-width:480px; margin:40px auto; background:#fff; border-radius:8px; padding:32px; box-shadow:0 2px 8px rgba(0,0,0,.08); }
                .otp-box { font-size:36px; font-weight:bold; letter-spacing:8px; text-align:center; color:#2d6cdf; background:#f0f5ff; border-radius:6px; padding:16px; margin:24px 0; }
                .footer { font-size:12px; color:#999; margin-top:24px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Your One-Time Password</h2>
                <p>It expires in <strong>{$minutes} minute(s)</strong>.</p>
                <div class="otp-box">{$otp}</div>
                <p>If you did not request this, please ignore this email.</p>
                <div class="footer">Do not share this OTP with anyone.</div>
            </div>
        </body>
        </html>
        HTML;

        return new Content(htmlString: $html);  // ✅ view: இல்லாம htmlString: மட்டும்
    }
}

