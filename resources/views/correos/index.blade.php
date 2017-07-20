<?php
use App\Menu;
$Barra = New Menu();
$Acciones = $Barra->getBarra(47);
?>
@extends('layouts.dashboard')

@section('title', 'correos')

@section('header-top')
	<!--dataTable.css-->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
@endsection

@section('header-bottom')
	<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
	<script src="{{ asset('js/correos.js') }}"></script>
	<!-- <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/r-2.1.0/datatables.min.js"></script> -->
@endsection

@section('content')
<div class="col s12 xl8 offset-xl2">
	<p class="right">
	<a href="{{ route("$entity.create", ['company'=> $company]) }}" class="waves-effect waves-light btn"><i class="material-icons right">add</i>Nuevo {{ trans_choice('messages.'.$entity, 0) }}</a> <br>
	</p>
</div>
@if (session('success'))
<div class="col s12 xl8 offset-xl2">
	<div class="alert alert-success">
		{{ session('success') }}
	</div>
</div>
@endif
<div class="col s12 xl8 offset-xl2">
	<table class="striped responsive-table highlight">
		<thead>
			<tr>
				<th>Correos</th>
				<th>Empresa</th>
				<th>Usuario</th>
				<th>Activo</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@foreach ($data as $correo)
		<tr>
			<td>{{ $correo->correo}}</td>
			<td>
				<p>
					<label for="fk_id_empresa"></label>
					<input type="text" id="fk_id_empresa" name="fk_id_empresa" disabled checked="{{ $correo->fk_id_empresa}}">
				</p>
			</td>
			<td>
				@foreach($users as $user)
					@if($correo->fk_id_usuario==$user->id_usuario)
						{{$user->usuario}}
					@endif
				@endforeach
			</td>
			<td>
				<p>
					<input type="checkbox" id="activo" name="activo" disabled checked="{{ $correo->activo}}">
					<label for="activo"></label>
				</p>
			</td>
			<td class="width-auto">
				<a href="{{ route("$entity.show", ['id' => $correo->id_correo, 'company'=> $company]) }}" class="waves-effect waves-light btn "><i class="material-icons">visibility</i></a>
				<a href="{{ route("$entity.edit", ['id' => $correo->id_correo, 'company'=> $company]) }}" class="waves-effect waves-light btn "><i class="material-icons">mode_edit</i></a>
				<a href="#" class="waves-effect waves-light btn" onclick="event.preventDefault(); document.getElementById('delete-form{{$correo->id_correo}}').submit();"><i class="material-icons">delete</i></a>
				<form id="delete-form{{$correo->id_correo}}" action="{{ route("$entity.destroy", ['id' => $correo->id_correo, 'company'=> $company]) }}" method="POST" style="display: none;">
					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				</form>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
@endsection