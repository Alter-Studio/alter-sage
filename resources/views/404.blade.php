@extends('layouts.app')

@section('content')
  @if (!have_posts())
	<div class="error">
	  <div class="container">
		<div class="error-message">
			<p class="large">@trans('not_found_message')</p>
			<a class="zoom left large" href="{{ home_url() }}">@trans('not_found_link_text')</a>
		</div>
	  </div>
	</div>
  @endif
@endsection
