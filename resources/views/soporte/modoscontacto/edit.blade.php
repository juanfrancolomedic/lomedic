@extends('layouts.dashboard')

@section('title', 'Editar')

@section('header-top')
@endsection

@section('header-bottom')
@endsection

@section('content')
<form action="{{ companyRoute('update') }}" method="post" class="col s12">
	{{ csrf_field() }}
	{{ method_field('PUT') }}
	<div class="col s12 xl8 offset-xl2">
		<div class="row">
			<div class="right">
				<button type="submit" class="waves-effect btn orange">Guardar y salir</button>
				<a href="{{ url()->previous() }}" class="waves-effect waves-teal btn-flat teal-text">Cancelar y salir</a>
			</div>
		</div>
	</div>
	<div class="col s12 xl8 offset-xl2">
		<h5>Editar Modo de contacto</h5>
		<div class="row">
			<div class="input-field col s12 m5">
				<input type="text" name="modo_contacto" id="modo_contacto" class="validate" value="{{ $data->modo_contacto}}">
				<label for="modo_contacto">Modo de contacto</label>
				@if ($errors->has('modo_contacto'))
					<span class="help-block">
						<strong>{{ $errors->first('modo_contacto') }}</strong>
					</span>
				@endif
			</div>
			<div class="input-field col s12 m7">
				<input type="hidden" name="activo" value="0">
				<input type="checkbox" id="activo" name="activo" {{$data->activo ? 'checked' : ''}} />
				<label for="activo">Estatus:</label>
				@if ($errors->has('activo'))
					<span class="help-block">
						<strong>{{ $errors->first('activo') }}</strong>
					</span>
				@endif
			</div>
		</div>
	</div>
</form>
@endsection