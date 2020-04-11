
    <!-- inicio refrencia para el arbol de roles -->
    <link rel="stylesheet" href="http://static.jstree.com/latest/assets/dist/themes/default/style.min.css" />
    <script src="http://static.jstree.com/latest/assets/dist/libs/jquery.js"></script>
    <script src="http://static.jstree.com/latest/assets/dist/jstree.min.js"></script>
    <!-- fin refrencia para el arbol de roles -->
    <!-- inicio buscador de tablas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
    <!-- fin buscador de tablas -->
    <script type="text/javascript">
        $(window).load(function() {
            $(".loader").fadeOut("slow");
        });
        $(document).ready(function() {
            $('#buscador_tabla').DataTable( {
                  "select": false,
                  "language": {
                        "lengthMenu": "Mostrar _MENU_ items",
                        "zeroRecords": "No existe ninun dato para mostrar",
                        "info": " Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No existe ninun dato",
                        "search":         "Buscar&nbsp;:",
                        "paginate": {
                            first:      "Premier",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Dernier"
                        },
                        "infoFiltered": "(filtered from _MAX_ total records)",
                        "select": {
                            rows: {
                                _: " (Seleccionado %d filas)",
                                0: "  Seleccine una fila para marcar",
                                1: " Seleccionado 1 fila"
                            }
                        }
                  },
                  "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]],
                  "bLengthChange" : false,

            });
        });
        window.setTimeout(function() {
            $(".exito").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 2000);

    </script>