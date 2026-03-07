<?php

namespace App\Jobs;

use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Models\WebsiteInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMessageNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    /**
     * Number of seconds the job can run before timing out.
     */
    public int $timeout = 30;

    public function __construct(
        private readonly string  $sent_by,
        private readonly string  $sending_to,
        private readonly string  $sender_type,
        private readonly string  $sender_name,
        private readonly bool    $isFirstMessage,
        private readonly string  $messageType = 'text',
        private readonly string  $messagePreview = '',
        private readonly ?array  $fileData = null,
    ) {}

    public function handle(): void
    {
        try {
            $brandname    = WebsiteInformation::where('id', 1)->value('brandname');
            $contact_mail = WebsiteInformation::where('id', 1)->value('contact_mail');
            // $receiver_email = 'programmer.emad7867@gmail.com';

            if ($this->sender_type === 'wholesaler') {
                $receiver       = Manufacturer::where('manufacturer_uid', $this->sending_to)->first();
                $receiver_email = $receiver->email ?? null;
                $receiver_name  = $receiver->company_name_en ?? 'Manufacturer';
            } else {
                $receiver       = Wholesaler::where('wholesaler_uid', $this->sending_to)->first();
                $receiver_email = $receiver->email ?? null;
                $receiver_name  = $receiver->company_name ?? 'Wholesaler';
            }

            if (!$receiver_email) {
                Log::info('SendMessageNotificationEmail: no receiver email, skipping.', [
                    'sending_to' => $this->sending_to,
                ]);
                return;
            }

            // Resolve human-readable file type label
            $fileTypeLabel = null;
            if ($this->messageType === 'file' && $this->fileData) {
                $mime = $this->fileData['file_type'] ?? '';
                if (str_starts_with($mime, 'image/')) {
                    $fileTypeLabel = 'Image (' . strtoupper(str_replace('image/', '', $mime)) . ')';
                } elseif ($mime === 'application/pdf') {
                    $fileTypeLabel = 'PDF Document';
                } elseif (in_array($mime, [
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ])) {
                    $fileTypeLabel = 'Word Document';
                } elseif (in_array($mime, [
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                ])) {
                    $fileTypeLabel = 'PowerPoint Presentation';
                } else {
                    $ext           = pathinfo($this->fileData['original_name'] ?? '', PATHINFO_EXTENSION);
                    $fileTypeLabel = $ext ? strtoupper($ext) . ' File' : 'File';
                }
            }

            if ($this->sender_type == 'manufacturer') {
                $chat_url = secure_url('wholesaler/chats');
            } else {
                $chat_url = secure_url('manufacturer/chats');
            }


            $subject = $this->isFirstMessage
                ? "New conversation started with {$this->sender_name}"
                : "New message from {$this->sender_name}";

            $data = [
                'brandname'        => $brandname,
                'receiver_name'    => $receiver_name,
                'sender_name'      => $this->sender_name,
                'sender_type'      => $this->sender_type,
                'is_first_message' => $this->isFirstMessage,
                'message_type'     => $this->messageType,
                'message_preview'  => \Illuminate\Support\Str::limit($this->messagePreview, 100),
                'file_data'        => $this->fileData,
                'file_type_label'  => $fileTypeLabel,
                'chat_url'         => $chat_url,
                'contact_mail'     => $contact_mail,
            ];

            Mail::send(
                'mail.message_notification',
                $data,
                function ($message) use ($receiver_email, $subject) {
                    $message->to($receiver_email)->subject($subject);
                }
            );

            Log::info('SendMessageNotificationEmail: email sent successfully.', [
                'sender'         => $this->sent_by,
                'receiver'       => $this->sending_to,
                'receiver_email' => $receiver_email,
                'is_first'       => $this->isFirstMessage,
                'message_type'   => $this->messageType,
            ]);
        } catch (\Exception $e) {
            Log::error('SendMessageNotificationEmail job failed: ' . $e->getMessage(), [
                'sender'   => $this->sent_by,
                'receiver' => $this->sending_to,
            ]);

            // Re-throw so Laravel retries the job (respects $tries)
            throw $e;
        }
    }

    /**
     * Handle a job that has failed all retry attempts.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendMessageNotificationEmail permanently failed after all retries.', [
            'sender'    => $this->sent_by,
            'receiver'  => $this->sending_to,
            'exception' => $exception->getMessage(),
        ]);
    }
}
