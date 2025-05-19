<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class EventCalendar extends Page
{
    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.event-calendar';
    
    protected static ?string $title = 'Kalender Acara';
    
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    public $month;
    public $year;
    public $events;
    public $culturalHeritageId;
    
    public function mount()
    {
        $this->month = request()->query('month', now()->month);
        $this->year = request()->query('year', now()->year);
        $this->culturalHeritageId = request()->query('cultural_heritage_id');
        
        $this->loadEvents();
    }
    
    public function loadEvents()
    {
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $eventsQuery = Event::query()
            ->where('status', true)
            ->where(function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('start_date', '<', $startOfMonth)
                          ->where('end_date', '>', $endOfMonth);
                    });
            });
            
        if ($this->culturalHeritageId) {
            $eventsQuery->whereHas('culturalHeritages', function ($query) {
                $query->where('cultural_heritage_id', $this->culturalHeritageId);
            });
        }
        
        $this->events = $eventsQuery->get();
    }
    
    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->loadEvents();
    }
    
    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->loadEvents();
    }
    
    public function getCalendarData(): array
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1);
        $daysInMonth = $date->daysInMonth;
        $firstDayOfWeek = $date->copy()->firstOfMonth()->dayOfWeek;
        
        // Adjust for Monday as first day of week
        $firstDayOfWeek = $firstDayOfWeek === 0 ? 6 : $firstDayOfWeek - 1;
        
        $weeks = [];
        $week = array_fill(0, 7, null);
        
        // Fill in the blanks before the first day
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $week[$i] = null;
        }
        
        // Fill in the days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dayDate = Carbon::createFromDate($this->year, $this->month, $day)->startOfDay();
            $dayIndex = ($firstDayOfWeek + $day - 1) % 7;
            
            $dayEvents = $this->events->filter(function ($event) use ($dayDate) {
                $eventStart = Carbon::parse($event->start_date)->startOfDay();
                $eventEnd = Carbon::parse($event->end_date)->startOfDay();
                return $dayDate->between($eventStart, $eventEnd);
            });
            
            $week[$dayIndex] = [
                'day' => $day,
                'date' => $dayDate,
                'events' => $dayEvents,
                'isToday' => $dayDate->isToday(),
            ];
            
            // Start a new week if we reach the end of a week
            if ($dayIndex === 6 || $day === $daysInMonth) {
                $weeks[] = $week;
                $week = array_fill(0, 7, null);
            }
        }
        
        return [
            'weeks' => $weeks,
            'month' => $date->format('F'),
            'year' => $date->year,
        ];
    }
}
