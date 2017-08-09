<!DOCTYPE html>
<html lang="es">
<head>
    <!--meta para caracteres especiales-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <!--<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
    <!--estilo css personal-->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}"  media="screen,projection"/>
    @yield('header-top')
</head>

<?php
use App\Menu;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

$Menu = New Menu();
// $MainMenu =  $Menu->getMenu();
$MainMenu =  '';

$Url = URL('/');
$Usuario = Session::get('usuario');
$Company = request()->company;

$QueryUser =  DB::table('ges_cat_usuarios')->where('usuario','=',$Usuario)->get()->toarray();
$QueryCompany =  DB::table('gen_cat_empresas')->where('conexion','=',$Company)->get()->toarray();


$QueryOterCompany =  DB::table('gen_cat_empresas')->where('conexion','<>',$Company)->get()->toarray();



$NombreUsuario = !empty($QueryUser[0]->nombre_corto) ? $QueryUser[0]->nombre_corto : '<\ Usuario >';
$NombreEmpresa = !empty($QueryCompany[0]->nombre_comercial) ? $QueryCompany[0]->nombre_comercial : '<\ Empresa >';
$LogoEmpresa = !empty($QueryCompany[0]->logotipo) ? $QueryCompany[0]->logotipo : 'twitch-logo.png';

$Color = !empty($QueryCompany[0]->color) ? $QueryCompany[0]->color : 'teal';
?>
<body class="grey lighten-3">

<div class="navbar-fixed ">
    <nav class="top-nav {{ $Color }} z-depth-0 nav-extended">
        <div class="nav-wrapper">
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
            <a href="#!" data-activates="enteDrop" data-beloworigin="true" class="brand-logo dropdown-button logo-enterprise">
                <img src="/img/{{ $LogoEmpresa }}" alt="Sesión activa" class="circle responsive-img"> {{ $NombreEmpresa }}
            </a>
            <ul class="right">
                <li><a href="#" data-activates="slide-help" class="help-collapse"><i class="material-icons">live_help</i></a></li>
                <li><a class="dropdown-button" href="#!" data-activates="notDrop" data-beloworigin="true"><i class="material-icons">add_alert</i></a></li>
            </ul>
        </div>
    </nav>
</div>

<ul id="slide-out" class="side-nav">
    <li>
        <div class="user-view center">
            <object id="front-page-logo" class="Sim" type="image/svg+xml" data="img/sim2.svg" name="SIM">Your browser does not support SVG</object>
            <div class="background">
                <img src="img/userBG2.jpg">
            </div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="white-text name">
                    <i class="tiny material-icons">power_settings_new</i> CERRAR SESIÓN
                </span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            <a href="#"><span class="white-text name">{{ $NombreUsuario }}</span></a>
            <!-- <a href="#"><span class="white-text">Facturación</span></a> -->
        </div>
    </li>
    <!--enteprises dropdown-->
    <ul id='enteDrop' class='dropdown-content'>
        @foreach($QueryOterCompany as $Companys)
            <li><a target="_blank" href="{{ $Url }}/{{ $Companys->conexion }}"><i class="material-icons">business</i>{{ $Companys->nombre_comercial }}</a></li>
        @endforeach
    </ul>
    <!--notifications dropdown-->
    <ul id="notDrop" class="dropdown-content noti-content collection">
            <li class="collection-item">
                    <p>Notification message</p>
            </li>
            <li class="collection-item">
                    <p>Notification message</p>
            </li>
            <li class="collection-item">
                    <p>Notification message</p>
            </li>
    </ul><!--/dropdown-->
                <ul id='main-menu'>
                    @each('partials.menu', $menu, 'modulo')
                </ul>
            </ul><!--/slide-out, principal nav-->
            <ul id="slide-help" class="side-nav">
                <li>
                <div class="user-view center">
                    <span><i class="material-icons medium white-text">live_help</i></span>
                    <div class="background">
                        <img src="img/helpBG2.png">
                    </div>
                    <a class="white-text" href="#"><span class="name">Sección de ayuda</span></a>
                    <a href="#ticketHelp" class="waves-effect waves-light btn-flat white-text">Crear ticket/solicitud</a>
                    <a href="{{ companyRoute('Soporte\SolicitudesController@index', ['company'=> $Company]) }}" class="waves-effect waves-light btn-flat white-text">Ver mis tickets/solicitudes</a>
                </div>
                </li>
                <li><a href="#!">Proceso NAUS1234</a></li>
                <li><a href="#!">Proceso NAUS1234</a></li>
                <li><a href="#!">Proceso NAUS1234</a></li>
                <li><a href="#!">Proceso NAUS1234</a></li>
            </ul><!--/slide-help, users help-->

            <div class="row {{ $Color }}">
                <div class="col s12">
                    <a href="{{ $Url }}/{{ $Company }}" class="breadcrumb">Home</a>
                    <!-- <a href="#!" class="breadcrumb">Section</a> -->
                </div>
            </div><!--/breadcrum-->

