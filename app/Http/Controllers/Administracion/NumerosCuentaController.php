<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\NumerosCuenta;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\Monedas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class NumerosCuentaController extends Controller
{
    public function __construct(NumerosCuenta $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
        $this->companies = Empresas::all();
        $this->banks = Bancos::all();
        $this->coins = Monedas::all();
    }

    public function index($company)
    {
        // $this->authorize('view', $this->entity);
        Logs::createLog($this->entity->getTable(),$company,null,'index',null);

        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->all()->where('eliminar', '=','0'),
            'companies' => $this->companies,
            'banks' => $this->banks,
            'coins' => $this->coins,

        ]);
    }

    public function create($company)
    {
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'companies' => $this->companies,
            'banks' => $this->banks,
            'coins' => $this->coins,
        ]);
    }

    public function store(Request $request, $company)
    {
        //dd($request->all());
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $created = $this->entity->create($request->all());
        if($created)
        {Logs::createLog($this->entity->getTable(),$company,$created->id_numero_cuenta,'crear','Registro insertado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al insertar');}

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    public function show($company, $id)
    {
        Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);

        $bank = $this->entity->findOrFail($id)->fk_id_banco;
        $empresa = $this->entity->findOrFail($id)->fk_id_empresa;
        $coin = $this->entity->findOrFail($id)->fk_id_sat_moneda;

        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
            'bank' => $this->banks->find($bank)->banco,
            'company_owner' => $this->companies->find($empresa)->nombre_comercial,
            'coin' => $this->coins->find($coin)->moneda,
        ]);
    }

    public function edit($company, $id)
    {
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
            'companies' => $this->companies,
            'banks' => $this->banks,
            'coins' => $this->coins,
        ]);
    }

    public function update(Request $request, $company, $id)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);
        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if($entity->save())
        {Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');}

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    public function destroy($company, $id)
    {
        $entity = $this->entity->findOrFail($id);
        $entity->eliminar='t';
        if($entity->save())
        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al eliminar');}

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }
}
