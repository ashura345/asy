<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TunggakanPembayaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;

    /**
     * @param  object  $item  (data join pembayaran_user + users + pembayarans)
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    public function build()
    {
        return $this->subject('Pengingat Tunggakan Pembayaran - Asy-Pay')
                    ->view('emails.tunggakan_pembayaran');
    }
}
