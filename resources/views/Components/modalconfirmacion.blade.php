@props([
    'id',
    'titulo',
    'mensaje',
    'action',
    'method' => 'POST',
    'textoBoton' => 'Confirmar',
    'claseBoton' => 'btn-peligro',
])

<div id="{{ $id }}" class="modal-overlay">
    <div class="modal-caja">
        <h3>{{ $titulo }}</h3>
        <p>{{ $mensaje }}</p>

        <div class="modal-acciones">
            <a href="#" class="btn">Cancelar</a>

            <form action="{{ $action }}" method="POST">
                @csrf
                @if(strtoupper($method) !== 'POST')
                    @method($method)
                @endif
                <button type="submit" class="btn {{ $claseBoton }}">{{ $textoBoton }}</button>
            </form>
        </div>
    </div>
</div>