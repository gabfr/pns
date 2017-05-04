<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use App\User;
use Log;

class SendEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $recipientInfos;

    private $replyToRecipient;

    private $info;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipient, array $info = array(), $replyToRecipient = null)
    {
        if ($recipient != null) {
            $this->recipientInfos = new \stdClass;
            $this->recipientInfos->name = $recipient->name;
            $this->recipientInfos->email = $recipient->email;
        }
        if ($replyToRecipient != null) {
            $this->replyToRecipient = new \stdClass;
            $this->replyToRecipient->name = $replyToRecipient->name;
            $this->replyToRecipient->email = $replyToRecipient->email;
        }
        $this->info = $info;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        if (($this->recipientInfos == null || empty($this->recipientInfos->email) || empty($this->recipientInfos->name))) {
            Log::error('Não é possível disparar email para usuário sem nome ou email', [$this->recipientInfos]);
            return;
        }

        $data = [
            'user' => $this->recipientInfos
        ];

        if (isset($this->info['data'])) {
            foreach ((array) $this->info['data'] as $k => $v) {
                $data[$k] = $v;
            }
        }

        //Log::error('Disparando email... Mais infos: ', [$this]);

        $mailer->send($this->info['template'], $data, function ($m) {
            $m->subject($this->info['subject']);
            $m->to($this->recipientInfos->email, $this->recipientInfos->name);
            if ($this->replyToRecipient != null) {
                $m->replyTo($this->replyToRecipient->email, $this->replyToRecipient->name);
            }
        });
    }
}
