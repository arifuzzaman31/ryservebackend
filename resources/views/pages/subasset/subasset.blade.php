@extends('layout.app')
@section('title', 'Sub Asset | Ryserve')
@push('style')
<link rel="stylesheet" href="{{ asset('admin-assets/assets/css/multiselect.css')}}">
@endpush
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 15px 0;">
    <div class="statbox">
        <div class="widget-header">
            <view-subasset />
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/business.js') }}"></script>
@endpush
