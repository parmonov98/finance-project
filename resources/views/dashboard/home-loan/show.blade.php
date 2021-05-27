@extends('layouts.app')
@section('content')
@livewire('home-loans')
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('lib/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}"></script>

<script>
    $(function() {
        $('.dates #usr1').datepicker({
            'format': 'dd-mm-yyyy',
            'autoclose': true
        });
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" type="text/css" href="lib/bootstrap-datepicker.css" >
  <link rel="stylesheet" href="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css') }}">

@endpush