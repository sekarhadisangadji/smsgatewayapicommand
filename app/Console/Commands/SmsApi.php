<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SmsApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SMSApi:Send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pesan sms => create by hadisangadji';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $ApiKey     = env('TXTLOCAL_API_KEY');
        $SenderName = env('TXTLOCAL_SENDERNAME');
        if($ApiKey == null AND $SenderName == null)
        {
           $this->line('TXTLOCAL API KEY dan SenderName masih kosong di file .env, silahkan diisi.'); 
        }
        elseif($ApiKey != null AND $SenderName == null)
        {
            $this->line('TXTLOCAL SenderName masih kosong di file .env, silahkan diisi.'); 
        }
        elseif($ApiKey == null AND $SenderName != null)
        {
            $this->line('TXTLOCAL API KEY masih kosong di file .env, silahkan diisi.'); 
        } else
        {
            $this->line('kirim pesan menggunakan TXTLOCAL API');

            $nomor_telepon = $this->ask('masukkan nomor telepon tujuan ?');

            $pesan = $this->ask('masukkan pesan sms kepada '.$nomor_telepon.' ?');
            if ($this->confirm('apakah anda yakin sudah benar ? [y|N]')) {
                $apiKey = urlencode($ApiKey);
                // Message details
                $numbers = urlencode($nomor_telepon);
                $sender = urlencode($SenderName);
                $message = rawurlencode($pesan);
                // Prepare data for POST request
                $data = 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message;
                // Send the GET request with cURL
                $ch = curl_init('https://api.txtlocal.com/send/?' . $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                // Process your response here
                echo $response;
                //  http://api.txtlocal.com/docs/sendsms
            } else {
                $this->line('anda membatalkan pengiriman sms kepada '.$nomor_telepon.'.'); 
            }
        }
        
    }
}
