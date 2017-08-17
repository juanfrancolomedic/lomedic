<?php

namespace App\Http\Models\Administracion;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use App\Http\Models\ModelBase;

class Usuarios extends Authenticatable
{
    use Notifiable;

	/*
	const CREATED_AT = 'fecha_crea';
	const UPDATED_AT = 'fecha_actualiza';
	*/

	public $timestamps = false;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ges_cat_usuarios';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_usuario';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'usuario', 'nombre_corto','activo','password','activo'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public $rules = [
		'nombre_corto' => 'required',
		'usuario' => 'required',
	];

	public function mails(){
		return $this->hasMany('app\Http\Models\Correos');
	}

	public function branches(){
		return $this->belongsToMany('app\Http\Models\Sucursales','ges_det_usuario_sucursal','fk_id_usuario','fk_id_sucursal');
	}

	/**
	 * Obtenemos perfiles asignados al usuario
	 * @return array
	 */
	public function perfiles(){
		return $this->belongsToMany(Perfiles::class, 'ges_det_perfiles_usuarios', 'fk_id_usuario', 'fk_id_perfil');
	}

	/**
	 * Obtenemos permisos asignados al usuario
	 * @return array
	 */
	public function permisos()
	{
		$permisos = new Collection();
		# Obtenemos permisos relacionados a perfiles del usuario
		foreach ($this->perfiles as $perfil) {
			$permisos = $permisos->merge( $perfil->permisos);
		}
		# Anexamos permisos relacionados al usuario
		return $permisos->merge($this->belongsToMany(Permisos::class, 'ges_det_permisos_usuarios', 'fk_id_usuario', 'fk_id_permiso')->getResults());
	}

	/**
	 * Obtenemos modulos a los que tiene acceso el usuario por medio de sus permisos
	 * @return array
	 */
	public function modulos($empresa = null)
	{
		# Obtenemos modulos en base a ...
		return Modulos::whereHas('permisos', function($q) {
			# Modulos relacionados a los permisos del usuario
			$q->whereIn('id_permiso', $this->permisos()->pluck('id_permiso') );
		})->get();
	}

	/**
	 * Obtenemos modulos anidados a los que tiene acceso el usuario por medio de sus permisos
	 * @return array
	 */
	public function modulos_anidados()
	{
		return $this->__modulos();
	}

	/**
	 * Obtenemos modulos anidados a los que tiene acceso el usuario por medio de sus permisos
	 * @param  Empresa $empresa
	 * @return array
	 */
	private function __modulos($empresa = null)
	{
		$empresa = $empresa ?: Empresas::where('conexion', request()->company)->first();
		$modulos_empresa = method_exists($empresa, 'modulos_anidados') ? $empresa->modulos_anidados() : $empresa;

		# Obtenemos modulos en base a ...
		$modulos_usuario = Modulos::whereHas('permisos', function($q) {
			# Modulos relacionados a los permisos del usuario
			$q->whereIn('id_permiso', $this->permisos()->pluck('id_permiso') );
		})->get();

		# Recorremos modulos de la empresa
		foreach ($modulos_empresa as $key => $modulo) {
			# Si usuario tiene acceso a modulo
			if (in_array($modulo->id_modulo, $modulos_usuario->pluck('id_modulo')->toArray() )) {
				# Mismo proceso a submodulos
				$modulo->submodulos = $this->__modulos($modulo->submodulos);
			} else {
				# Eliminamos modulo de coleccion
				$modulos_empresa->forget($key);
			}
		}

		return $modulos_empresa;
	}

}