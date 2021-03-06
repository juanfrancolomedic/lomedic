<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class MetodosPago extends Model
{
    // use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_metodos_pago';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_metodo_pago';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['metodo_pago', 'descripcion', 'activo'];

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
	public $rules = [
		'metodo_pago'	=> 'required',
		'descripcion'	=> 'required',
		// 'activo'		=> 'required',
	];


	/**
	 * @return field name of table
	 */
	public function getTable(){
	    return $this->table;
    }

}
