<?php

require_once __DIR__ . '/../model/RatesModel.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class RatesController
{
    private $model;
    private $twig;
    private $loader;

    public function __construct()
    {
        $this->model = new RatesModel();
        $this->loader = new FilesystemLoader(__DIR__ . 'vendor/autoload.php');
        $this->twig = new Environment($this->loader);
    }

    public function addRateAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'];
            $rate = $_POST['rate'];
            $result = $this->model->AddRate($date, $rate);
            if ($result) {
                $message = 'Rate added successfully';
            } else {
                $message = 'Error adding rate';
            }
            echo $this->twig->render('rates/addRate.html.twig', ['message' => $message]);
        } else {
            echo $this->twig->render('rates/addRate.html.twig');
        }
    }

    public function editRateAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $date = $_POST['date'];
            $rate = $_POST['rate'];
            $result = $this->model->EditRate($id, $date, $rate);
            if ($result) {
                $message = 'Rate updated successfully';
            } else {
                $message = 'Error updating rate';
            }
            echo $this->twig->render('rates/editRate.html.twig', ['message' => $message]);
        } else {
            $id = $_GET['id'];
            $rate = $this->model->SingleRate($id);
            echo $this->twig->render('rates/editRate.html.twig', ['rate' => $rate]);
        }
    }

    public function allRatesAction()
    {
        $rates = $this->model->AllRates();
        echo $this->twig->render('rates/allRates.html.twig', ['rates' => $rates]);
    }

    public function deleteRateAction()
    {
        $id = $_POST['id'];
        $result = $this->model->DeleteRate($id);
        if ($result) {
            $message = 'Rate deleted successfully';
        } else {
            $message = 'Error deleting rate';
        }
        echo $this->twig->render('rates/deleteRate.html.twig', ['message' => $message]);
    }

    public function getHistoricalRatesAction()
    {
        $url = 'https://api.exchangerate.host/timeseries?start_date=2022-01-01&end_date=2022-12-31&base=USD';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        if (isset($data['rates']) && count($data['rates']) > 0) {
            foreach ($data['rates'] as $date => $rates) {
                foreach ($rates as $currency => $rate) {
                    $this->model->AddRate($date, $currency, $rate);
                }
            }
            $message = 'Historical rates imported successfully';
        } else {
            $message = 'Error importing historical rates';
        }
        echo $this->twig->render('rates/historicalRates.html.twig', ['message' => $message]);
    }

    public function generateReportAction()
    {
        $rates = $this->model->AllRates();
        $groupedRates = [];
        foreach ($rates as $rate) {
            $month = date('Y-m', strtotime($rate['date']));
            if (!isset($groupedRates[$month])) {
                $groupedRates[$month] = [];
            }
            $groupedRates[$month][] = $rate['rate'];
        }
        $reportData = [];
        foreach ($groupedRates as $month => $rates) {
            $min = min($rates);
            $max = max($rates);
            $avg = array_sum($rates) / count($rates);

            $reportData[] = [
                'month' => $month,
                'min' => $min,
                'max' => $max,
                'avg' => $avg
            ];
        }
        $template = $this->twig->load('reports/report.html.twig');
        echo $template->render(['reportData' => $reportData]);
    }

    public function exportReportAction()
    {
        $reportData = $_SESSION['report'];
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="exchange_rate_report.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Date', 'Currency', 'Rate'));
        foreach ($reportData as $row) {
            fputcsv($output, array($row['date'], $row['currency'], $row['rate']));
        }
        fclose($output);
    }
}
