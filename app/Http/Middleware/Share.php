<?php

namespace App\Http\Middleware;

use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Soporte\Categorias;
use App\Http\Models\Soporte\Prioridades;
use App\Http\Models\Soporte\Solicitudes;
use App\Http\Models\Soporte\Subcategorias;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Share
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		# Compartimos empresa
		View::share('empresa', Empresas::where('conexion', request()->company)->first());

		# Compartimos otras empresas
		View::share('empresas', Empresas::where('conexion', '!=', request()->company)->get());

		#Las categorías utilizadas en el ticket. Esto es para inyectar la opción vacía
		$categoria[0] = 'Selecciona una categoría';
		$categoria = $categoria + Categorias::all()->pluck('categoria','id_categoria')->toArray();

		# Compartimos modulos de usuario para generar menu
		View::share('menu', Auth::user()->modulos_anidados());
		View::share('employees_tickets', Empleados::all());
		View::share('categories_tickets', $categoria);
		View::share('priorities_tickets', Prioridades::all());
		View::share('ultimos_tickets',Solicitudes::all()->where('fk_id_empleado_solicitud',Auth::id())
            ->where('fecha_hora_resolucion',null)->take(5));

		return $next($request);
	}
}