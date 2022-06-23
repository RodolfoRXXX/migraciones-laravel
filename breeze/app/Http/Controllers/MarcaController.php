<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = Marca::orderBy('mkNombre')->paginate(3);
        return view('marcas', ['marcas'=>$marcas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/marcaCreate');
    }

    /*Función de validación de Marcas */
    private function validarMarca(Request $request){
        $request->validate(
            [
            'mkNombre'=>'required|min:2|max:50'
            ],
            [
            'mkNombre.required'=>'Este campo es requerido.',
            'mkNombre.min'=>'La marca debe tener al menos 2 caracteres.',
            'mkNombre.max'=>'La marca no debe exceder de los 50 caracteres.'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mkNombre = $request->mkNombre;
        $this->validarMarca($request);
        $Marca = new Marca;
        $Marca->mkNombre = $mkNombre;
        $Marca->save();
            return redirect('/marcas')
                    ->with(['mensaje'=>'La marca '.$mkNombre.' ha sido agregada correctamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show(Marca $marca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        $Marca = Marca::find($id);
        
        return view('/marcaEdit', ['Marca'=>$Marca]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $mkNombre = $request->mkNombre;
        $this->validarMarca($request);

        $Marca = Marca::find($request->idMarca);
        $Marca->mkNombre = $mkNombre;
        $Marca->save();

            return redirect('/marcas')
                    ->with(['mensaje'=>'La marca '.$mkNombre.' ha sido modificada.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    private function productoPorMarca($id){
        $check = Producto::where('idMarca', $id)->first();
            return $check;
    }

    public function confirm($id){
        $Marca = Marca::find($id);
        if(!$this->productoPorMarca($id)){
            return view('marcaDelete', ['Marca'=>$Marca]);
        }

        return redirect('/marcas')
                ->with(['mensaje'=>'La marca '.$Marca->mkNombre.' tiene productos asignados a ella.']);
    }

    public function destroy(Request $request)
    {
        Marca::destroy($request->idMarca);
            return redirect('/marcas')
                ->with(['mensaje'=>'La marca '.$request->mkNombre.' ha sido eliminada.']);
    }
}
