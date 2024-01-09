<?php

namespace App\Http\Controllers;

use App\Models\Certificat;
use Codedge\Fpdf\Fpdf\Fpdf;
use Exception;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected $fpdf;

    public function __construct()
    {
        // General Setting of pdf file
        $this->fpdf = new Fpdf('P', 'mm', array(300, 300));
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage();
        $this->fpdf->SetFillColor(245,237,110);
        $this->fpdf->Rect(10,10,265,115,'F');
    }

    public function pdfOutput($id)
    {
        try{
        $logo = public_path('logo.jpg');
        if(!$logo) throw new Exception("logo unavailable", 404);

        $certificatId = Certificat::find($id);
        if(!$certificatId) throw new Exception("certificate informations unavailable", 404);

        $certificatInfos = $certificatId->join('proprietaires', 'proprietaires.id', '=', 'certificats.proprietaire')
        ->join('vehicules', 'vehicules.id', '=', 'certificats.vehicule');


    //    head
        $this->fpdf->SetFillColor(255,215,0);
        $this->fpdf->SetFont('Arial', 'B', 13);
        $this->fpdf->MultiCell(70,10, 'A DETACHER
        ET A GLISSER
        DANS LA POCHETTE'
        ,0,'C',true)   ;

        //body
        $this->fpdf->DashedRect(10.5,40,80,110);
        $this->fpdf->SetFont('Arial', 'B', 9);
        $this->fpdf->Cell(100,7,'VEHICULE:',0,1,'L',false);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'Genre:',0,0,'L',false);
        $this->fpdf->Cell(40,5, $certificatInfos->value('typeDeVehicule'),0,1,'L',false);
        $this->fpdf->Cell(30,5,'Marque:',0,0,'L',false);
        $this->fpdf->Cell(40,5,$certificatInfos->value('marque'),0,1,'L',false);
        $this->fpdf->Cell(30,5,'Model:',0,0,'L',false);
        $this->fpdf->Cell(40,5,$certificatInfos->value('model'),0,1,'L',false);
        $this->fpdf->Cell(30,5,'N* imm:',0,0,'L',false);
        $this->fpdf->Cell(40,5,$certificatInfos->value('immatriculation'),0,1,'L',false);
        $this->fpdf->Cell(30,5,'valable Du:',0,0,'L',false);
        $this->fpdf->Cell(40,5,$certificatInfos->value('date_delivrance'),0,1,'L',false);
        $this->fpdf->Cell(30,5,'              Au:',0,0,'L',false);
        $this->fpdf->Cell(40,5,$certificatInfos->value('date_expiration'),0,1,'L',false);

        $this->fpdf->Image($logo, 35,78,-150);
        $this->fpdf->SetY(95);
        $this->fpdf->SetFont('Arial', 'B', 11);
        $this->fpdf->SetFillColor(248,142,85);
        $this->fpdf->RoundedRect(12,95,67,5,3,'1111','DF');
        $this->fpdf->Cell(70,5,('SN '. $certificatInfos->value("certificats.id")),0,1,'C',false);
        //foot
        $this->fpdf->SetY(110.1);
        $this->fpdf->SetFont('Arial', 'B', 13);
        $this->fpdf->SetFillColor(255,215,0);
        $this->fpdf->Cell(70.5,15,'A DETACHER',0,0,'C',true);
        //end decoupage

        //partie souscripteur
        $this->fpdf->SetFont('Arial', 'B', 10);
        $this->fpdf->SetXY(100, 19);
        $this->fpdf->Cell(10,12,'SOUSCRIPTEUR',0,1,'L',false);
        $this->fpdf->Ln(10);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->SetXY(100, 35);
        $this->fpdf->Cell(30,5,'Nom:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,("{$certificatInfos->value('prenom')}  {$certificatInfos->value('nom')}"),0,1,'L',false);
        $this->fpdf->SetXY(100, 40);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'Profession:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('profession'),0,1,'L',false);
        $this->fpdf->SetXY(100, 45);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'Adresse:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('adresse'),0,1,'L',false);

        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->SetXY(100, 80);
        $this->fpdf->Cell(100,7,'VEHICULE:',0,1,'L',false);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->SetXY(100,90);
        $this->fpdf->Cell(30,5,'Genre:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('typeDeVehicule'),0,1,'L',false);
        $this->fpdf->SetXY(100,95);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'Marque:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('marque'),0,1,'L',false);
        $this->fpdf->SetXY(100, 100);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'Model:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('model'),0,1,'L',false);
        $this->fpdf->SetXY(100, 105);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'N* imm:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('immatriculation'),0,1,'L',false);
        $this->fpdf->SetXY(100, 110);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'valable Du:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('date_delivrance'),0,1,'L',false);
        $this->fpdf->SetXY(110, 115);
        $this->fpdf->SetFont('Arial', 'B', 8);
        $this->fpdf->Cell(30,5,'Au:',0,0,'L',false);
        $this->fpdf->SetFont('Arial', 'I',12);
        $this->fpdf->Cell(20,5,$certificatInfos->value('date_expiration'),0,1,'L',false);

        //partie entreprise
        $this->fpdf->RoundedRect(175,17,95,100,3,'1111','D');
        $this->fpdf->Image($logo, 180,20,-80);
        $this->fpdf->SetFont('Arial', 'I', 10);
        $this->fpdf->SetXY(205, 20);
        $this->fpdf->Cell(70,12,'369, PLLES ASSAINIES U6 x DAKAR SN', 0,1, 'L', false);
        $this->fpdf->SetXY(220, 25);
        $this->fpdf->Cell(70,12,'Tel: +221 77 867 56 91',0,1,'L',false);
        $this->fpdf->SetXY(220, 30);
        $this->fpdf->Cell(70,12,'Fax: +221 77 724 84 08',0,1,'L',false);
        $this->fpdf->SetXY(220, 35);
        $this->fpdf->Cell(70,12,'B.P. 14000 DAKAR RP',0,1,'L',false);
        $this->fpdf->SetXY(205, 40);
        $this->fpdf->Cell(70,12,'e-mail: senegal.insuranceme@lahad-dev.com', 0,1, 'L', false);
        $this->fpdf->SetXY(185, 60);
        $this->fpdf->Cell(70,12,'Entreprise regle par le Code des Assurances', 0,1,'C', false);
        $this->fpdf->SetXY(185, 65);
        $this->fpdf->SetFont('Arial', 'B', 14);
        $this->fpdf->Cell(70,12,"ATTESTATION D'ASSURANCE*", 0,1,'C', false);
        $this->fpdf->SetXY(185, 75);
        $this->fpdf->SetFont('Arial', 'B', 11);
        $this->fpdf->SetFillColor(248,142,85);
        $this->fpdf->RoundedRect(185,75,73,5,3,'1111','DF');
        $this->fpdf->Cell(73,5,"SN {$certificatInfos->value('certificats.id')}",0,1,'C',false);
        $this->fpdf->SetXY(181, 81);
        $this->fpdf->SetFont('Arial', 'I', 7.5);
        $this->fpdf->MultiCell(80,3,"<<La presentation de la presente attestation n'implique selon les
        dispositions de l'article 213 du Code CIMA, qu'une presomption
                     de garantie a la charge de l'Assureur>>.",0,'C', false);
        $this->fpdf->SetXY(185,95);
        $this->fpdf->Cell(73,5,'Pour la Societe', 0,1,'C',false);

        $this->fpdf->Output('F',('PdfOutput/'.$certificatInfos->value('certificats.id').'.pdf'));

        return response()->file(public_path("PdfOutput/{$certificatInfos->value('certificats.id')}.pdf"), ['Content-Type' => 'application/pdf']);


        }catch(\Throwable $th){
            return response()->json(["error" => $th->getMessage()], $th->getCode());
        }
    }
}
