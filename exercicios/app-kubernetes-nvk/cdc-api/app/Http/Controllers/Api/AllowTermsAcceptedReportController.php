<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AllowTermsAcceptedReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class AllowTermsAcceptedReportController extends Controller
{
    private AllowTermsAcceptedReport $reportService;

    public function __construct(AllowTermsAcceptedReport $reportService)
    {
        $this->reportService = $reportService;
    }

    public function createSelectedUsers(Request $request)
    {
        request()->user()->authorizeRoles(['Administrador']);
        $cpfs = $request->input('cpfs');

        $fileStatus = '';

        // TODO: check if file already exists, if not then create it
        // $filePath = $this->reportService->generateFileBasename(now());
        // if (Storage::disk('local')->exists($filePath.'.csv')) {
        //     $fileStatus = 'LOADED';
        // } else {
        //     if ($this->reportService->generateEntriesReport($cpfs)) {
        //         $fileStatus = 'CREATED';
        //     }
        // }
        $filePath = $this->reportService->generateEntriesReport($cpfs);

        // if success, return temporary signed links
        $expires = now()->addMinutes(2);
        return response()->json([
            'name' => 'Usuários selecionados - Gerado em ' . now()->format('Y-m-d H:i'),
            'expires' => $expires->toIso8601String(),
            'download_url' => URL::temporarySignedRoute(
                'report.allow-terms-accepted.download',
                $expires
            ),
            'view_url' => URL::temporarySignedRoute(
                'report.allow-terms-accepted.view',
                $expires
            )
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function createAllUsers()
    {
        request()->user()->authorizeRoles(['Administrador']);
        $this->reportService->generateAllUsersReport();

        // if success, return temporary signed links
        $expires = now()->addMinutes(1);
        return response()->json([
            'name' => 'Todos os Usuários - Gerado em ' . now()->format('Y-m-d H:i'),
            'expires' => $expires->toIso8601String(),
            'download_url' => URL::temporarySignedRoute(
                'report.allow-terms-accepted.download',
                $expires
            ),
            'view_url' => URL::temporarySignedRoute(
                'report.allow-terms-accepted.view',
                $expires
            )
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function viewUserAccess() {
        // TODO: check if file already exists, if not then create it
        $filePath = $this->reportService->generateFileBasename(now());
        if (Storage::disk('s3')->exists($filePath.'.csv')) {
            $fileName = $this->reportService->generateAllowTermsAcceptedDownloadName(now());

            return response()->file(Storage::disk('s3')->path($filePath.'.csv'));
        }

        return response()->json([
            "error" => [
                "messages" => "Relatório não encontrado."
            ]
        ], 404);
    }

    public function downloadUserAccess() {
        // it will receive a signed url only when it can post to generate one, so not a problem
//        request()->user()->authorizeRoles(['Administrador']);

        // check if files exists, if not then return 404, else download file

        // TODO: check if file already exists, if not then create it
        $filePath = $this->reportService->generateFileBasename(now());
        if (Storage::disk('s3')->exists($filePath.'.csv')) {
            $fileName = $this->reportService->generateAllowTermsAcceptedDownloadName(now());

            return Storage::disk('s3')->download($filePath.'.csv', $fileName.'.csv', ['Content-Type' => 'text/csv']);
        }

        return response()->json([
            "error" => [
                "messages" => "Relatório não encontrado."
            ]
        ], 404);
    }
}
