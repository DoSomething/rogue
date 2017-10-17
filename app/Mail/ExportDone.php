<?php

namespace Rogue\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportDone extends Mailable
{
    use Queueable, SerializesModels;

    protected $campaignId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pathToExport = Storage::url('export_'.$this->campaignId.'.csv');

        return $this->from('ssmith@dosomething.org')
                    ->subject('Your signup export is ready!')
                    ->attach($pathToExport)
                    ->view('emails.export_done');
    }
}
