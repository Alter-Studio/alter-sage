<?php /* Template Name: Home */ ?>
@extends('layouts.app')

@section('content')
  <div class="container">
	{{ get_the_title() }}
  </div>
@endsection
