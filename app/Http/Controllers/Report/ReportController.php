<?php

namespace App\Http\Controllers\Report;

use PHPJasper\PHPJasper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function cetakLaporanProduk()
    {
        // // Paths
        $jrxmlPath = storage_path('app/reports/laporan/LaporanProduk.jasper');
        $outputDir = public_path('reports');
        $outputFileName = 'LaporanProduk.pdf';

        // Ensure output directory exists
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // JasperStarter executable path - adjust if needed
        $jasperstarterCmd = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter'); // Change to your jasperstarter path

        // DB connection details from config
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Compile command
        // $compileCommand = escapeshellcmd("{$jasperstarterCmd} compile \"{$jrxmlPath}\"");

        // Generate command with DB params
        $generateCommand = escapeshellcmd("{$jasperstarterCmd} process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
            . " -u {$dbUser} -p {$dbPass} -H {$dbHost} -n {$dbName}  --db-port={$dbPort}");

        try {
            // Compile jrxml to jasper
            // Log::info("Running compile command: {$compileCommand}");
            // exec($compileCommand, $compileOutput, $compileReturnVar);
            // if ($compileReturnVar !== 0) {
            //     Log::error('Compile failed: ' . implode("\n", $compileOutput));
            //     return response('Failed to compile report.', 500);
            // }

            // Generate report PDF
            Log::info("Running generate command: {$generateCommand}");
            exec($generateCommand, $generateOutput, $generateReturnVar);
            if ($generateReturnVar !== 0) {
                Log::error('Generate report failed: ' . implode("\n", $generateOutput));
                return response('Failed to generate report.', 500);
            }

            $pdfFilePath = $outputDir . '/' . $outputFileName;
            if (!file_exists($pdfFilePath)) {
                Log::error("Generated PDF file not found at path: {$pdfFilePath}");
                return response('Generated PDF file not found.', 500);
            }

            return response()->file($pdfFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $ex) {
            Log::error('Exception when generating report: ' . $ex->getMessage());
            return response('Exception occurred: ' . $ex->getMessage(), 500);
        }
    }

    public function cetakBarcodeProduk($id)
    {
        // // Paths
        $jrxmlPath = storage_path('app/reports/barcode/BarcodeProduk.jasper');
        $outputDir = public_path('barcode');
        $outputFileName = 'BarcodeProduk.pdf';
        $barcodePath = public_path('storage/barcode/');

        // Ensure output directory exists
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // JasperStarter executable path - adjust if needed
        $jasperstarterCmd = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter'); // Change to your jasperstarter path

        // DB connection details from config
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Compile command
        // $compileCommand = "\"{$jasperstarterCmd}\" compile \"{$jrxmlPath}\"";

        // Generate command with DB params
        $generateCommand = "\"{$jasperstarterCmd}\" process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
            . " -u \"{$dbUser}\" -p \"{$dbPass}\" -H \"{$dbHost}\" -n \"{$dbName}\" --db-port=\"{$dbPort}\""
            . " -P kodeproduk=\"{$id}\" barcodePath=\"{$barcodePath}\"";

        try {
            // Compile jrxml to jasper
            // Log::info("Running compile command: {$compileCommand}");
            // exec($compileCommand, $compileOutput, $compileReturnVar);
            // if ($compileReturnVar !== 0) {
            //     Log::error('Compile failed: ' . implode("\n", $compileOutput));
            //     return response('Failed to compile report.', 500);
            // }

            // Generate report PDF
            Log::info("Running generate command: {$generateCommand}");
            exec($generateCommand, $generateOutput, $generateReturnVar);
            if ($generateReturnVar !== 0) {
                Log::error('Generate report failed: ' . implode("\n", $generateOutput));
                return response('Failed to generate report.', 500);
            }

            $fullBarcodeFile = $barcodePath . $id . ".png";

            if (file_exists($fullBarcodeFile)) {
                Log::info("File barcode ditemukan: " . $fullBarcodeFile);
            } else {
                Log::error("File barcode TIDAK ditemukan: " . $fullBarcodeFile);
            }

            $pdfFilePath = $outputDir . '/' . $outputFileName;
            if (!file_exists($pdfFilePath)) {
                Log::error("Generated PDF file not found at path: {$pdfFilePath}");
                return response('Generated PDF file not found.', 500);
            }

            return response()->file($pdfFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $ex) {
            Log::error('Exception when generating report: ' . $ex->getMessage());
            return response('Exception occurred: ' . $ex->getMessage(), 500);
        }
    }

    public function cetakNotaTransaksi($id)
    {
        // // Paths
        $jrxmlPath = storage_path('app/reports/nota/CetakNotaTransaksi.jasper');
        $outputDir = public_path('nota');
        $outputFileName = 'CetakNotaTransaksi.pdf';
        $assetPath = public_path('assets/img/HEADER.jpg');
        $svgPath   = public_path('assets/img/icons/instagram.svg');
        $imagePath = public_path('storage/produk/');

        // Ensure output directory exists
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // JasperStarter executable path - adjust if needed
        $jasperstarterCmd = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter'); // Change to your jasperstarter path

        // DB connection details from config
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Compile command
        // $compileCommand = escapeshellcmd("{$jasperstarterCmd} compile \"{$jrxmlPath}\"");

        // Generate command with DB params
        // $generateCommand = escapeshellcmd("{$jasperstarterCmd} process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
        //     . " -u {$dbUser} -p {$dbPass} -H {$dbHost} -n {$dbName}  --db-port={$dbPort}" . " -P kodeproduk={$id} -P barcodePath=public/storage/barcode/");

        $generateCommand = "\"{$jasperstarterCmd}\" process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
            . " -u \"{$dbUser}\" -p \"{$dbPass}\" -H \"{$dbHost}\" -n \"{$dbName}\" --db-port=\"{$dbPort}\""
            . " -P Parameter1=\"{$id}\" assetPath=\"{$assetPath}\" imagePath=\"{$imagePath}\" svgPath=\"{$svgPath}\"";

        try {
            // Compile jrxml to jasper
            // Log::info("Running compile command: {$compileCommand}");
            // exec($compileCommand, $compileOutput, $compileReturnVar);
            // if ($compileReturnVar !== 0) {
            //     Log::error('Compile failed: ' . implode("\n", $compileOutput));
            //     return response('Failed to compile report.', 500);
            // }

            // Generate report PDF
            Log::info("Running generate command: {$generateCommand}");
            exec($generateCommand, $generateOutput, $generateReturnVar);
            if ($generateReturnVar !== 0) {
                Log::error('Generate report failed: ' . implode("\n", $generateOutput));
                return response('Failed to generate report.', 500);
            }

            $pdfFilePath = $outputDir . '/' . $outputFileName;
            if (!file_exists($pdfFilePath)) {
                Log::error("Generated PDF file not found at path: {$pdfFilePath}");
                return response('Generated PDF file not found.', 500);
            }

            return response()->file($pdfFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $ex) {
            Log::error('Exception when generating report: ' . $ex->getMessage());
            return response('Exception occurred: ' . $ex->getMessage(), 500);
        }
    }

    public function cetakSuratBarang(Request $request)
    {
        $kodetransaksi  = $request->kodetransaksi;
        $kodeproduk     = $request->kodeproduk;
        // // Paths
        $jrxmlPath = storage_path('app/reports/nota/CetakSuratBarang.jasper');
        $outputDir = public_path('nota');
        $outputFileName = 'CetakSuratBarang.pdf';
        $assetPath = public_path('assets/img/HEADER.jpg');
        $svgPath   = public_path('assets/img/icons/instagram.svg');
        $imagePath = public_path('storage/produk/');

        // Ensure output directory exists
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // JasperStarter executable path - adjust if needed
        $jasperstarterCmd = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter'); // Change to your jasperstarter path

        // DB connection details from config
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Compile command
        // $compileCommand = escapeshellcmd("{$jasperstarterCmd} compile \"{$jrxmlPath}\"");

        // Generate command with DB params
        // $generateCommand = escapeshellcmd("{$jasperstarterCmd} process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
        //     . " -u {$dbUser} -p {$dbPass} -H {$dbHost} -n {$dbName}  --db-port={$dbPort}" . " -P kodeproduk={$id} -P barcodePath=public/storage/barcode/");

        $generateCommand = "\"{$jasperstarterCmd}\" process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
            . " -u \"{$dbUser}\" -p \"{$dbPass}\" -H \"{$dbHost}\" -n \"{$dbName}\" --db-port=\"{$dbPort}\""
            . " -P Parameter1=\"{$kodetransaksi}\" Parameter2=\"{$kodeproduk}\" assetPath=\"{$assetPath}\" imagePath=\"{$imagePath}\" svgPath=\"{$svgPath}\"";

        try {
            // Compile jrxml to jasper
            // Log::info("Running compile command: {$compileCommand}");
            // exec($compileCommand, $compileOutput, $compileReturnVar);
            // if ($compileReturnVar !== 0) {
            //     Log::error('Compile failed: ' . implode("\n", $compileOutput));
            //     return response('Failed to compile report.', 500);
            // }

            // Generate report PDF
            Log::info("Running generate command: {$generateCommand}");
            exec($generateCommand, $generateOutput, $generateReturnVar);
            if ($generateReturnVar !== 0) {
                Log::error('Generate report failed: ' . implode("\n", $generateOutput));
                return response('Failed to generate report.', 500);
            }

            $pdfFilePath = $outputDir . '/' . $outputFileName;
            if (!file_exists($pdfFilePath)) {
                Log::error("Generated PDF file not found at path: {$pdfFilePath}");
                return response('Generated PDF file not found.', 500);
            }

            return response()->file($pdfFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $ex) {
            Log::error('Exception when generating report: ' . $ex->getMessage());
            return response('Exception occurred: ' . $ex->getMessage(), 500);
        }
    }

    public function cetakNotaPembelian($id)
    {
        // // Paths
        $jrxmlPath = storage_path('app/reports/nota/CetakNotaPembelian.jasper');
        $outputDir = public_path('nota');
        $outputFileName = 'CetakNotaPembelian.pdf';
        $assetPath = public_path('assets/img/HEADER.jpg');
        $svgPath   = public_path('assets/img/icons/instagram.svg');

        // Ensure output directory exists
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // JasperStarter executable path - adjust if needed
        $jasperstarterCmd = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter'); // Change to your jasperstarter path

        // DB connection details from config
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Compile command
        // $compileCommand = escapeshellcmd("{$jasperstarterCmd} compile \"{$jrxmlPath}\"");

        // Generate command with DB params
        // $generateCommand = escapeshellcmd("{$jasperstarterCmd} process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
        //     . " -u {$dbUser} -p {$dbPass} -H {$dbHost} -n {$dbName}  --db-port={$dbPort}" . " -P kodeproduk={$id} -P barcodePath=public/storage/barcode/");

        $generateCommand = "\"{$jasperstarterCmd}\" process \"{$jrxmlPath}\" -o \"{$outputDir}\" -f pdf -t mysql"
            . " -u \"{$dbUser}\" -p \"{$dbPass}\" -H \"{$dbHost}\" -n \"{$dbName}\" --db-port=\"{$dbPort}\""
            . " -P kodepembelian=\"{$id}\" assetPath=\"{$assetPath}\" svgPath=\"{$svgPath}\"";

        try {
            // Compile jrxml to jasper
            // Log::info("Running compile command: {$compileCommand}");
            // exec($compileCommand, $compileOutput, $compileReturnVar);
            // if ($compileReturnVar !== 0) {
            //     Log::error('Compile failed: ' . implode("\n", $compileOutput));
            //     return response('Failed to compile report.', 500);
            // }

            // Generate report PDF
            Log::info("Running generate command: {$generateCommand}");
            exec($generateCommand, $generateOutput, $generateReturnVar);
            if ($generateReturnVar !== 0) {
                Log::error('Generate report failed: ' . implode("\n", $generateOutput));
                return response('Failed to generate report.', 500);
            }

            $pdfFilePath = $outputDir . '/' . $outputFileName;
            if (!file_exists($pdfFilePath)) {
                Log::error("Generated PDF file not found at path: {$pdfFilePath}");
                return response('Generated PDF file not found.', 500);
            }

            return response()->file($pdfFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $ex) {
            Log::error('Exception when generating report: ' . $ex->getMessage());
            return response('Exception occurred: ' . $ex->getMessage(), 500);
        }
    }
}
