<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Joystick Admin</title>
    <meta name="description" content="Joystick Admin">
    <meta name="author" content="issayev.adilet@gmail.com">
    <link rel="icon" href="/joystick/favicon.png" type="image/x-icon" />
    <link rel="shortcut icon" href="/joystick/favicon.png" type="image/x-icon" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/joystick/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/joystick/css/admin.css" rel="stylesheet">
    @yield('head')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" id="sidebarCollapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand text-uppercase" href="/{{ $lang }}/admin"><i class="material-icons text-primary">sports_esports</i> <b>Joystick</b></a>
        </div>

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <i class="material-icons md-20">person_outline</i> {{ Auth::user()->name }} <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a class="dropdown-item" href="{{ route('logout', $lang) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Выйти') }} </a>
                  <form id="logout-form" action="{{ route('logout', $lang) }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-7 col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            @can('viewAny', App\Models\Page::class)<li @if(Request::is($lang.'/admin/pages*')) class="active" @endif><a href="/{{ $lang }}/admin/pages"><i class="material-icons md-20">content_copy</i> Страницы</a></li>@endcan
            @can('viewAny', App\Models\Post::class)<li @if(Request::is($lang.'/admin/posts*')) class="active" @endif><a href="/{{ $lang }}/admin/posts"><i class="material-icons md-20">create</i> Новости</a></li>@endcan
            @can('viewAny', App\Models\Section::class)<li @if(Request::is($lang.'/admin/sections*')) class="active" @endif> <a href="/{{ $lang }}/admin/sections"><i class="material-icons md-20">dashboard</i> Разделы</a> </li>@endcan
            @can('viewAny', App\Models\Category::class)<li @if(Request::is($lang.'/admin/categories*')) class="active" @endif><a href="/{{ $lang }}/admin/categories"><i class="material-icons md-20">category</i> Категории</a></li>@endcan
            @can('viewAny', App\Models\Project::class)<li @if(Request::is($lang.'/admin/projects*')) class="active" @endif><a href="/{{ $lang }}/admin/projects"><i class="material-icons md-20">apps</i> Проекты</a></li>@endcan
            @can('viewAny', App\Models\Product::class)<li @if(Request::is($lang.'/admin/products*')) class="active" @endif><a href="/{{ $lang }}/admin/products"><i class="material-icons md-20">store</i> Продукты</a></li>@endcan
            @can('allow-filemanager', Auth::user())<li @if(Request::is($lang.'/admin/frame-filemanager*')) class="active" @endif><a href="/{{ $lang }}/admin/frame-filemanager"><i class="material-icons md-20">folder</i> Файловый менеджер</a></li>@endcan
            @can('viewAny', App\Models\Banner::class)<li @if(Request::is($lang.'/admin/banners*')) class="active" @endif><a href="/{{ $lang }}/admin/banners"><i class="material-icons md-20">collections</i> Баннеры</a></li>@endcan
            @can('viewAny', App\Models\Mode::class)<li @if(Request::is($lang.'/admin/modes*')) class="active" @endif><a href="/{{ $lang }}/admin/modes"><i class="material-icons md-20">style</i> Режимы</a></li>@endcan
            @can('viewAny', App\Models\Option::class)<li @if(Request::is($lang.'/admin/options*')) class="active" @endif><a href="/{{ $lang }}/admin/options"><i class="material-icons md-20">label_outline</i> Опции</a></li>@endcan
            @can('viewAny', App\Models\Order::class)<li @if(Request::is($lang.'/admin/orders*')) class="active" @endif><a href="/{{ $lang }}/admin/orders"><i class="material-icons md-20">shopping_cart</i> Заказы</a></li>@endcan
            @can('viewAny', App\Models\App::class)<li @if(Request::is($lang.'/admin/apps*')) class="active" @endif><a href="/{{ $lang }}/admin/apps"><i class="material-icons md-20">send</i> Заявки</a></li>@endcan
          </ul>
          <ul class="nav nav-sidebar">
            <li @if(Request::is($lang.'/admin/office*')) class="active" @endif><a href="/{{ $lang }}/admin/office"><i class="material-icons md-20">apartment</i> Офис</a></li>
            <li @if(Request::is($lang.'/admin/storages*')) class="active" @endif><a href="/{{ $lang }}/admin/storages"><i class="material-icons md-20">warehouse</i> Склады</a></li>
            <li @if(Request::is($lang.'/admin/cashbooks*')) class="active" @endif><a href="/{{ $lang }}/admin/cashbooks"><i class="material-icons md-20">account_balance</i> Кассы</a></li>
            <li @if(Request::is($lang.'/admin/workplaces*')) class="active" @endif><a href="/{{ $lang }}/admin/workplaces"><i class="material-icons md-20">workspaces</i> Рабочие места</a></li>
            <li @if(Request::is($lang.'/admin/bank_accounts*')) class="active" @endif><a href="/{{ $lang }}/admin/bank_accounts"><i class="material-icons md-20">account_balance_wallet</i> Счета</a></li>
            <li @if(Request::is($lang.'/admin/payment_types*')) class="active" @endif><a href="/{{ $lang }}/admin/payment_types"><i class="material-icons md-20">payments</i> Виды оплаты</a></li>
            <li @if(Request::is($lang.'/admin/doc_types*')) class="active" @endif><a href="/{{ $lang }}/admin/doc_types"><i class="material-icons md-20">description</i> Виды документов</a></li>
            <li @if(Request::is($lang.'/admin/units*')) class="active" @endif><a href="/{{ $lang }}/admin/units"><i class="material-icons md-20">balance</i> Единицы измерения</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            @can('viewAny', App\Models\Company::class)<li @if(Request::is($lang.'/admin/companies*')) class="active" @endif><a href="/{{ $lang }}/admin/companies"><i class="material-icons md-20">business</i> Компании</a></li>@endcan
            @can('viewAny', App\Models\Currency::class)<li @if(Request::is($lang.'/admin/currencies*')) class="active" @endif><a href="/{{ $lang }}/admin/currencies"><i class="material-icons md-20">attach_money</i> Валюты</a></li>@endcan
            @can('viewAny', App\Models\Region::class)<li @if(Request::is($lang.'/admin/regions*')) class="active" @endif><a href="/{{ $lang }}/admin/regions"><i class="material-icons md-20">map</i> Регионы</a></li>@endcan
            @can('viewAny', App\Models\Language::class)<li @if(Request::is($lang.'/admin/languages*')) class="active" @endif><a href="/{{ $lang }}/admin/languages"><i class="material-icons md-20">language</i> Языки</a></li>@endcan
            @can('viewAny', App\Models\User::class)<li @if(Request::is($lang.'/admin/users*')) class="active" @endif><a href="/{{ $lang }}/admin/users"><i class="material-icons md-20">people_outline</i> Пользователи</a></li>@endcan
            @can('viewAny', App\Models\Role::class)<li @if(Request::is($lang.'/admin/roles*')) class="active" @endif><a href="/{{ $lang }}/admin/roles"><i class="material-icons md-20">accessibility</i> Роли</a></li>@endcan
            @can('viewAny', App\Models\Permission::class)<li @if(Request::is($lang.'/admin/permissions*')) class="active" @endif><a href="/{{ $lang }}/admin/permissions"><i class="material-icons md-20">lock_open</i> Права доступа</a></li>@endcan
          </ul>
          <ul class="nav nav-sidebar">
            <li>
              <a href="{{ route('logout', $lang) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons md-20">exit_to_app</i> Выйти</a>
            </li>
          </ul>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          @yield('content')
        </div>
      </div>
    </div>

    <script src="/joystick/js/jquery.min.js"></script>
    <script src="/joystick/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
          $('.sidebar').toggleClass('active');
        });
        $('.main').on('click', function () {
          $('.sidebar').removeClass('active');
        });
      });
    </script>
    @yield('scripts')
  </body>
</html>
