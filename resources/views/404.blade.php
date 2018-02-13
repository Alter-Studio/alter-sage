@extends('layouts.app')

@section('content')
  @if (!have_posts())
	<div class="error">
	  <div class="container">
		<div class="error-message">
			<a class="zoom left large" href="{{ home_url() }}">404 Error</a>
		</div>
	  </div>
	</div>
  @endif
@endsection
