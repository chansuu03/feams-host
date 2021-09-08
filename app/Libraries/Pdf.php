<?php
  namespace App\Libraries;
  require_once(APPPATH. 'ThirdParty/fpdf/fpdf.php');

use App\ThirdParty\FPDF;

class Pdf extends FPDF {
    public function __construct() {
      parent::__construct();
    }

    function Header() {      
      // Get width of page (for lines)
      $width= $this->GetPageWidth(); // Width of Current Page
      $width = $width - 15;
      // Logo
      $this->Image('public/img/fea_outline.png',15,10,40);
      // Set Font
      $this->SetFont('Arial','B',15);
      // Line
      $this->SetLineWidth(1);
      $this->Line(10,28,$width,28);
      // Line break
      $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Date Printed: '. date("F d,Y").'     Page '.$this->PageNo(),0,0,'C');
    }
}