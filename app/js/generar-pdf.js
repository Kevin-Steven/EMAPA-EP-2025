document.addEventListener('DOMContentLoaded', () => {
    const botonGenerar = document.getElementById('btn-generar-pdf');
    const formulario = document.getElementById('formulario-pdf');

    if (botonGenerar && formulario) {
        botonGenerar.addEventListener('click', function () {
            if (!formulario.reportValidity()) {
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se generará el documento PDF con los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00519E',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, generar PDF',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    formulario.submit();
                    Swal.fire({
                        icon: 'success',
                        title: 'Generando...',
                        text: 'Tu archivo se abrirá en una nueva ventana.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        });
    }
});
