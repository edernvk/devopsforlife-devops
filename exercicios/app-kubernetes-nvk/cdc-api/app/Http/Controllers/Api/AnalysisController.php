<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MessageInterface;
use App\Repositories\Interfaces\UserInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AnalysisController extends Controller
{
    protected $messageRepository;
    protected $userRepository;

    public function __construct(
        MessageInterface $messageRepository,
        UserInterface $userRepository
    ) {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
        $this->middleware('auth:api');
    }

    public function retrieve() {
        request()->user()->authorizeRoles(['Administrador']);

        $messagesCount = $this->messageRepository->count();
        $messagesWeek = count($this->messageRepository->createdFrom(7));
        $activity = Activity::orderBy('created_at', 'desc')->paginate(10);

        $activityAccess = Activity::where('description', 'Logado no Sistema')->get();

        $activityAccessTotal = $activityAccess->count();

        $activityAccessPerWeek = $activityAccess->where('created_at', '>=', Carbon::today()->subDays(7))->count();

        $reading_status = $this->retrieveTargetRead();

        return response()->json([
            'messages_total' => $messagesCount,
            'messages_week' => $messagesWeek,
            'activity' => $activity,
            'access_total' => $activityAccessTotal,
            'access_week' => $activityAccessPerWeek,
            'reading_status' => $reading_status
        ]);
    }

    private function retrieveTargetRead() {
        $messages = $this->messageRepository->all();
        $messages->load('to');

        $unreadUsers = 0;
        $countUsers = 0;
        foreach ($messages as $message){
            $unreadUsers += $this->messageRepository->getUserWhoRead($message->id)->count();
            $countUsers += $message->to()->count();
        }

        $result = ($unreadUsers * 100)/$countUsers;

        return $result;
    }

    public function retrieveData() {
        request()->user()->authorizeRoles(['Administrador']);

        $messagesCount = $this->messageRepository->count();
        $messagesWeek = count($this->messageRepository->createdFrom(7));
        $activityAccess = Activity::where('description', 'Logado no Sistema')->get();
        $activityAccessTotal = $activityAccess->count();
        $activityAccessPerWeek = $activityAccess->where('created_at', '>=', Carbon::today()->subDays(7))->count();

        return response()->json([
            'messages_total' => $messagesCount,
            'messages_week' => $messagesWeek,
            'access_week' => $activityAccessPerWeek,
            'access_total' => $activityAccessTotal,
        ]);
    }

    public function retrieveActivity() {
        $activity = Activity::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($activity);
    }
}
