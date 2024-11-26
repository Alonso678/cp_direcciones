<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Direcciones</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .record-container {
            display: flex;
        }
        .record-list {
            width: 60%;
            padding-right: 10px;
            border-right: 1px solid #ddd;
        }
        .form-container {
            width: 40%;
            padding-left: 10px;
        }
        .btn-acciones {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .formulario {
            pointer-events: none;
            max-width: 400px;
            margin: 0 auto;
        }
        .formulario input, .formulario button, .formulario select {
            pointer-events: none;
        }
        .record-attribute {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="btn-acciones">
        <button type="button" class="btn btn-primary" id="btn-editar">Editar</button>
        <button type="submit" class="btn btn-danger" name="eliminar" form="form-acciones">Eliminar</button>
    </div>
    <div class="record-container">
        <div class="record-list">
            <h2>Listar Direcciones</h2>
            <form id="form-acciones" method="post" action="acciones.php">
                <?php include 'read_direcciones.php'; ?>
            </form>
        </div>
        <div class="form-container">
            <h2>Formulario de Dirección</h2>
            <form method="post" action="create_process.php" id="formulario-direccion" class="formulario">
                <div class="form-group">
                    <label>País</label>
                    <select name="pais" id="pais" class="form-control">
                        <option value="">Seleccione un país</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" id="estado" class="form-control" disabled>
                        <option value="">Seleccione un estado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Municipio</label>
                    <select name="municipio" id="municipio" class="form-control" disabled>
                        <option value="">Seleccione un municipio</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Localidad</label>
                    <select name="localidad" id="localidad" class="form-control" disabled>
                        <option value="">Seleccione una localidad</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Código Postal</label>
                    <input type="text" name="codigopostal" id="input-codigopostal" class="form-control" placeholder="Código Postal">
                    <small id="codigoPostalHelp" class="form-text text-muted">Ingrese el código postal y seleccione el país para validar.</small>
                </div>
                <button type="submit" class="btn btn-success" id="btn-guardar">Guardar</button>
                <button type="button" class="btn btn-secondary" id="btn-cancelar">Cancelar</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <button type="button" class="btn btn-success" id="btn-agregar">Agregar</button>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function habilitarFormulario() {
        document.querySelectorAll('.formulario input, .formulario button, .formulario select').forEach(function(element) {
            element.style.pointerEvents = 'auto';
        });
    }

    function deshabilitarFormulario() {
        document.querySelectorAll('.formulario input, .formulario button, .formulario select').forEach(function(element) {
            element.style.pointerEvents = 'none';
        });
    }

    async function cargarOpciones(selectElement, type, key = '') {
        const response = await fetch(`get_options.php?type=${type}&key=${key}`);
        const data = await response.json();
        
        selectElement.innerHTML = '<option value="">Seleccione una opción</option>';
        data.forEach(option => {
            let newOption = document.createElement('option');
            newOption.value = option[Object.keys(option)[0]];
            newOption.text = option['descripcion'] || option[Object.keys(option)[0]];
            selectElement.appendChild(newOption);
        });
        selectElement.disabled = false;
    }

    async function validarCodigoPostal(pais, codigoPostal) {
        try {
            const response = await fetch(`https://api.zippopotam.us/${pais}/${codigoPostal}`);
            if (response.ok) {
                const data = await response.json();
                return {
                    valid: true,
                    data: data
                };
            } else {
                return {
                    valid: false,
                    message: 'Código postal no válido o no encontrado.'
                };
            }
        } catch (error) {
            return {
                valid: false,
                message: 'Error al validar el código postal.'
            };
        }
    }

    document.getElementById('btn-agregar').addEventListener('click', function() {
        document.getElementById('formulario-direccion').reset();
        habilitarFormulario();
        cargarOpciones(document.getElementById('pais'), 'pais');
    });

    document.getElementById('btn-editar').addEventListener('click', async function() {
        let checkboxes = document.querySelectorAll('input[name="seleccionar[]"]:checked');
        if (checkboxes.length === 1) {
            let codigopostal = checkboxes[0].value;
            const response = await fetch(`get_direccion.php?codigopostal=${codigopostal}`);
            const data = await response.json();
            
            document.getElementById('pais').value = data.pais_key;
            await cargarOpciones(document.getElementById('estado'), 'estado', data.pais_key);
            document.getElementById('estado').value = data.estado_key;
            await cargarOpciones(document.getElementById('municipio'), 'municipio', data.estado_key);
            document.getElementById('municipio').value = data.municipio_key;
            await cargarOpciones(document.getElementById('localidad'), 'colonia', data.municipio_key);
            document.getElementById('localidad').value = data.localidad_key;
            document.getElementById('input-codigopostal').value = data.codigopostal;
            habilitarFormulario();
        } else {
            alert('Seleccione un solo registro para editar.');
        }
    });

    document.getElementById('btn-cancelar').addEventListener('click', function() {
        document.getElementById('formulario-direccion').reset();
        deshabilitarFormulario();
    });

    document.getElementById('pais').addEventListener('change', async function() {
        let paisKey = this.value;
        let estadoSelect = document.getElementById('estado');
        await cargarOpciones(estadoSelect, 'estado', paisKey);
        estadoSelect.disabled = !paisKey;
        document.getElementById('municipio').disabled = true;
        document.getElementById('localidad').disabled = true;
    });

    document.getElementById('estado').addEventListener('change', async function() {
        let estadoKey = this.value;
        let municipioSelect = document.getElementById('municipio');
        await cargarOpciones(municipioSelect, 'municipio', estadoKey);
        municipioSelect.disabled = !estadoKey;
        document.getElementById('localidad').disabled = true;
    });

    document.getElementById('municipio').addEventListener('change', async function() {
        let municipioKey = this.value;
        let localidadSelect = document.getElementById('localidad');
        await cargarOpciones(localidadSelect, 'colonia', municipioKey);
        localidadSelect.disabled = !municipioKey;
    });
</script>
</body>
</html>
