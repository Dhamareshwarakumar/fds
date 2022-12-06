<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;


class NotifyBasketItems extends Mailable {

    use Queueable, SerializesModels;

    public $username;
    public $dish_name;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct($username, $dish_name)
    {
        $this->username = $username;
        $this->dish_name = $dish_name;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('18341A0537@gmrit.edu.in', 'Dhamareshwarakumar Gandikota'),
            subject: 'Hungry... Catch this up',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            html: 'emails.notifyBasketItems'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
    
}