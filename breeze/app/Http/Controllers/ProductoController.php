<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::orderBy('prdNombre')->paginate(5);
        return view('productos', ['productos'=>$productos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $marcas = Marca::all();
        $categorias = Categoria::all();

            return view('/productoCreate', ['marcas'=>$marcas,'categorias'=>$categorias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validarForm(Request $request){
        $request->validate(
                        [
                            'prdNombre'     =>'required|min:5|max:75',
                            'prdPrecio'     =>'required|numeric|min:0',
                            'idMarca'       =>'required|int',
                            'idCategoria'   =>'required|int',
                            'prdDescripcion'=>'required|min:2|max:150',
                            'prdImagen'     =>'mimes:jpg,jpeg,png|max:2048'
                        ],
                        [
                            'prdNombre.required'     =>'El campo "Nombre del producto" es obligatorio.',
                            'prdNombre.min'          =>'El campo "Nombre del producto" debe tener como mínimo 2 caractéres.',
                            'prdNombre.max'          =>'El campo "Nombre" debe tener 75 caractéres como máximo.',
                            'prdPrecio.required'     =>'Complete el campo Precio.',
                            'prdPrecio.numeric'      =>'Complete el campo Precio con un número.',
                            'prdPrecio.min'          =>'Complete el campo Precio con un número mayor a 0.',
                            'idMarca.required'       =>'Seleccione una marca.',
                            'idMarca.integer'        =>'Seleccione una marca.',
                            'idCategoria.required'   =>'Seleccione una categoría.',
                            'idCategoria.integer'    =>'Seleccione una categoría.',
                            'prdDescripcion.required'=>'Complete el campo Descripción.',
                            'prdDescripcion.min'     =>'Complete el campo Descripción con al menos 3 caractéres',
                            'prdDescripcion.max'     =>'Complete el campo Descripción con 150 caractéres como máximo.',
                            'prdImagen.mimes'        =>'Debe ser una imagen.',
                            'prdImagen.max'          =>'Debe ser una imagen de 2MB como máximo.'
                        ]
        );
    }

    private function subirImagen(Request $request){
        //si no hay imagen
        //valoriza la variable con la imagen por defecto
        $prdImagen = 'noDisponible.png';
        
        //si no se modifica la imagen cuando se modifica el producto
        if( $request->has('imgActual') ){
            $prdImagen = $request->imgActual;
        }

        //verifica que el campo tenga un archivo
        if( $request->file('prdImagen') ){
            //recupera la extensión del archivo subido
            $extension = $request->file('prdImagen')->extension();
            //renombra el archivo = cantSeg.extension
            $prdImagen = time().'.'.$extension;
            //mover el archivo a la carpeta donde se va a guardar
            $request->file('prdImagen')->move(public_path('/imagenes/productos'), $prdImagen);
        }
        return $prdImagen;
    }

    public function store(Request $request)
    {
        $this->validarForm($request);
        $prdImagen = $this->subirImagen($request);
        //instanciar
        $Producto = new Producto;

        $Producto->prdNombre      = $request->prdNombre;
        $Producto->prdPrecio      = $request->prdPrecio;
        $Producto->idMarca        = $request->idMarca;
        $Producto->idCategoria    = $request->idCategoria;
        $Producto->prdDescripcion = $request->prdDescripcion;
        $Producto->prdImagen      = $prdImagen;
        $Producto->prdActivo      = 1;

        $Producto->save();

            return redirect('productos')
                    ->with(['mensaje'=>'El producto '.$request->prdNombre.' ha sido agregado correctamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $Producto = Producto::find($id);

            return view('/productoEdit', 
                [
                    'Producto'  =>$Producto,
                    'marcas'    =>$marcas,
                    'categorias'=>$categorias
                ]
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //validación
        $this->validarForm( $request );
        //subir imagen
        $prdImagen = $this->subirImagen($request);
        //obtenemos datos del producto por su id
        $Producto = Producto::find( $request->idProducto );
        //asignación de valores nuevos
        $Producto->prdNombre      = $request->prdNombre;
        $Producto->prdPrecio      = $request->prdPrecio;
        $Producto->idMarca        = $request->idMarca;
        $Producto->idCategoria    = $request->idCategoria;
        $Producto->prdDescripcion = $request->prdDescripcion;
        $Producto->prdImagen      = $prdImagen;
        //$Producto->prdActivo      = 1;
        //guardamos
        $Producto->save();
        //redirección + mensaje ok
        return redirect('/productos')
               ->with(['mensaje'=>'El producto: '.$request->prdNombre.' ha sido modificado correctamente']);
    }

    public function confirm($id)
    {
        $Producto   = Producto::with(['getMarca', 'getCategoria'])->find($id);
        
        return view('/productoDelete', [ 'Producto'  =>$Producto ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Producto::destroy( $request->idProducto );

        return redirect('/productos')
               ->with(['mensaje'=>'El producto: '.$request->prdNombre.' ha sido eliminado correctamente']);
    }
}
