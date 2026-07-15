<?php



namespace App\Services;



use TCPDF;



class PDFGeneratorService

{

    protected $pdf;



    public function __construct()

    {

        $this->pdf = new TCPDF();

        $this->pdf->SetCreator('D2C Solutions');

        $this->pdf->SetAuthor('Admin');

        $this->pdf->SetTitle('D2C Order Invoice');

    }



    public function generatePDF($html)

    {

        $this->pdf->AddPage();

        $this->pdf->writeHTML($html, true, false, true, false, '');

        return $this->pdf->Output('sample.pdf', 'S'); // S: Return as a string

    }



    public function getHTMLContent($data)

    {

        $data = [

            'title' => 'D2C Order Invoice',

            'date' => date('m/d/Y'),

            'cols' => isset($data['cols']) ? $data['cols'] : [], // Provide a default value if 'cols' is not set

            'data' => $data

        ];



        return view('marketing.Orders.invoice-template', $data)->render();

    }

}