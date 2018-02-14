<!doctype html>
<html @php(language_attributes())>
  @include('partials.head')
  <body>
    @php
      $svg_file = file_get_contents(App\asset_path('sprites.svg'));
      echo $svg_file;
    @endphp
    @php(do_action('get_header'))
    @include('partials.header')
    @yield('page-header')
    <div id="main">
      <div class="wrapper" data-namespace="{!! App::getTemplate() !!}" role="document">
        <div class="content">
          <main class="main">
            @yield('content')
          </main>
        </div>
      </div>
    </div>
    @php(do_action('get_footer'))
    @include('partials.footer')
    @php(wp_footer())
  </body>
</html>
