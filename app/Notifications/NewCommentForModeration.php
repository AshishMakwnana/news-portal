<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Comment;
use Illuminate\Support\Str;

class NewCommentForModeration extends Notification
{
    use Queueable;

    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $news = $this->comment->news;
        return (new MailMessage)
            ->subject('New comment awaiting moderation')
            ->line('A new comment has been submitted on "' . $news->title . '" and requires moderation.')
            ->action('View Comment', url('/admin/news/' . $news->id))
            ->line('Comment excerpt:')
            ->line(Str::limit(strip_tags($this->comment->body), 200));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'news_id' => $this->comment->news_id,
        ];
    }
}
