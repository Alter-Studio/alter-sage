<header class="header">
    <a class="brand" href="{{ home_url('/') }}">{{ get_bloginfo('name', 'display') }}</a>
    <nav class="nav-primary">
      @if (has_nav_menu('primary_navigation'))
      	{{ App\bem_menu('primary_navigation', 'menu', 'menu--ul') }}
      @endif
    </nav>
</header>
