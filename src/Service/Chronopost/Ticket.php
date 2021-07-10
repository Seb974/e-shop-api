<?php

namespace App\Service\Chronopost;

class Ticket
{
    private $pdfConverter;
    private $ticketHeight;
    private $ticketWidth;
    private $rootPath;
    private $folder;

    public function __construct($pdfConverter, $ticketHeight, $ticketWidth, $rootPath, $folder)
    {
        $this->pdfConverter = $pdfConverter;
        $this->ticketHeight = $ticketHeight;
        $this->ticketWidth = $ticketWidth;
        $this->rootPath = $rootPath;
        $this->folder = $folder;
    }

    public function getPrintableZPL($skybill, $reservationNumber)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->pdfConverter . $this->ticketHeight . "x" . $this->ticketWidth . "/0/");
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $skybill);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/pdf"));
        $result = curl_exec($curl);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
            $root = $this->rootPath . $this->folder;
            $fileName = $root . $reservationNumber .'.pdf';
            $ticket = fopen($fileName, 'w');
            fwrite($ticket, $result);
            fclose($ticket);
        } else {
            print_r("Error: $result");
        }
        curl_close($curl);

        return $fileName;
    }
}