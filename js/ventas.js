let array_productos = [];
function buscar_productos_comprar(){
    let valor = $('#productos_comprar').val()
    $.ajax({
        type: "POST",
        url: urlweb + "api/Ventas/listar_productos_comprar",
        data: {
            valor:valor
        },
    }).done(function (r){
        let datos = JSON.parse(r);
        let body = `<ul style="
                list-style: none;
                position: absolute;
                z-index: 999;
                background: #ffffff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
                width: 485px;
                margin: 0;
                padding: 0;
                left: 2.5%;">`
        if (datos.length > 0){
            datos.map(function (el,index){
                body+=
                    `
                     <li class="producto-sugerido"  onselect="" onclick="traerdatos_producto(${el.id_producto},'${el.producto_nombre}','${el.producto_stock}','${el.producto_precio}'); calcular_total_venta(); calcular_vuelto()">${el.producto_nombre}</li>
                     `
            })
        }else{
            body+=
                `<li class="sin-resultados">Sin resultados</li>`
        }
        body += `</ul>`
        $('#lista_productos_comprar').html(body)

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
function traerdatos_producto(id_producto,producto_nombre, producto_stock, producto_precio){
    $('#lista_productos_comprar').html('')
    $('#productos_comprar').val('')

    // Buscar si el id_producto ya existe en el array
    const existeProducto = array_productos.some(producto => producto.id === id_producto);

    if (existeProducto) {
        console.log('Ya se encuentra el producto en la lista');
        respuesta('Ya se encuentra el producto en la lista', 'error');
        return; // Salir de la función si el producto ya existe
    }

    let options = {
        id: id_producto,
        nombre: producto_nombre,
        stock: producto_stock,
        precio_unitario: producto_precio,
        subtotal: producto_precio,
        vender_cantidad: 1,
    }
    array_productos.push(options);
    llenar_tabla();
    return options.vender_cantidad;
}

function llenar_tabla(){
    let body = ""
    if(array_productos.length > 0){
        array_productos.map(function (el,index){
            body += `
            <tr>
                <td class="nombre-producto">${el.nombre}</td>
                <td class="stock-producto">${el.stock}</td>
                <td>
                    <div class="input-group">
                    
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-number" onclick="disminuir_cantidad(${index}); calcular_total(${index}); calcular_total_venta(); calcular_vuelto()">
                                <span class="fa fa-minus"></span>
                            </button>
                        </span>
                        <input id="inputCantidad" style="width: 2px !important;" type="text" class="form-control input-number" value="1" min="1" oninput="actualizar_cantidad(${index}); validar_cantidad(${index}); validarNumero(event); calcular_total(${index}); calcular_total_venta()" onchange="actualizar_cantidad(${index}); validar_cantidad(${index}); calcular_total(${index}); calcular_total_venta(); calcular_vuelto()">
                        
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-number" onclick="aumentar_cantidad(${index}); calcular_total(${index}); calcular_total_venta(); calcular_vuelto()">
                                <span class="fa fa-plus"></span>
                            </button>
                        </span>
                        
                    </div>
                </td>
                
<!--                LO SGTE ES COMENTARIO:-->
                <!––<td>
                    <select id="medida_id_${index}" name="medida_id_${index}" onchange="guardar_select_medida(${index})">
                        ${select_medida(el.medida)}
                    </select>
                </td>––>
                <!--FIN DEL COMENTARIO-->
                
                <td class="precio-unitario">${el.precio_unitario}</td>
                
                <td class="precio-total">${el.subtotal}</td>
                
                <td>
                    <a class="bg-danger btn btn-sm text-white" onclick="accion_eliminar(${index})"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            `
        })
    }
    $('#tabla_vender').html(body)
}

function select_medida(param_medida =  ''){
    let body = '<option>Seleccionar</option>';
/*    if(datos_medida.length >0){
        datos_medida.map(und=>{
            body += `
            <option value="${und.id_medida}" ${und.id_medida == param_medida ? 'selected' : '' }>${und.nombre_medida}</option>
            `
        })
    }*/
    return body;
}

function actualizar_cantidad(index){
    let inputCantidad = document.querySelectorAll('.input-number')[index];
    let cantidad = parseInt(inputCantidad.value);
    let stock = document.querySelectorAll('.stock-producto')[index];
    let cantidad_vender=cantidad;
    inputCantidad.value = cantidad_vender.toString();
    array_productos[index].vender_cantidad = cantidad_vender;
}

function aumentar_cantidad(index, vender_cantidad) {
    let inputCantidad = document.querySelectorAll('.input-number')[index];
    let cantidad = parseInt(inputCantidad.value);
    let stock = document.querySelectorAll('.stock-producto')[index];
    stock=parseInt(stock.innerHTML)
    let cantidad_vender=cantidad;
    if (cantidad<stock){
        cantidad_vender=cantidad_vender+1;
        inputCantidad.value = cantidad_vender.toString();
        array_productos[index].vender_cantidad = cantidad_vender;
    }
}

function disminuir_cantidad(index) {
    let inputCantidad = document.querySelectorAll('.input-number')[index];
    let cantidad = parseInt(inputCantidad.value);
    let cantidad_vender=cantidad;
    if (cantidad > 1) {
        cantidad_vender=cantidad_vender-1;
        inputCantidad.value = cantidad_vender.toString();
        array_productos[index].vender_cantidad = cantidad_vender;
    }
}

function accion_eliminar(index) {
    array_productos.splice(index,1)
    llenar_tabla()
    calcular_total_venta()
}

function calcular_total(index){
    let precio_unit = document.querySelectorAll('.precio-unitario')[index];
    precio_unit=parseFloat(precio_unit.innerHTML);
    let cant = document.querySelectorAll('.input-number')[index];
    cant = parseInt(cant.value);
    let precio_total=precio_unit*cant;

    document.querySelectorAll('.precio-total')[index].innerHTML=precio_total.toFixed(2);

    array_productos[index].subtotal = precio_total;
}

function vaciar_listado(index) {
    array_productos=[];
    llenar_tabla()
    calcular_total_venta();
}

function calcular_total_venta() {

    let total=0;

    let celdasTotal=document.querySelectorAll('.precio-total');
    for (let i=0; i<celdasTotal.length; ++i) {
        total+=parseFloat(celdasTotal[i].firstChild.textContent);
    }
    total=total.toFixed(2);

    document.getElementById('total_venta').innerHTML='Total Venta: S/. ' + total;
    document.getElementById('total_venta_pago').innerHTML='Total Venta: S/. ' + total;
    return total
}

function validarNumero(event) {
    const input = event.target;
    const value = input.value;
    const sanitizedValue = value.replace(/\D/g, '');
    const numero = parseInt(sanitizedValue, 10) || 1;
    input.value = numero;
}

function validar_cantidad(index){
    let inputCantidad = document.querySelectorAll('.input-number')[index];
    let cantidad = parseInt(inputCantidad.value);
    let stock = document.querySelectorAll('.stock-producto')[index];
    stock=parseInt(stock.innerHTML)
    if (stock<=cantidad){
        inputCantidad.value = stock;
        array_productos[index].vender_cantidad = stock;
        console.log(array_productos);
    }
}

function calcular_vuelto() {
    let vuelto=0;
    let total=calcular_total_venta();
    let input_efectivo=document.getElementById('efectivo_recibido');
    let efectivo=parseFloat(input_efectivo.value);

    if (array_productos.length>0) {
        if (document.getElementById('pago_exacto?').checked){
            vuelto=0;
            input_efectivo.value=calcular_total_venta();
            input_efectivo.disabled=true;
        }
        else {
            input_efectivo.disabled=false;
            if (efectivo>=total){
                vuelto=efectivo-total;
            }
        }
        document.getElementById('vuelto').innerHTML='Vuelto: S/. ' + vuelto.toFixed(2);
    }

}

function ultima_serie(){
    let id_tipo_documento = $("#id_tipo_documento").val();
    console.log(id_tipo_documento);
    if (id_tipo_documento===""){
        document.getElementById('documento').value="";
    }
    else{
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/ultima_serie",
            data: {
                tipo_documento:id_tipo_documento
            },
            datatype: "json"
        }).done(function (r){

            var valorObtenido = JSON.parse(r);
            let ultima_serie=valorObtenido.valor_obtenido.documento_serie;
            let serie=ultima_serie.split('-')[0];
            let correlativo = parseInt(ultima_serie.split('-')[1], 10);
            let nuevo_correlativo=correlativo+1;
            nuevo_correlativo=nuevo_correlativo.toString().padStart(6, '0');
            let nueva_serie= serie +"-"+ nuevo_correlativo;
            $("#documento").val(nueva_serie);
        });
    }
}

/*
function pago_exacto(){
    let var_pago_exacto;
    if (document.getElementById('pago_exacto?').checked){
        var_pago_exacto=true;
        calcular_vuelto();
    }
    else{
        var_pago_exacto=false;
    }
    return var_pago_exacto;
}*/
$("#formulario_realizar_venta").on('submit', function(e){
    e.preventDefault()
    var valor = true;
    var boton = "btn_realizar_venta";
    var id_venta = $("#id_venta").val();
    var id_cliente = $("#id_cliente").val();
    var id_tipo_documento = $("#id_tipo_documento").val();
    var modo_pago = $("#modo_pago").val();
    var id_documento = $("#id_documento").val();
    var documento= $("#documento").val();
    valor = validar_campo_vacio('id_tipo_documento',id_tipo_documento, valor);
    valor = validar_campo_vacio('modo_pago',modo_pago, valor);
    valor = validar_campo_vacio('id_cliente',id_cliente, valor);
    let productos_array = new FormData(this);
    productos_array.append('array_productos' , JSON.stringify(array_productos))
    if(valor){
        $.ajax({
            type: "POST",
            url: urlweb + "api/Ventas/guardar_realizar_venta",
            data:productos_array,
            contentType: false,
            cache: false,
            processData:false,
            dataType: 'json',
            beforeSend: function () {
                cambiar_estado_boton(boton, 'Vendiendo...', true);
            },
            success:function (r) {
                cambiar_estado_boton(boton, "<i class=\"fa fa-save fa-sm text-white-50\"></i> Realizar Venta", false);
                switch (r.result.code) {
                    case 1:
                        respuesta('¡Venta Realizada! Recargando...', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        break;
                    case 2:
                        respuesta('Error al realizar venta', 'error');
                        break;
                    case 3:
                        respuesta('Antes debe escoger un producto', 'error');
                        break;
                    default:
                        respuesta('¡Algo catastrofico ha ocurrido!', 'error');
                        break;
                }
            }
        });
    }
})

const cajaBuscar = document.getElementById('productos_comprar');
const listaProductos = document.getElementById('lista_productos_comprar');
cajaBuscar.addEventListener('input', function() {
    if (this.value.trim() === '') {
        listaProductos.innerHTML = '';
        listaProductos.style.display = 'none';
    } else {
        buscar_productos_comprar();
        listaProductos.style.display = 'block';
    }
});
document.getElementById('productos_comprar').addEventListener('focus', function() {
    if (this.value.trim() !== '') {
        buscar_productos_comprar();
        listaProductos.style.display = 'block';
    }
});
document.getElementById('productos_comprar').addEventListener('blur', function() {
    setTimeout(function() {
        listaProductos.style.display = 'none';
    }, 100);
});