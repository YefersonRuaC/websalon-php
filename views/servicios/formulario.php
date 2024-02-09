<div class="campo">
    <label for="nombre">Nombre</label>
    <input 
        type="text" 
        name="nombre" 
        id="nombre"
        placeholder="Ingrese nombre del servicio"
        value="<?= $servicio->nombre; ?>">
</div>

<div class="campo">
    <label for="precio">Precio</label>
    <input 
        type="number" 
        name="precio" 
        id="precio"
        placeholder="Ingrese precio del servicio"
        value="<?= $servicio->precio; ?>">
</div>