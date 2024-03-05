@extends('layout.app')
@section('title', 'Amenities | Ryserve')

@section('content')

<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <view-amenities />
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/business.js') }}"></script>
@endpush
