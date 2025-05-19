<?php

namespace App\Http\Controllers\Report;

use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class ReportController extends Controller
{
    public function cetakDaftarProduk()
    {
        $jasper = new PHPJasper();

        $inputJrxml = resource_path('reports/CetakDaftarProduk.jrxml');
        $jasper->compile($inputJrxml)->execute();

        $jasperstarterPath = $jasperstarterPath = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter.exe');
        $jasperFile = resource_path('reports/CetakDaftarProduk.jasper');
        $outputPath = public_path('storage/reports/produk');
        $jdbcDir    = resource_path('jdbc');

        // Pastikan folder output ada
        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        // Bangun command (TANPA kutip satu tambahan!)
        $cmd = sprintf(
            '"%s" process "%s" -f pdf -o "%s" -t mysql -H 127.0.0.1 -u root -p admin -n dbunggulkencana --jdbc-dir "%s" 2>&1',
            $jasperstarterPath,
            $jasperFile,
            $outputPath,
            $jdbcDir
        );

        exec($cmd, $output, $resultCode);

        $pdfPath = $outputPath . '/CetakDaftarProduk.pdf';

        if (!file_exists($pdfPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File PDF tidak ditemukan',
                'cmd' => $cmd,
                'output' => $output
            ], 500);
        }

        return response()->file($pdfPath);
    }

    public function cetakBarcodeProduk($id)
    {
        $jasper = new PHPJasper();

        $inputJrxml = resource_path('reports/CetakBarcodeProduk.jrxml');
        $jasper->compile($inputJrxml)->execute();

        $jasperstarterPath = $jasperstarterPath = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter.exe');
        $jasperFile = resource_path('reports/CetakBarcodeProduk.jasper');
        $outputPath = public_path('storage/reports/barcode');
        $jdbcDir    = resource_path('jdbc');

        // Pastikan folder output ada
        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        // Bangun command dengan parameter
        $cmd = sprintf(
            '"%s" process "%s" -f pdf -o "%s" -t mysql -H 127.0.0.1 -u root -p admin -n dbunggulkencana --jdbc-dir "%s" -P Parameter1="%s"',
            $jasperstarterPath,
            $jasperFile,
            $outputPath,
            $jdbcDir,
            $id
        );

        exec($cmd, $output, $resultCode);

        $pdfPath = $outputPath . '/CetakBarcodeProduk.pdf';

        if (!file_exists($pdfPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File PDF tidak ditemukan',
                'cmd' => $cmd,
                'output' => $output
            ], 500);
        }

        return response()->file($pdfPath);
    }

    public function cetakNotaProduk(Request $request)
    {

        $jasper = new PHPJasper();

        $inputJrxml = resource_path('reports/CetakSuratProduk.jrxml');
        $jasper->compile($inputJrxml)->execute();

        $jasperstarterPath = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter.exe');
        $jasperFile = resource_path('reports/CetakSuratProduk.jasper');
        $outputPath = public_path('storage/reports/nota');
        $jdbcDir    = resource_path('jdbc');

        // Pastikan folder output ada
        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        $kodetransaksi = $request->kodetransaksi;
        $kodeproduk    = $request->kodeproduk;

        // Bangun command dengan parameter
        $cmd = sprintf(
            '"%s" process "%s" -f pdf -o "%s" -t mysql -H 127.0.0.1 -u root -p admin -n dbunggulkencana --jdbc-dir "%s" -P kodetransaksi="%s" -P kodeproduk="%s"',
            $jasperstarterPath,
            $jasperFile,
            $outputPath,
            $jdbcDir,
            $kodetransaksi,
            $kodeproduk
        );

        exec($cmd, $output, $resultCode);

        $pdfPath = $outputPath . '/CetakSuratProduk.pdf';

        if (!file_exists($pdfPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File PDF tidak ditemukan',
                'cmd' => $cmd,
                'output' => $output
            ], 500);
        }

        return response()->file($pdfPath);
    }
}
