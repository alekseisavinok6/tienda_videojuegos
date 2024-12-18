<?php

session_start();

require 'bd.php';

$sqlVideojuegos = "SELECT v.id, v.nombre, v.descripcion, g.nombre AS genero FROM videojuego AS v
INNER JOIN genero AS g ON v.id_genero=g.id";
$videojuegos = $conn->query($sqlVideojuegos);

$dir = "imagenes/";

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Videojuegos playstation 2</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="recursos/css/bootstrap.min.css" rel="stylesheet">
    <link href="recursos/css/all.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
    <div class="container py-3">
        <h2 class="text-center">Videojuegos playstation 2</h2>
        <hr>

        <?php if (isset($_SESSION['msg']) && isset($_SESSION['color'])) { ?>
            <div class="alert alert-<?= $_SESSION['color']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <?php
            unset($_SESSION['color']);
            unset($_SESSION['msg']);
        } ?>

        <div class="row justify-content-end">
            <div class="col-auto">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nu_ventana_1"><i class="fa-solid fa-circle-plus"></i> Alta de datos</a>
            </div>
        </div>

        <table class="table table-sm table-striped table-hover mt-4">
            <thead class="table-primary">
                <tr>
                    <th><i>id</i></th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Género</th>
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row_videojuego = $videojuegos->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row_videojuego['id']; ?></td>
                        <td><?= $row_videojuego['nombre']; ?></td>
                        <td><?= $row_videojuego['descripcion']; ?></td>
                        <td><?= $row_videojuego['genero']; ?></td>
                        <td><img src="<?= $dir . $row_videojuego['id'] . '.jpg?n=' . time(); ?>" width="100"></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ed_ventana_1" data-bs-id="<?= $row_videojuego['id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#el_ventana_1" data-bs-id="<?= $row_videojuego['id']; ?>"><i class="fa-solid fa-trash"></i></i> Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <p class="text-center">Tienda de videojuegos - 2025</a></p>
        </div>
    </footer>

    <?php
    $sqlGenero = "SELECT id, nombre FROM genero";
    $generos = $conn->query($sqlGenero);

    $conn->close();
    ?>

    <?php include 'nuevaVentana.php'; ?>

    <?php $generos->data_seek(0); ?>

    <?php include 'editarVentana.php'; ?>
    <?php include 'eliminarVentana.php'; ?>

    <script>
        let nuevaVentana = document.getElementById('nu_ventana_1')
        let editarVentana = document.getElementById('ed_ventana_1')
        let eliminarVentana = document.getElementById('el_ventana_1')

        nuevaVentana.addEventListener('shown.bs.modal', event => {
            nuevaVentana.querySelector('.modal-body #nombre').focus()
        })

        nuevaVentana.addEventListener('hide.bs.modal', event => {
            nuevaVentana.querySelector('.modal-body #nombre').value = ""
            nuevaVentana.querySelector('.modal-body #descripcion').value = ""
            nuevaVentana.querySelector('.modal-body #genero').value = ""
            nuevaVentana.querySelector('.modal-body #imagen').value = ""
        })

        editarVentana.addEventListener('hide.bs.modal', event => {
            editarVentana.querySelector('.modal-body #nombre').value = ""
            editarVentana.querySelector('.modal-body #descripcion').value = ""
            editarVentana.querySelector('.modal-body #genero').value = ""
            editarVentana.querySelector('.modal-body #img_imagen').value = ""
            editarVentana.querySelector('.modal-body #imagen').value = ""
        })

        editarVentana.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')

            let inputId = editarVentana.querySelector('.modal-body #id')
            let inputNombre = editarVentana.querySelector('.modal-body #nombre')
            let inputDescripcion = editarVentana.querySelector('.modal-body #descripcion')
            let inputGenero = editarVentana.querySelector('.modal-body #genero')
            let imagen = editarVentana.querySelector('.modal-body #img_imagen')

            let url = "obtenerVideojuego.php"
            let formData = new FormData()
            formData.append('id', id)

            fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
                .then(data => {

                    inputId.value = data.id
                    inputNombre.value = data.nombre
                    inputDescripcion.value = data.descripcion
                    inputGenero.value = data.id_genero
                    imagen.src = '<?= $dir ?>' + data.id + '.jpg'

                }).catch(err => console.log(err))
        })

        eliminarVentana.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            eliminarVentana.querySelector('.modal-footer #id').value = id
        })

    </script>


    <script src="recursos/js/bootstrap.bundle.min.js"></script>
</body>
</html>