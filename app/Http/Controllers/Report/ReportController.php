<?php

namespace App\Http\Controllers\Report;

use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function cetakBarcodeProduk($id)
    {
        // Path file sumber & output
        $jrxmlFile = public_path('assets/pages/reports/cetakbarcodeproduk.jrxml'); // File sumber
        $jasperFile = public_path('assets/pages/reports/cetakbarcodeproduk.jasper'); // File hasil compile
        $output = public_path('assets/pages/reports/output_' . $id); // Output per ID agar tidak tertimpa

        $jasperObj = new PHPJasper();

        // **1️⃣ Compile `.jrxml` menjadi `.jasper` jika belum ada**
        if (!file_exists($jasperFile)) {
            $jasperObj->compile($jrxmlFile)->execute();
        }

        // **2️⃣ Jalankan laporan dengan parameter & koneksi database**
        $jasperObj->process(
            $jasperFile,  // Gunakan file hasil compile
            $output,
            [
                'format' => ['pdf'],
                'params' => [
                    'Parameter1' => (int) $id // Pastikan tipe Integer
                ],
                'db_connection' => [
                    'driver'   => 'mysql',
                    'host'     => env('DB_HOST'),
                    'port'     => env('DB_PORT'),
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'database' => env('DB_DATABASE'),
                    'jdbc_url' => 'jdbc:mysql://127.0.0.1:3306/dbunggulkencana?verifyServerCertificate=false',
                ]
            ]
        )->execute();

        // **3️⃣ Kembalikan file PDF ke browser**
        $file = $output . '.pdf';

        return response()->file($file);
    }
}
