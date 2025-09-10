<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Mail\Mailables\Attachment;

class PostCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Post $post){}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Post Creado: ' . $this->post->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.post.created',
            with:[
                'post' => $this->post,
                'author' => $this->post->user->name,
                'published_at' => $this->post->published_at?->format('d/m/y')
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
   public function attachments(): array
{
    $attachments = [];
    if ($this->post->cover_image && Storage::disk('public')->exists($this->post->cover_image)) {
        $attachments[] = Attachment::fromPath(
            Storage::disk('public')->path($this->post->cover_image)
        )->as('COVER_' . $this->post->id . '.' . pathinfo($this->post->cover_image, PATHINFO_EXTENSION));
    }   
    return $attachments;
}
}
