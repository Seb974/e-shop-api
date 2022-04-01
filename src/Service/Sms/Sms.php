<?php

namespace App\Service\Sms;

use App\Repository\PlatformRepository;

class Sms
{
    private $url;
    private $platformRepository;

    public function __construct($url, PlatformRepository $platformRepository)
    {
        $this->url = $url;
        $this->platformRepository = $platformRepository;
    }

    public function sendTo($clientPhone, $message)
    {
        $platform = $this->getPlatform();
        $data = [
            'user'  => $platform->getSMSUser(),
            'pass'  => $platform->getSMSKey(),
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

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}