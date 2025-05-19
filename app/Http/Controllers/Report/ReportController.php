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
    private function runCommandWithTimeout($cmd, $timeout = 10)
    {
        $descriptorspec = [
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes);

        if (!is_resource($process)) {
            return ['exit_code' => 1, 'output' => ['Gagal membuka proses']];
        }

        $output = [];
        $start = time();
        $stdout = '';
        $stderr = '';

        while (true) {
            $status = proc_get_status($process);

            if (!$status['running']) {
                break;
            }

            if ((time() - $start) > $timeout) {
                proc_terminate($process, 9);
                return ['exit_code' => 124, 'output' => ['Process timeout after ' . $timeout . ' seconds']];
            }

            usleep(100000); // 0.1 detik
        }

        if (is_resource($pipes[1])) {
            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
        }
        if (is_resource($pipes[2])) {
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
        }

        $exitCode = proc_close($process);

        $output = array_filter(array_merge(explode("\n", $stdout), explode("\n", $stderr)));

        return [
            'exit_code' => $exitCode,
            'output'    => $output,
        ];
    }


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

    public function cetakSuratBarang(Request $request)
    {
        $kodetransaksi  = $request->kodetransaksi;
        $kodeproduk     = $request->kodeproduk;

        $jasperstarterPath = '/home/itsmee/Downloads/jasperstarter/bin/jasperstarter';
        $reportName = 'CetakSuratBarang';
        $reportPath = resource_path("reports/$reportName");
        $jasperFile = "$reportPath.jasper";
        $outputPath = public_path('storage/reports/nota');
        $jdbcDir = resource_path('jdbc');

        // Buat folder output jika belum ada
        if (!file_exists($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        // Langsung generate PDF tanpa compile ulang
        $processCmd = sprintf(
            '%s process "%s" -o "%s" -f pdf -t mysql -H 127.0.0.1 -u root -p@Admin123 -n dbunggulkencana --jdbc-dir "%s" -P Parameter1=%s Parameter2=%s',
            $jasperstarterPath,
            $jasperFile,
            $outputPath,
            $jdbcDir,
            $kodetransaksi,
            $kodeproduk
        );

        $processResult = $this->runCommandWithTimeout($processCmd, 20); // max 20 detik

        $pdfPath = $outputPath . '/CetakSuratBarang.pdf';

        if (!file_exists($pdfPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File PDF tidak ditemukan',
                'cmd' => $processCmd,
                'output' => $processResult['output'],
            ], 500);
        }

        return response()->file($pdfPath);
    }
}
