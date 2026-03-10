@extends('layouts.app')

@section('title', 'Kalender Jatuh Tempo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Kalender Jatuh Tempo</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kalender Jatuh Tempo</h1>
        <p class="page-subtitle">Lihat jadwal jatuh tempo dokumen peminjaman</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge bg-danger d-flex align-items-center px-3 py-2"><i class="fas fa-circle me-2" style="font-size: 8px;"></i>Terlambat</span>
        <span class="badge bg-warning d-flex align-items-center px-3 py-2"><i class="fas fa-circle me-2" style="font-size: 8px;"></i>3 Hari Lagi</span>
        <span class="badge bg-primary d-flex align-items-center px-3 py-2"><i class="fas fa-circle me-2" style="font-size: 8px;"></i>Normal</span>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
<style>
.fc {
    font-family: inherit;
}
.fc .fc-toolbar-title {
    font-size: 1.25rem;
    font-weight: 600;
}
.fc .fc-button {
    background-color: var(--primary);
    border-color: var(--primary);
    padding: 0.4rem 1rem;
    font-size: 0.875rem;
}
.fc .fc-button:hover {
    background-color: var(--primary-dark, #1a6f8a);
    border-color: var(--primary-dark, #1a6f8a);
}
.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active {
    background-color: var(--primary-dark, #1a6f8a);
    border-color: var(--primary-dark, #1a6f8a);
}
.fc .fc-daygrid-day-number,
.fc .fc-col-header-cell-cushion {
    color: var(--text-primary);
    text-decoration: none;
}
.fc .fc-daygrid-day.fc-day-today {
    background-color: rgba(26, 111, 138, 0.1);
}
.fc-event {
    cursor: pointer;
    border: none;
    padding: 2px 6px;
    font-size: 0.8rem;
}
.fc-event:hover {
    opacity: 0.9;
}
.fc-daygrid-event-dot {
    display: none;
}
.fc .fc-popover {
    background-color: var(--bg-card);
    border: 1px solid var(--border-color);
}
.fc .fc-more-popover .fc-popover-body {
    background-color: var(--bg-card);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: @json($events),
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        locale: 'id',
        height: 'auto',
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu',
            list: 'Daftar'
        },
        dayMaxEvents: 3,
        moreLinkClick: 'popover',
        eventDisplay: 'block',
        nowIndicator: true,
        selectable: true
    });
    calendar.render();
});
</script>
@endpush
