<?php

namespace App\Service\Sms;

class Sms
{
    private $url;
    private $user;
    private $pass;

    public function __construct($url, $user, $pass)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function sendTo($clientPhone, $message)
    {
        $data = [
            'user'  => $this->user,
            'pass'  => $this->pass,
            'cmd'   => 'sendsms',
            'to'    => $clientPhone,
            'txt'   => $message,
            'iscom' => 'N'
        ];

        return $this->execute($data);
    }

    private function execute($data)
    {
        $response = "";

        try {
            $request = curl_init($this->url);
            $post = http_build_query($data, '', '&');
            curl_setopt($request, CURLOPT_POST, 1);
            curl_setopt($request, CURLOPT_POSTFIELDS, $post);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($request);
            curl_close($request);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        } finally {
            return $response;
        }
    }
}