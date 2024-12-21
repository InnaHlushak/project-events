<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\User;

use Illuminate\Support\Facades\Mail;
use App\Mail\EventInvitation;
use App\Mail\EventTicket;

use App\Mail\AttendanceReport;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Jobs\GeneratePopularityPeport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ClientEventController extends Controller
{
    /**
     * Display a listing of all events with a date no later than the current one
     */
    public function index(Request $request)
    {
        //Access the Event model, return all records sorted (ascending) by deadline-date
        $currentDate = now(); // Get the current date and time
        $events = Event::where('deadline', '>=', $currentDate)
                        ->orderBy('deadline', 'asc')
                        ->paginate(6);

        if( $request->expectsJson()) {
            return new EventCollection($events);
        }

        return response()->json($events);
    }

    /**
     * Display single event whith id.
     */
    public function show(string $id, Request $request)
    {
        //Refer to the Event model and find the 1st record in the table with the specified id 
        $event = Event::findOrFail($id);

        if( $request->expectsJson()) {
            return new EventResource($event);
        }

         return response()->json($event);
    }

    /**
     * Increment popularity of event whith id.
     */
    public function incrementPopularity($id)
    {
        $event = Event::findOrFail($id);
        $event->popularity++;
        $event->save();
        return response()->json(['success' => true]);
    }

    /**
     * Increment number participants of event whith id.
     */
    public function incrementNumber($id)
    {
        $event = Event::findOrFail($id);
        $event->number++;
        $event->save();
        return response()->json(['success' => true]);
    }

    /**
     * Show most popular events (top 3)
     */
    public function popular(Request $request)
    {
        $currentDate = now(); 
        $events = Event::where('deadline', '>=', $currentDate)
                        ->orderBy('popularity', 'desc')
                        ->orderBy('deadline', 'asc')
                        ->take(3)
                        ->get();

        if( $request->expectsJson()) {
            return new EventCollection($events);
        }

        return response()->json($events);
    }

    /**
     * Find event records in the database that contain the specified $text, and display a list of all these events
     */
    public function search( Request $request,string $text)
    {
        $events = Event::where('name', 'like', "%$text%")        
            ->orWhere('description', 'like', "%$text%")
            ->orWhereHas('category', function ($query) use ($text) {
                $query->where('name', 'like', "%$text%");
            })
            ->orWhereHas('costs', function ($query) use ($text) {
                $query->where('name', 'like', "%$text%");
            })
            ->orderBy('deadline', 'asc')
            ->paginate(3);

        return EventResource::collection($events);
    }

    /**
     * Send an event invitation by email
     */
    public function sendInvitationMail(string $idEvent, string $idUser, string $number)
    {
        $event = Event::findOrFail($idEvent);

        $currentDate = now();
        if (strtotime($event->deadline) < strtotime($currentDate)) {
            return response()->json([
                'success' => false,
                'message' => 'Не може бути надісланим оскільки термін події пройшов'
            ]);
        }

        $user = User::findOrFail($idUser);

        Mail::to($user->email)->send(new EventInvitation($event, $user, $number));

        return response()->json(['success' => true, 'message' => 'Запрошення успішно надіслане']);
    }

    /**
     * Send an event ticket by email
     */
    public function sendTicketMail(string $idEvent, string $idUser, string $typeTicked, string $finalPrice, string $number)
    {
        $event = Event::findOrFail($idEvent);

        $currentDate = now();
        if (strtotime($event->deadline) < strtotime($currentDate)) {
            return response()->json([
                'success' => false,
                'message' => 'Не може бути надісланим оскільки термін події пройшов'
            ]);
        }

        $user = User::findOrFail($idUser);

        Mail::to($user->email)->send(new EventTicket($event, $user, $typeTicked, $finalPrice, $number));

        return response()->json(['success' => true, 'message' => 'Квиток успішно надісланий']);
    }

    /**
     * Send an email with an event attendance report attached as a PDF file
     */
    public function sendAttendanceReportMail(string $idUser)
    {
        $user = User::findOrFail($idUser);
        $events = Event::orderBy('number', 'desc')->get();

        $pdf = Pdf::loadView('pdf.attendance-report', ['events' => $events]);        
        Mail::to($user->email)->send(new AttendanceReport($events, $pdf));        

        return response()->json(['success' => true, 'message' => 'Успішно надіслано']);
    }

    /**
     *Generate a PDF file of a report with statistics of popularity of events
     */
    public function downloadPopularityReport()
    {
        $events = Event::orderBy('popularity', 'desc')->get();
        
        // Створюємо завдання
        $job = new GeneratePopularityPeport($events);
        $jobId = $job->jobId;

        // Диспетчеризуємо завдання в чергу
        dispatch($job); 

        // Повертаємо користувачу повідомлення, що завдання в процесі
        return response()->json([
            'success' => true,
            'message' => 'Завдання на генерацію звіту відправлено в чергу. Будь ласка зачекайте.',
            'job_id' => $jobId
        ]);
    }

    public function downloadReport($jobId)
    {
        // Перевірка, чи існує файл після завершення завдання
        $filePath = "reports/{$jobId}.pdf";
        
        // Перевіряємо наявність згенерованого файлу
        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(
                Storage::disk('public')->path($filePath),
                "popularity-report-{$jobId}.pdf",
                ['Content-Type' => 'application/pdf']
            );
        }

        // Якщо файл не знайдено, повідомляємо користувача
        return response()->json([
            'success' => false,
            'message' => 'Файл не знайдено або створення файлу ще не завершено.'
        ]);
    }

    //-----------------------------------------------------
    //Проста логіка (без черг) генерації та завантаження звіту:
    //-----------------------------------------------------
    // public function downloadPopularityReport()
    // {
    //     $events = Event::orderBy('popularity', 'desc')->get();
    //     $pdf = Pdf::loadView('pdf.popularity-report', ['events' => $events]); 

    //     return response($pdf->output(), 200, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'attachment; filename="popularity-report.pdf"',
    //     ]);
    // }
    //-----------------------------------------------------
}