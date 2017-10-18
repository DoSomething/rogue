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

    protected $campaign;
    protected $csv;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($campaign, $csv)
    {
        $this->campaign = $campaign;
        $this->csv = $csv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ssmith@dosomething.org')
                    ->subject('Your signup export is ready!')
                    ->attachData($this->csv, 'export_'.$this->campaign['id'].'.csv', [
                        'mime' => 'text/csv',
                    ])
                    ->view('emails.export_done')
                    ->with([ 'campaign' => $this->campaign ]);
    }
}