<div class="row">
@yield('content')
</div><!--/row section-->
<form action="{{ companyRoute('Soporte\SolicitudesController@store', ['company'=> $Company]) }}" method="post" class="col s12" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('POST') }}
    <div id="ticketHelp" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Nuevo Ticket:</h4>
                <div class="input-field col s12">
                    <input type="text" id="asunto" name="asunto" class="validate" value="{{old('asunto')}}">
                    <label for="asunto">Asunto</label>
                </div>
            <div class="row">
                <div class="input-field col s12">
                    <p class="col s6">
                        <input name="groupWho" type="radio" id="forMe1" onclick="activar_empleado()" checked
                               data-url="{{companyRoute('RecursosHumanos\EmpleadosController@obtenerEmpleado')}}"/>
                        <label for="forMe1">El ticket es para mí</label>
                    </p>
                    <p class="col s6">
                        <input name="groupWho" type="radio" id="otherUser" onchange="activar_empleado()"/>
                        <label for="otherUser">Para otra persona</label>
                    </p>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="empleado_solicitud" class="autocomplete_empleado"
                           data-url="{{companyRoute('RecursosHumanos\EmpleadosController@obtenerEmpleados')}}" autocomplete="off">
                    <label for="empleado_solicitud">Usuario</label>
                    <input type="hidden" id="nombre_solicitante" name="nombre_solicitante" value="">
                </div>
                <div class="input-field col s12">
                    <input type="text" id="sucursal" class="autocomplete_sucursal"
                           data-url="{{companyRoute('Administracion\SucursalesController@obtenerSucursales')}}" autocomplete="off">
                    <label for="sucursal">Sucursal</label>
                    <input type="hidden" id="fk_id_sucursal" name="fk_id_sucursal" value="">
                </div>
                <div class="input-field col s4">
                    <select name="fk_id_categoria" id="fk_id_categoria">
                        <option selected disabled>Selecciona una categoría</option>
                        @foreach($categories_tickets as $category_ticket)
                            <option value="{{$category_ticket->id_categoria}}"
                                    data-url="{{companyRoute('Soporte\SolicitudesController@obtenerSubcategorias'
                                    ,['id' => $category_ticket->id_categoria])}}">
                                {{$category_ticket->categoria}}
                            </option>
                        @endforeach
                    </select>
                    <label for="fk_id_categoria">Categoría</label>
                </div>
                <div class="input-field col s4">
                    <select name="fk_id_subcategoria" id="fk_id_subcategoria" disabled>
                    </select>
                    <label>Subcategoría</label>
                </div>
                <div class="input-field col s4">
                    <select name="fk_id_accion" id="fk_id_accion" disabled>
                    </select>
                    <label>Acción</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="descripcion" name="descripcion" class="materialize-textarea"></textarea>
                    <label for="descripcion">Descripción</label>
                </div>
                <div class="file-field input-field col s12">
                    <div class="btn">
                        <span><i class="material-icons">file_upload</i>Anexar pruebas</span>
                        <input type="file" name="archivo[]" id="archivo" multiple>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload one or more files">
                    </div>
                </div>
                <div class="input-field col s12">
                    <p>Prioridad:</p>
                    @foreach($priorities_tickets as $priority_ticket)
                        <p class="col s4">
                            <input name="fk_id_prioridad" type="radio" value="{{$priority_ticket->id_prioridad}}"
                                   id="prioridad{{$priority_ticket->id_prioridad}}"/>
                            <label for="prioridad{{$priority_ticket->id_prioridad}}">{{$priority_ticket->prioridad}}</label>
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-action waves-effect waves-light btn orange">Aceptar</button>
            {{--<button class="modal-action modal-close waves-effect teal-text btn-flat">Cancelar</button>--}}
        </div>
    </div><!--/Modal de ayuda-->
</form>
	<!--Import jQuery before materialize.js-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

	@yield('header-bottom')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/InitiateComponents.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.ui.autocomplete2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ticket.js') }}"></script>

</body>
</html>