<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::orderBy('catNombre')->paginate(3);
        return view('/categorias', ['categorias'=>$categorias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/categoriaCreate');
    }

    /*Función de validación de Marcas */
    private function validarCategoria(Request $request){
        $request->validate(
            [
            'catNombre'=>'required|min:5|max:30'
            ],
            [
            'catNombre.required'=>'Este campo es requerido.',
            'catNombre.min'=>'La categoría debe tener al menos 5 caracteres.',
            'catNombre.max'=>'La categoría no debe exceder de los 30 caracteres.'
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
        $catNombre = $request->catNombre;
        $this->validarCategoria($request);
        $Categoria = new Categoria;
        $Categoria->catNombre = $catNombre;
        $Categoria->save();
            return redirect('/categorias')
                    ->with(['mensaje'=>'La categoria '.$catNombre.' ha sido agregada correctamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Categoria = Categoria::find($id);
        return view('/categoriaEdit', ['Categoria'=>$Categoria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $catNombre = $request->catNombre;
        $this->validarCategoria($request);

        $Categoria = Categoria::find($request->idCategoria);
        $Categoria->catNombre = $catNombre;
        $Categoria->save();

            return redirect('/categorias')
                    ->with(['mensaje'=>'La categoría '.$catNombre.' ha sido modificada.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */

    private function productoPorCategoria($id){
        $check = Producto::where('idCategoria', $id)->first();
            return $check;
    }

    public function confirm($id){
        $Categoria = Categoria::find($id);
        if(!$this->productoPorCategoria($id)){
            return view('categoriaDelete', ['Categoria'=>$Categoria]);
        }

        return redirect('/categorias')
                ->with(['mensaje'=>'La categoria '.$Categoria->catNombre.' tiene productos asignados a ella.']);
    }

    public function destroy(Request $request)
    {
        Categoria::destroy($request->idCategoria);
            return redirect('/categorias')
                ->with(['mensaje'=>'La categoría '.$request->catNombre.' ha sido eliminada.']);
    }
}
