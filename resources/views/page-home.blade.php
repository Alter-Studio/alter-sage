<?php /* Template Name: Home */ ?>
@extends('layouts.app')

@section('content')
  <div class="container">
	{{ get_the_title() }}
		<div style="max-width: 100%">
			<div class="inner">

				{{-- Basic Responsive Image | One Ratio --}}
				<div class="img--responsive" style="padding-bottom: {{ get_field('image')['sizes']['large-ratio'] }}%" >
					<img class="lazyload" 
						data-sizes="auto" 
						data-src="
							{{ get_field('image')['sizes']['large'] }}
						"
					/>
				</div>

			</div>
		</div>
  </div>
@endsection
