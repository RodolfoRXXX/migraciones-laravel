<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h1 class="pb-4">Modificación de un producto</h1>
    <!-- formulario -->
                    <div class="shadow-md rounded-md max-w-3xl mb-72">
                        <form action="/producto/update" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="p-6 bg-white">
                                <div class="form-group mb-4">
                                    <label for="prdNombre">Nombre del Producto</label>
                                    <input type="text" name="prdNombre"
                                        value="{{ old( 'prdNombre', $Producto->prdNombre ) }}"
                                        class="form-control" id="prdNombre">
                                </div>

                                <label for="prdPrecio">Precio del Producto</label>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">$</div>
                                    </div>
                                    <input type="number" name="prdPrecio"
                                        value="{{ old( 'prdPrecio', $Producto->prdPrecio ) }}"
                                        class="form-control" id="prdPrecio" min="0" step="0.01">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="idMarca">Marca</label>
                                    <select class="form-select" name="idMarca" id="idMarca">
                                        <option value="">Seleccione una marca</option>
                                @foreach( $marcas as $marca )
                                        <option value="{{ $marca->idMarca }}" {{ (old("idMarca", $Producto->idMarca) == ($marca->idMarca))?"selected":"" }} >{{ $marca->mkNombre }}</option>
                                @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="idCategoria">Categoría</label>
                                    <select class="form-select" name="idCategoria" id="idCategoria">
                                        <option value="">Seleccione una categoría</option>
                                @foreach( $categorias as $categoria )
                                        <option value="{{ $categoria->idCategoria }}" {{ (old("idCategoria", $Producto->idCategoria) == ($categoria->idCategoria))?"selected":"" }}>{{ $categoria->catNombre }}</option>
                                @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="prdDescripcion">Descripción del Producto</label>
                                    <textarea name="prdDescripcion" class="form-control" id="prdDescripcion">{{ old( 'prdDescripcion', $Producto->prdDescripcion ) }}</textarea>
                                </div>

                                <div>
                                    Imagen actual:
                                    <figure class="pt-3 d-flex justify-content-center">
                                        <img src="/imagenes/productos/{{ $Producto->prdImagen }}" class="img-thumbnail">
                                    </figure>
                                </div>

                                Modificar imagen (opcional):
                                <div class="custom-file mt-1 mb-4">
                                    <input type="file" name="prdImagen"  class="custom-file-input" id="customFileLang" lang="es">
                                    <label class="custom-file-label" for="customFileLang" data-browse="Buscar en disco">Seleccionar Archivo: </label>
                                </div>
                                <input type="hidden" name="idProducto"
                                    value="{{ $Producto->idProducto }}">
                                <input type="hidden" name="imgActual"
                                    value="{{ $Producto->prdImagen }}">

                                <div class="py-6 flex items-center justify-end">

                                    <button class="text-blue-900 hover:text-blue-800
                                        bg-blue-400 hover:bg-blue-300 px-5 py-1 mr-6
                                        border border-blue-500 rounded
                                        ">Modificar producto</button>
                                    <a href="/productos" class="text-blue-600 hover:text-blue-500
                                        bg-gray-50 hover:bg-white px-5 py-1
                                        border border-gray-300 rounded
                                        ">Volver a panel de productos</a>

                                </div>

                            </div>
                        </form>
                    </div>
        <!-- FIN formulario -->
                    @if( $errors->any() )
                    <div class="alert alert-danger col-8 mx-auto">
                        <ul>
                            @foreach( $errors->all() as $error )
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>