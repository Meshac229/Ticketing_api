<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApiKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function build()
    {
        return $this->view('emails.api_key')
                    ->subject('Votre clé API');
    }
}
