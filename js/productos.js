let array_productos = [];
function buscar_productos() {
    let valor = $('#agg_producto').val();
    $.ajax({
        type: "POST",
        url: urlweb + "api/Productos/listar_productos_input",
        data: {
            valor: valor
        },
    }).done(function (r) {
        let datos = JSON.parse(r);
        let body = `
            <ul style="
                list-style: none;
                position: absolute;
                z-index: 999;
                background: #ffffff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
                width: 485px;
                margin: 0;
                padding: 0;
                left: 2.5%;
            ">`;
        if (datos.length > 0) {
            datos.map(function (el, index) {
                body +=
                    `
                    <li class="producto-sugerido" onclick="traerdatos_product(${el.id_producto},'${el.producto_nombre}')">${el.producto_nombre}</li>
                    `;
            });
        } else {
            body +=
                `<li class="sin-resultados">Sin resultados</li>`;
        }

        body += `</ul>`;
        $('#lista_productos').html(body);

        const style = document.createElement('style');
        style.innerHTML = `
            .producto-sugerido {
                padding: 5px;
                cursor: pointer;
            }
            
            .producto-sugerido:hover {
                background-color: #4451B6;
                color: #fff;
            }

            .sin-resultados {
                padding: 10px;
                color: #888;
            }
        `;
        document.head.appendChild(style);
    });
}
function traerdatos_product(id_producto, producto_nombre) {
    let existingProductIndex = array_productos.findIndex(product => product.id === id_producto);

    if (existingProductIndex !== -1) {
        array_productos[existingProductIndex].cantidad++;
    } else {
        let options = {
            id: id_producto,
            nombre: producto_nombre,
            medida: 'id_medida',
            cantidad: 1
        };
        array_productos.push(options);
    }

    pintar_tabla();
}

function pintar_tabla() {
    let body = "";
    if (array_productos.length > 0) {
        array_productos.forEach(function (el, index) {
            body += `
            <tr>
                <td>${el.nombre}</td>
                <td>
                    <div class="input-group">
                        <input style="width: 5px" type="text" class="form-control input-number" id="cantidad_${index}" value="${el.cantidad}" oninput="validarNumero(event, ${index})">
                    </div>
                </td>
                <td>
                    <select id="medida_id_${index}" name="medida_id_${index}" onchange="guardar_select_medida(${index})">
                        ${select_medida(el.medida)}
                    </select>
                    <input type="hidden" id="medida_actual_${index}" value="${el.medida_actual}">
                </td>
                <td>
                    <a class="bg-danger btn btn-sm text-white" onclick="accion_eliminar(${index})"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            `;
        });
    }
    $('#llenado_tabla').html(body);
}

