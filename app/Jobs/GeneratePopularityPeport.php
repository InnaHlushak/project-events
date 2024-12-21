<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class GeneratePopularityPeport implements ShouldQueue
{
    public $jobId;
    public $events;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct($events)
    {
        $this->jobId = Str::uuid(); // Генеруємо унікальний ID
        $this->events = $events;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Генерація PDF
        $pdfContent = Pdf::loadView('pdf.popularity-report', ['events' => $this->events])->output();

        // Збереження PDF у локальне сховище
        $filePath = "reports/{$this->jobId}.pdf";
        Storage::disk('public')->put($filePath, $pdfContent);      
    }
}
