@extends('layouts.app')

@section('title', 'Kalender Jatuh Tempo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
<li class="breadcrumb-item active">Kalender Jatuh Tempo</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Kalender Jatuh Tempo Dokumen</h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-danger"><i class="fas fa-circle me-1"></i>Terlambat</span>
                        <span class="badge bg-warning"><i class="fas fa-circle me-1"></i>3 Hari Lagi</span>
                        <span class="badge bg-primary"><i class="fas fa-circle me-1"></i>Normal</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
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
        moreLinkClick: 'popover'
    });
    calendar.render();
});
</script>
@endpush
