function validarCampoNumerico(input) {
    const tipo = input.dataset.tipo;
    let valor = input.value;

    switch (tipo) {
        case "telefono":
        case "cedula":
            // Solo números, máximo 10 dígitos
            input.value = valor.replace(/\D/g, '').slice(0, 10);
            break;

        case "ruc":
            // Solo números, máximo 13 dígitos
            input.value = valor.replace(/\D/g, '').slice(0, 13);
            break;

        case "convencional":
            // Solo números, entre 7 y 9 dígitos
            input.value = valor.replace(/\D/g, '').slice(0, 9);
            break;

        case "promedio":
            // Permitir solo números decimales con máximo dos decimales y valor máximo 100.00
            valor = valor.replace(/[^0-9.]/g, '');
            const partes = valor.split('.');

            if (partes.length > 2) {
                valor = partes[0] + '.' + partes[1]; // eliminar puntos adicionales
            }

            // Limitar a 2 decimales
            if (partes[1]) {
                partes[1] = partes[1].slice(0, 2); // solo dos dígitos
                valor = partes[0] + '.' + partes[1];
            }

            // Convertir a número para validar el rango
            if (parseFloat(valor) > 100) {
                valor = '100.00';
            }

            input.value = valor;
            break;

        case "entero":
            // Solo números enteros
            input.value = valor.replace(/\D/g, '');
            break;

        default:
            input.value = valor; // No hacer nada si no hay tipo válido
    }
}
