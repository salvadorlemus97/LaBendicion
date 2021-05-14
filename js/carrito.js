$(document).ready(function(){
    $('.select2').select2();
    rellenar_clientes();
    recuperarLS_carrito();
    contar_productos();
    recuperarLS_carrito_compra();
    calculartotal();
    $(document).on('click', '.agregar-carrito', (e) => {
        const elemento = $(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id = $(elemento).attr('prodid');
        const nombre = $(elemento).attr('prodnombre');
        const concentracion = $(elemento).attr('prodconcentracion');
        const adicional = $(elemento).attr('prodadicional');
        const precio = $(elemento).attr('prodprecio');
        const laboratorio = $(elemento).attr('prodlaboratorio');
        const tipo = $(elemento).attr('prodtipo');
        const presentacion = $(elemento).attr('prodpresentacion');
        const avatar = $(elemento).attr('prodavatar');
        const stock = $(elemento).attr('prodstock');

        const producto ={
            id: id,
            nombre: nombre,
            concentracion: concentracion,
            adicional: adicional,
            precio: precio,
            laboratorio: laboratorio,
            tipo: tipo,
            presentacion: presentacion,
            avatar: avatar,
            stock: stock,
            cantidad: 1
        };
        let id_producto;
        let productos;
        productos = recuperarLS();
        productos.forEach(prod => {
            if (prod.id === producto.id) {
                id_producto = prod.id;
            }
        });
        if (id_producto === producto.id) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El producto ya fue agregado al carrito de compras!'
            });
        }else{
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            
            Toast.fire({
                icon: 'success',
                title: 'Se agrego el producto al carrito de compras'
            })
            template = `
                <tr prodid="${producto.id}">
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.concentracion}</td>
                    <td>${producto.adicional}</td>
                    <td>${producto.precio}</td>
                    <td><button class="btn btn-danger borrar-producto"><i class="fas fa-times-circle"></i></button></td>
                </tr>
            `;
            $('#lista').append(template);
            agregarLS(producto);
            contar_productos();
        }        
    });

    $(document).on('click', '.borrar-producto', (e) => {
        const elemento = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(elemento).attr('prodid');
        elemento.remove();
        eliminar_producto_LS(id);
        contar_productos();
        calculartotal();
    });

    $(document).on('click', '#vaciar-carrito', (e) => {
        $('#lista').empty();
        eliminarLS();
        contar_productos();
    });

    $(document).on('click', '#procesar-pedido', (e) => {
        procesar_pedido();
    });   
    
    $(document).on('click', '#procesar-compra', (e) => {
        procesar_compra();
    });

    function recuperarLS() {
        let productos;
        if (localStorage.getItem('productos') === null) {
            productos = [];
        }else{
            productos = JSON.parse(localStorage.getItem('productos'));
        }
        return productos;
    }

    function agregarLS(producto) {
        let productos;
        productos = recuperarLS();
        productos.push(producto);
        localStorage.setItem('productos', JSON.stringify(productos));
    }

    function recuperarLS_carrito() {
        let productos, id_producto;
        productos = recuperarLS();
        funcion = 'buscar_id';
        productos.forEach(producto => {
            id_producto = producto.id;
            $.post('../controlador/ProductoController.php', {funcion, id_producto}, (response) => {
                let template_carrito = '';
                let json = JSON.parse(response);
                template_carrito = `
                    <tr prodid="${json.id}">
                        <td>${json.id}</td>
                        <td>${json.nombre}</td>
                        <td>${json.concentracion}</td>
                        <td>${json.adicional}</td>
                        <td>${json.precio}</td>
                        <td><button class="btn btn-danger borrar-producto"><i class="fas fa-times-circle"></i></button></td>
                    </tr>
                `;
                $('#lista').append(template_carrito);
            });
        });
    }

    function eliminar_producto_LS(id){
        let productos;
        productos = recuperarLS();
        productos.forEach(function(producto, indice) {
            if (producto.id === id) {
                productos.splice(indice, 1);
            }
        });
        localStorage.setItem('productos', JSON.stringify(productos));
    }

    function eliminarLS() {
        localStorage.clear();
    }

    function contar_productos(){
        let productos;
        productos = recuperarLS();
        let contador = 0;
        productos.forEach(producto => {
            contador++;
        });
        $('#contador').html(contador);
    }

    function procesar_pedido(){
        let productos;
        productos = recuperarLS();
        if (productos.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El carrito esta vacio!'
            });
        }else{
            location.href = '../vista/adm_compra.php';
        }
    }

    // no se esta usando xq dio problemas de sincronizacion, pero se dejo como referencia
    function recuperarLS_carrito_compra1() { // esta funcion tiene problemas de sincronizacion al mostrar la lista de productos comprados en adm_compras.php, al digitar la cantidad en los inputs se refleja en otras filas        
        let productos, id_producto;
        productos = recuperarLS();
        funcion = 'buscar_id';
        productos.forEach(producto => {
            id_producto = producto.id;
            $.post('../controlador/ProductoController.php', {funcion, id_producto}, (response) => {
                let template_compra = '';
                let json = JSON.parse(response);
                template_compra = `
                    <tr prodid="${producto.id}" prodprecio="${json.precio}">
                        <td>${json.nombre}</td>
                        <td>${json.stock}</td>
                        <td class="precio">${json.precio}</td>
                        <td>${json.concentracion}</td>
                        <td>${json.adicional}</td>
                        <td>${json.laboratorio}</td>
                        <td>${json.presentacion}</td>
                        <td><input type="number" min="1" class="form-control cantidad_producto" value="${producto.cantidad}"></td>
                        <td class="subtotales"><h5>${(json.precio*producto.cantidad)}</h5></td>
                        <td><button class="btn btn-danger borrar-producto"><i class="fas fa-times-circle"></i></button></td>
                    </tr>
                `;                
                $('#lista-compra').append(template_compra);
            });
        });
    }

    async function recuperarLS_carrito_compra() {
        let productos;
        productos = recuperarLS();
        funcion = 'traer_productos';
        const response = await fetch('../controlador/ProductoController.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'funcion='+funcion+'&&productos='+JSON.stringify(productos) // codifica el arreglo enviado a jsonstring
        });
        let resultado = await response.text(); //si se envia un json se cambia el text por json
        $('#lista-compra').append(resultado);
    }

    $(document).on('click', '#actualizar', (e) => {
        let productos, precios;
        precios = document.querySelectorAll('.precio');
        productos = recuperarLS();
        productos.forEach(function(producto, indice) {
            producto.precio = precios[indice].textContent;
        });
        localStorage.setItem('productos', JSON.stringify(productos));
        calculartotal();

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        
        Toast.fire({
            icon: 'success',
            title: 'Datos actualizados'
        })
    });

    $('#cp').keyup((e) => {
        let id, cantidad, producto, productos, montos, precio;
        producto = $(this)[0].activeElement.parentElement.parentElement;
        id = $(producto).attr('prodid');
        precio = $(producto).attr('prodprecio');
        cantidad = producto.querySelector('input').value;
        montos = document.querySelectorAll('.subtotales');
        productos = recuperarLS();
        productos.forEach(function(prod, indice) {
            if (prod.id === id) {
                prod.cantidad = cantidad;
                prod.precio = precio;
                montos[indice].innerHTML = `
                    <h5>${(cantidad*precio)}</h5>
                `;
            }
        });
        localStorage.setItem('productos', JSON.stringify(productos));
        calculartotal();
    });

    function calculartotal() {
        let productos, subtotal, con_iva, total_sin_descuento, pago, vuelto, descuento;
        let total = 0, iva = 0.13;
        productos = recuperarLS();
        productos.forEach(producto => {
            let subtotal_producto = Number(producto.precio*producto.cantidad);
            total = (total+subtotal_producto);
        });
        pago = $('#pago').val();
        descuento = $('#descuento').val();

        total_sin_descuento = parseFloat(total).toFixed(2);
        con_iva = parseFloat(total*iva).toFixed(2);
        subtotal = parseFloat(total-con_iva).toFixed(2);
        total = (total-descuento);
        vuelto = (pago-total);

        $('#subtotal').html(subtotal);
        $('#con_iva').html(con_iva);
        $('#total_sin_descuento').html(total_sin_descuento);
        $('#total').html(total.toFixed(2));
        $('#vuelto').html(vuelto.toFixed(2));
    }

    function procesar_compra(){
        let cliente = $('#cliente').val();
        if (recuperarLS().length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No hay productos, debe de seleccionar un producto!'
              }).then(function(){
                  location.href = '../vista/adm_catalogo.php'
              })
        }else if(cliente == ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Se requiere un seleccionar un cliente!'
              })
        }else{
            verificar_stock().then(error => {
                if (error == 0) {
                    registrar_compra(cliente);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Se realizo la compra!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function(){
                        eliminarLS();
                        location.href = '../vista/adm_catalogo.php'
                    })
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Ocurrio un problema al verificar el stock de los productos, por favor revisar stock!'
                    })
                }
            });            
        }
    }

    /*esta funcion realizada por ajax es asincrona y si se ejecuta asi causa un problema ya que por ser asincrona no espera 
    q se termine de ejecutar la peticion para devolver el resultado, por eso se puso en falso la asincronia, con esto si espera
    a que se termine de ejecutar la peticion, pero ese tiempo que se tarda mantiene al navegador bloqueado y no se puede realizar
    ninguna accion hasta que termina de ejecutarse la peticion. esta opcion es muy poco optima es por eso que vamos a utilizar fetch await.*/
    /*
    function verificar_stock() {
        let productos, id, cantidad;
        let error = 0;
        funcion = 'verificar_stock';
        productos = recuperarLS();
        productos.forEach(producto => {
            id = producto.id;
            cantidad = producto.cantidad;
            $.ajax({
                url: '../controlador/ProductoController.php',
                data: {funcion, id, cantidad},
                type: 'POST',
                async: false,
                success: function(response){
                    error = (error + Number(response));
                }
            });
        });
        return error;
    }
    */

    // aqui se utiliza el fectch await, este solo funciona con funciones asincronas
    async function verificar_stock() {
        let productos;
        funcion = 'verificar_stock';
        productos = recuperarLS();
        const response = await fetch('../controlador/ProductoController.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'funcion='+funcion+'&&productos='+JSON.stringify(productos)
        });
        let error = await response.text(); //si se envia un json se cambia el text por json
        return error;
    }

    function registrar_compra(cliente){
        funcion = 'registrar_compra';
        let total = $('#total').get(0).textContent;
        let productos = recuperarLS();
        let json = JSON.stringify(productos);
        $.post('../controlador/CompraController.php', {funcion, total, cliente, json}, (response) => {
            console.log(response);
        });
    }

    function rellenar_clientes(){
        funcion = 'rellenar_clientes';
        $.post('../controlador/ClienteController.php', {funcion}, (response) => {
            let clientes = JSON.parse(response);
            let template = '<option value="" selected disabled>-- Seleccionar Cliente --</option>';
            clientes.forEach(cliente => {
                template += `
                    <option value="${cliente.id}">${cliente.nombre}</option>
                `;
            });
            $('#cliente').html(template);
        });
    }
});