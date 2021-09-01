@extends('layouts.app')

@prepend('css')
<link rel="stylesheet" href="extensions/sticky-header/bootstrap-table-sticky-header.css">
<link rel="stylesheet" href="extensions/fixed-columns/bootstrap-table-fixed-columns.css">
@endprepend

@section('content')

@livewire('monthly-networths')
@endsection

@push('scripts')
<script src="extensions/sticky-header/bootstrap-table-sticky-header.js"></script>
<script src="extensions/fixed-columns/bootstrap-table-fixed-columns.js"></script>
@endpush
