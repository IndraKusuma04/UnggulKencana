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
        // Path to the report files
        $input = storage_path('app/reports/laporan/LaporanProduk.jrxml'); // Path to your jrxml file
        $output = storage_path('app/public/output/laporan');

        // Database connection configuration
        $options = [
            'format' => ['pdf'],
            'db_connection' => [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port', '3306'),
                'database' => config('database.connections.mysql.database'),
                'username' => config('database.connections.mysql.username'),
                'password' => config('database.connections.mysql.password'),
                'jdbc_driver' => 'com.mysql.cj.jdbc.Driver',
                'jdbc_url' => 'jdbc:mysql://' . config('database.connections.mysql.host') . ':' . config('database.connections.mysql.port', '3306') . '/' . config('database.connections.mysql.database') . '?useUnicode=true&characterEncoding=UTF-8&useSSL=false',
                'jdbc_dir' => base_path('vendor/geekcom/phpjasper/bin/jdbc'),
            ],
        ];

        // Create PHPJasper instance and process the report
        $jasper = new PHPJasper;

        try {
            // Compile jrxml to jasper
            $jasper->compile($input)->execute();

            // Replace .jrxml extension with .jasper to specify compiled report path
            $compiledReport = preg_replace('/\.jrxml$/', '.jasper', $input);

            // Generate report PDF
            $jasper->process(
                $compiledReport,
                $output,
                $options['format'],
                $options['db_connection']
            )->execute();

            $pdfFile = $output . '.pdf';

            if (!file_exists($pdfFile)) {
                return response('Report generation failed: PDF file not found.', 500);
            }

            // Example of logging the SQL query results
            $results = DB::select("SELECT pr.kodeproduk, jp.jenis_produk, pr.nama, pr.harga_jual, pr.harga_beli, pr.berat, pr.karat, pr.lingkar, pr.panjang FROM produk pr JOIN jenis_produk jp ON pr.jenisproduk_id = jp.id JOIN kondisi k ON pr.kondisi_id = k.id");
            Log::info('Query Results: ', (array) $results);
            Log::info('DB config', config('database.connections.mysql'));


            return response()->file($pdfFile)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response('Error generating report: ' . $e->getMessage(), 500);
        }
    }

    public function cetakBarcodeProduk($id)
    {
        // Path to the report files
        $input = storage_path('app/reports/barcode/BarcodeProduk.jrxml'); // Path to your jrxml file
        $output = storage_path('app/public/output/barcode');

        // Database connection configuration
        $options = [
            'format' => ['pdf'],
            'params' => [
                // Add your report parameters here, example:
                'kodeproduk' => $id,
            ],
            'db_connection' => [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port', '3306'),
                'database' => config('database.connections.mysql.database'),
                'username' => config('database.connections.mysql.username'),
                'password' => config('database.connections.mysql.password'),
                'jdbc_driver' => 'com.mysql.cj.jdbc.Driver',
                'jdbc_url' => 'jdbc:mysql://' . config('database.connections.mysql.host') . ':' . config('database.connections.mysql.port', '3306') . '/' . config('database.connections.mysql.database') . '?useUnicode=true&characterEncoding=UTF-8&useSSL=false',
                'jdbc_dir' => base_path('vendor/geekcom/phpjasper/bin/jdbc'),
            ],
        ];

        // Create PHPJasper instance and process the report
        $jasper = new PHPJasper;

        try {
            // Compile jrxml to jasper
            $jasper->compile($input)->execute();

            // Replace .jrxml extension with .jasper to specify compiled report path
            $compiledReport = preg_replace('/\.jrxml$/', '.jasper', $input);

            // Generate report PDF
            $jasper->process(
                $compiledReport,
                $output,
                $options['format'],
                $options['params'],
                $options['db_connection']
            )->execute();

            $pdfFile = $output . '.pdf';

            if (!file_exists($pdfFile)) {
                return response('Report generation failed: PDF file not found.', 500);
            }

            return response()->file($pdfFile)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response('Error generating report: ' . $e->getMessage(), 500);
        }
    }

    public function cetakSuratBarang(Request $request)
    {
        $kodetransaksi  = $request->kodetransaksi;
        $kodeproduk     = $request->kodeproduk;

        $jasperstarterPath = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter');
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
