<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Http\Requests\UserAccessReportRequest;
use App\Http\Controllers\Controller;
use App\Services\ReportService;

class ReportController extends Controller
{
    private $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function createUserAccess(UserAccessReportRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $start = $request->get('start');
        $end = $request->get('end');
        $fileStatus = '';

        // TODO: check if file already exists, if not then create it
        $filePath = $this->reportService->generateUserAccessBasename($start, $end);
        if (Storage::disk('s3')->exists($filePath.'.csv')) {
            $fileStatus = 'LOADED';
        } else {
            if ($this->reportService->generateUserAccess($start, $end)) {
                $fileStatus = 'CREATED';
            }
        }

        // if success, return temporary signed links
        $expires = now()->addMinutes(2);
        return response()->json([
            'status' => $fileStatus,
            'name' => $start . ' - ' . $end,
            'expires' => $expires->toIso8601String(),
            'download_url' => URL::temporarySignedRoute(
                'report.user-access.download',
                $expires,
                [ 'start' => $start, 'end' => $end ]
            ),
            'view_url' => URL::temporarySignedRoute(
                'report.user-access.view',
                $expires,
                [ 'start' => $start, 'end' => $end ]
            )
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function countUsers(Request $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $start = $request->get('start');
        $end = $request->get('end');
        $type = $request->get('type');

        return $this->reportService->countUsersAccess($start, $end, $type);
    }

    public function viewUserAccess(Request $request) {
        $start = $request->get('start');
        $end = $request->get('end');

        // TODO: check if file already exists, if not then create it
        $filePath = $this->reportService->generateUserAccessBasename($start, $end);
        if (Storage::disk('s3')->exists($filePath.'.csv')) {
            $fileName = $this->reportService->generateUserAccessDownloadName($start,$end);

            return response()->file(Storage::disk('s3')->path($filePath.'.csv'));
        }

        return response()->json([
            "error" => [
                "messages" => "Relatório não encontrado."
            ]
        ], 404);
    }

    public function downloadUserAccess(Request $request) {
        // it will receive a signed url only when it can post to generate one, so not a problem
//        request()->user()->authorizeRoles(['Administrador']);

        // check if files exists, if not then return 404, else download file
        $start = $request->get('start');
        $end = $request->get('end');

        // TODO: check if file already exists, if not then create it
        $filePath = $this->reportService->generateUserAccessBasename($start, $end);
        if (Storage::disk('s3')->exists($filePath.'.csv')) {
            $fileName = $this->reportService->generateUserAccessDownloadName($start,$end);

            return Storage::disk('s3')->download($filePath.'.csv', $fileName.'.csv', ['Content-Type' => 'text/csv']);
        }

        return response()->json([
            "error" => [
                "messages" => "Relatório não encontrado."
            ]
        ], 404);
    }

}