//RESTAR
// <span className="input-group-btn">
//                             <button type="button" className="btn btn-default btn-number"
//                                     onClick="disminuirCantidad(${index})">
//                                 <span className="fa fa-minus"></span>
//                             </button>
//                         </span>
//SUMAR
// <span className="input-group-btn">
//                             <button type="button" className="btn btn-default btn-number"
//                                     onClick="aumentarCantidad(${index})">
//                                 <span className="fa fa-plus"></span>
//                             </button>
//                         </span>
function guardar_editar_productos(){
    var valor = true;
    var boton = "btn-agregar-producto";
    var id_producto = $('#id_producto').val();
    var producto_nombre = $('#producto_nombre').val();
    var producto_stock = $('#producto_stock').val();
    var producto_precio = $('#producto_precio').val();
    // var medida = $('#medida').val();
    var id_medida = $('#id_medida').val();

    valor = validar_campo_vacio('producto_nombre',producto_nombre, valor);
    valor = validar_campo_vacio('producto_precio',producto_precio, valor);
    valor = validar_campo_vacio('producto_stock',producto_stock, valor);
    if (producto_stock <= 0) {
        respuesta('¡Stock Inválido', 'error');
        valor = false;
    }
    if(valor){
        var cadena = "producto_nombre=" + producto_nombre +
            "&producto_precio=" + producto_precio+
            "&id_producto=" + id_producto+
            "&id_medida=" + id_medida+
            "&producto_stock=" + producto_stock;
        $.ajax({
            type: "POST",
            url: urlweb + "api/Productos/guardar_editar_productos",
            data: cadena,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        if(id_producto != ""){
                            respuesta('¡Producto Editado Exitosamente', 'success');
                        } else {
                            respuesta('¡Producto guardado! Recargando...', 'success');
                        }
                        setTimeout(function () { location.reload(); }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar producto', 'error');
                        break;
                    case 3:
                        respuesta('El producto ya se encuentra registrado', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
}
function editar_productos(id_producto){
    let guardarid = id_producto;
    $.ajax({
        type: "POST",
        url: urlweb + "api/Productos/edicion_productos",
        data: {
            guardarid :guardarid
        },
        dataType: 'json'
    }).done(function(datos_editar){
        console.log(datos_editar);
        let almacenar = datos_editar.result.code;
        $("#id_producto").val(guardarid);
        $('#producto_nombre').val(almacenar.producto_nombre);
        $('#producto_stock').val(almacenar.producto_stock);
        $('#producto_precio').val(almacenar.producto_precio);
        $('#id_medida').val(almacenar.id_medida);
    });
    function edicion(producto_nombre, producto_stock, producto_precio, medida){
    }

}
function limpiar_productos(){
    $('#id_producto').val('');
    $('#producto_nombre').val('');
    $('#producto_stock').val('');
    $('#producto_precio').val('');
    $('#medida').val('');
}
function eliminar_producto(id_producto){
    $.ajax({
        type: "POST",
        url: urlweb + "api/Productos/eliminar_producto",
        data: {
            id : id_producto
        },
        dataType: 'json',
        success:function (r) {
            switch (r.result.code) {
                case 1:
                    respuesta('¡Producto eliminado! Recargando...', 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                    break;
                case 2:
                    respuesta('Error al borrar producto', 'error');
                    break;
                default:
                    respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                    break;
            }
        }
    });
}
function accion_eliminar(index) {
    array_productos.splice(index,1)
    pintar_tabla()
}
// function aumentarCantidad(index) {
//     let inputCantidad = document.querySelectorAll('.input-number')[index];
//     let cantidad = parseInt(inputCantidad.value);
//     inputCantidad.value = cantidad + 1;
// }
// function disminuirCantidad(index) {
//     let inputCantidad = document.querySelectorAll('.input-number')[index];
//     let cantidad = parseInt(inputCantidad.value);
//     if (cantidad > 1) {
//         inputCantidad.value = cantidad - 1;
//     }
// }
function validarNumero(event, index) {
    const input = event.target;
    const value = input.value.trim();

    if (value === "") {
        input.style.border = 'solid #ff4d4d';
        array_productos[index].cantidad = null;
    } else if (!isNaN(value)) {
        const numero = parseInt(value, 10);
        array_productos[index].cantidad = numero;
        input.style.border = '';
    } else {
        input.style.border = 'solid #ff4d4d';
        array_productos[index].cantidad = null;
    }

    input.value = array_productos[index].cantidad || '';
}
function select_medida(param_medida =  ''){
    let body = '<option>Seleccionar</option>';
    if(datos_medida.length >0){
        datos_medida.map(und=>{
            body += `
            <option value="${und.id_medida}" ${und.id_medida == param_medida ? 'selected' : '' }>${und.nombre_medida}</option>
            `
        })
    }
    return body;
}
function guardar_select_medida(index){
    let selec = $(`#medida_id_${index}`).val()
    array_productos[`${index}`].medida = selec

    // Obtener el índice del producto en el array por su ID
    const productoIndex = array_productos.findIndex(producto => producto.id === array_productos[index].id);

    // Obtener la medida actual del producto en la base de datos
    const medida_actual = array_productos[productoIndex].medida_actual;

    // Guardar la medida actual en el array
    array_productos[productoIndex].medida_actual = medida_actual;
}
$("#formulario_formato").on('submit', function(e){
    e.preventDefault()
    var valor = true;
    var boton = "btn-agregar-formato_ingreso";
    var id_formato = $("#id_formato").val();
    var numero_ingreso = $("#numero_ingreso").val();
    var fecha_ingreso = $("#fecha_ingreso").val();
    var hora_ingreso = $("#hora_ingreso").val();
    var proveedor = $("#proveedor").val();
    valor = validar_campo_vacio('proveedor',proveedor, valor);
    let productos_array = new FormData(this);
    productos_array.append('array_productos' , JSON.stringify(array_productos))
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/Productos/guardar_formato_ingreso",
            data:productos_array,
            contentType: false,
            cache: false,
            processData:false,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Guardando...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Guardar", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Formato guardado! Recargando...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al guardar formato de ingreso', 'error');
                        break;
                    case 3:
                        respuesta('Antes debe escoger un producto a ingresar', 'error');
                        break;
                    case 4:
                        respuesta('Unidad de medida Inválido!', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
})

const searchBox = document.getElementById('agg_producto');
const productList = document.getElementById('lista_productos');
searchBox.addEventListener('input', function() {
    if (this.value.trim() === '') {
        productList.innerHTML = '';
        productList.style.display = 'none';
    } else {
        buscar_productos();
        productList.style.display = 'block';
    }
});
document.getElementById('agg_producto').addEventListener('focus', function() {
    if (this.value.trim() !== '') {
        buscar_productos();
        productList.style.display = 'block';
    }
});
document.getElementById('agg_producto').addEventListener('blur', function() {
    setTimeout(function() {
        productList.style.display = 'none';
    }, 100);
});