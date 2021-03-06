<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Tipocombustible extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_tipo_combustible';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_combustible';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['combustible', 'activo'];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = ['combustible' => 'required'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	  'combustible' => 'Combustible',
	];

}
