@extends('layout.app')
@section('title', 'Role | Ryserved')
@section('content')
<div id="tableHover" class="col-lg-12 col-12 layout-spacing" style="padding: 25px 0;">
    <view-role />
</div>

@endsection

@push('script')
<script src="{{ asset('js/app.js') }}"></script>
@endpush
