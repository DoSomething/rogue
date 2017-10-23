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

    /**
     * The full campaign array with campaign details.
     *
     * @var array
     */
    protected $campaign;

    /**
     * The id of the campaign run.
     *
     * @var int
     */
    protected $campaignRun;

    /**
     * The raw data of the generated CSV to attach to the email.
     *
     * @var string
     */
    protected $csv;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($campaign, $campaignRun, $csv)
    {
        $this->campaign = $campaign;
        $this->campaignRun = $campaignRun;
        $this->csv = $csv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Defaults to send from 'ssmith@dosomething.org',
        // but we can change this if there is a better address to use.
        // However, folks should feel lucky to get an email from Shae Smith.
        return $this->from('ssmith@dosomething.org')
                    ->subject('Your signup export is ready!')
                    ->attachData($this->csv, 'export_'.$this->campaign['id'].'.csv', [
                        'mime' => 'text/csv',
                    ])
                    ->view('emails.export_done')
                    ->with([
                        'campaign' => $this->campaign,
                        'campaignRun' => $this->campaignRun,
                    ]);
    }
}
