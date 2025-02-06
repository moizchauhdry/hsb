<?php

namespace App\Mail;

use App\Exports\RenewalExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Mail\Mailables\Attachment;

class RenewalMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'HSB Portal',
            to: [$this->user->email],
            cc: 'moizchauhdry@gmail.com'
        );
    }

    /**
     * Get the message content definition.`
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.renewal',
            with: ['user' => $this->user]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Define file name`
        $fileName = 'renewals-' . $this->user->id . '.xlsx';

        // Correct path for saving the file
        $filePath = storage_path("app/public/renewals/{$fileName}");

        // Ensure directory exists
        if (!file_exists(storage_path('app/renewals'))) {
            mkdir(storage_path('app/renewals'), 0777, true);
        }

        // Save the Excel file in the correct storage directory
        Excel::store(new RenewalExport($this->user), "renewals/{$fileName}", 'local');

        // Attach file using the correct absolute path
        return [
            Attachment::fromPath($filePath)
                ->as('excel.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
