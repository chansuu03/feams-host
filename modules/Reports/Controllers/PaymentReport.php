<?php
namespace Modules\Reports\Controllers;

use App\Controllers\BaseController;
use App\Models as Models;
use Modules\Payments\Models as PayModels;
use Modules\Contributions\Models as ContriModels;
use App\Libraries as Libraries;

class PaymentReport extends BaseController
{
    public function __construct() {
        $this->paymentModel = new PayModels\PaymentsModel();
        $this->contriModel = new ContriModels\ContributionModel();
        $this->userModel = new Models\UserModel();
        $this->pdf = new Libraries\Pdf();
    }

    public function index() {
        // checking roles and permissions
        $data['perm_id'] = check_role('37', 'REPO', $this->session->get('role'));
        if(!$data['perm_id']['perm_access']) {
            $this->session->setFlashdata('sweetalertfail', 'Error accessing the page, please try again');
            return redirect()->to(base_url());
        }
        $data['rolePermission'] = $data['perm_id']['rolePermission'];
        $data['perms'] = array();
        foreach($data['rolePermission'] as $rolePerms) {
            array_push($data['perms'], $rolePerms['perm_mod']);
        }

        if($this->request->getMethod() == 'post') {
            $this->generatePDF($_POST);
        }
        $data['allPayments'] = $this->paymentModel->allPaid();
        $data['contributions'] = $this->contriModel->viewAll();
        // echo '<pre>';
        // print_r($data['allPayments']);
        // die();

        $data['user_details'] = user_details($this->session->get('user_id'));
        $data['active'] = 'pay_repo';
        $data['title'] = 'Payment Reports';
        return view('Modules\Reports\Views\payments\index', $data);
    }

    public function changeTable($id) {
        if($id == '1') {
            $data['logins'] = $this->loginModel->withRole();
            return view('Modules\Reports\Views\login\table', $data);
        } elseif($id === '2') {
            $data['logins'] = $this->loginModel->thisDay();
            // echo '<pre>';
            // print_r($data['logins']);
            return view('Modules\Reports\Views\login\table', $data);
        } elseif($id == '3') {
            $data['logins'] = $this->loginModel->weekly();
            return view('Modules\Reports\Views\login\table', $data);
        } elseif($id == '4') {
            $data['logins'] = $this->loginModel->monthly();
            return view('Modules\Reports\Views\login\table', $data);
        }
    }

    private function generatePDF($data) {
        if($this->request->getMethod() == 'post') {
            if($_POST['type'] == '1') {
                $this->generateContri($data);
            }
            if($_POST['type'] == '2') {
                $this->generatePaid($data);
            }
            if($_POST['type'] == '3') {
                $this->generateNotPaid($data);
            }
        }
    }

    private function generateContri($data) {
		$this->pdf->AliasNbPages();
		
		$date = date('F d,Y');
		$this->pdf->AddPage('l', 'Legal');
		$this->pdf->SetFont('Arial','B',12);
        $this->pdf->Cell(70,10,'List of contributions');
		$this->pdf->Ln();

		$this->pdf->SetFont('Arial', 'B' ,8);
		$this->pdf->SetX(55);
		$this->pdf->Cell(50,10,'Contribution',1);
		$this->pdf->Cell(50,10,'Cost',1);
		$this->pdf->Cell(50,10,'Total payments',1);
		$this->pdf->Cell(50,10,'Date added',1);
		$this->pdf->Ln();
		foreach($this->contriModel->findAll() as $contri) {
			$this->pdf->SetX(55);
			$this->pdf->SetFont('Arial', '' ,8);
            if($contri['created_at'] >= $data['start'] && $contri['created_at'] <= $data['end']) {
                $this->pdf->Cell(50,8,$contri['name'],1);
                $this->pdf->Cell(50,8,$contri['cost'],1);
                $amount = 0;
                foreach($this->paymentModel->findAll() as $pay) {
                    if($pay['contri_id'] == $contri['id']) {
                        $amount += $pay['amount'];
                    }
                }
                $this->pdf->Cell(50,8,$amount,1);
                $date = date_create($contri['created_at']);
                $created_at = date_format($date, 'F d, Y H:i:s');
                $this->pdf->Cell(50,8,$created_at,1);
                $this->pdf->Ln();
            }
		}
		$date = date('F d,Y');
        $startDate = date_create($data['start']);
        $start = date_format($startDate, 'F d, Y');
        $endDate = date_create($data['end']);
        $end = date_format($endDate, 'F d, Y');
        $this->response->setHeader('Content-Type', 'application/pdf');
		$this->pdf->Output('D', 'Payment Report ['.$start.' -'. $end .'].pdf'); 
    }

