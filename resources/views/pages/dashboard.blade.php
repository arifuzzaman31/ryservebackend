@extends('layout.app')
@push('style')
<link href="{{ asset('admin-assets/assets/css/components/tabs-accordian/custom-tabs.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="row">
    <div class="col-12 layout-spacing">
        <view-dashboard />
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('js/app.js') }}"></script>
@endpush
