<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCampaignAnnouncement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Campaign $campaign) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Campaign: ' . $this->campaign->name . ' - Donation Module',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-campaign',
        );
    }
}