    private function generatePaid($data) {
		$this->pdf->AliasNbPages();
		
		$date = date('F d,Y');
		$this->pdf->AddPage('l', 'Legal');
		$this->pdf->SetFont('Arial','B',12);
        $this->pdf->Cell(70,10,'List of people who paid for contributions');
		$this->pdf->Ln();

		$this->pdf->SetFont('Arial', 'B' ,8);
		$this->pdf->SetX(55);
		$this->pdf->Cell(50,10,'User',1);
		$this->pdf->Cell(50,10,'Contribution',1);
		$this->pdf->Cell(50,10,'Amount',1);
		$this->pdf->Cell(50,10,'Date paid',1);
		$this->pdf->Ln();
		foreach($this->paymentModel->allPaid() as $pay) {
			$this->pdf->SetX(55);
			$this->pdf->SetFont('Arial', '' ,8);
            if($pay['created_at'] >= $data['start'] && $pay['created_at'] <= $data['end']) {
                $this->pdf->Cell(50,8,$pay['first_name'].' '. $pay['last_name'],1);
                $this->pdf->Cell(50,8,$pay['amount'],1);
                $this->pdf->Cell(50,8,$pay['name'],1);
                $date = date_create($pay['created_at']);
                $created_at = date_format($date, 'F d, Y H:i:s');
                $this->pdf->Cell(50,8,$created_at,1);
                $this->pdf->Ln();
            }
		}
		$date = date('F d,Y');
        $startDate = date_create($data['start']);
        $start = date_format($startDate, 'F d, Y');
        $endDate = date_create($data['end']);
        $end = date_format($endDate, 'F d, Y');
        $this->response->setHeader('Content-Type', 'application/pdf');
		$this->pdf->Output('D', 'Payment Report ['.$start.' -'. $end .'].pdf'); 
    }
    
    private function generateNotPaid($data) {
		$this->pdf->AliasNbPages();
        $contri = $this->contriModel->where('id', $data['cont'])->first();
		
		$date = date('F d,Y');
		$this->pdf->AddPage('P', 'Legal');
		$this->pdf->SetFont('Arial','B',12);
        $this->pdf->Cell(70,10,'List of people who are not paid for '. $contri['name']);
		$this->pdf->Ln();

		$this->pdf->SetFont('Arial', 'B' ,8);
		// $this->pdf->SetX(55);
		// $this->pdf->Cell(50,10,'User',1);
		// $this->pdf->Ln();

        $payUser = array();
		foreach($this->paymentModel->allPaid() as $pay) {
            if($pay['contri_id'] == $contri['id']) {
                $payUser[] = $pay['user_id'];
            }
		}
        $users = $this->userModel->where('status', '1')->findAll();
        foreach($users as $user) {
            if(!in_array($user['id'], $payUser)) {
                $notPaid[] = $user['first_name'].' '.$user['last_name'];
            }
        }
        $ctr = 1;
        foreach($notPaid as $not) {
            $this->pdf->Cell(70,5, $ctr.'. '.$not);
            $this->pdf->Ln();
            $ctr++;
        }
        
		$date = date('F d,Y');
        $startDate = date_create($data['start']);
        $start = date_format($startDate, 'F d, Y');
        $endDate = date_create($data['end']);
        $end = date_format($endDate, 'F d, Y');
        $this->response->setHeader('Content-Type', 'application/pdf');
		$this->pdf->Output('D', 'Payment Report ['.$start.' -'. $end .'].pdf'); 
    }
}