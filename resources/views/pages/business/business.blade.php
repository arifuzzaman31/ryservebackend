@extends('layout.app')
@section('title', 'Business | Ryserve')

@section('content')

<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            {{-- <create-business /> --}}
            <view-business />
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/business.js') }}"></script>
@endpush
