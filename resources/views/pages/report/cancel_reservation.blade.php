@extends('layout.app')
@section('title', 'Canceled Reservation | Reserve')
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <cancel-reserve />
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/report.js') }}"></script>
@endpush
