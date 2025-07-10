// Script reutilizable para almacenar datos básicos del formulario en localStorage

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formulario-pdf");
  
    // Solo almacenamos los campos más comunes de contacto del estudiante
    const campos = [
      "nombres", "apellidos", "ciudad", "cedula", "direccion", "telefono_convencional",
      "direccion", "email", "telefono"
    ];
  
    if (!form) return;
  
    // Recuperar valores almacenados cuando se recarga la página
    campos.forEach(nombre => {
      const campo = form.elements[nombre];
      if (campo && localStorage.getItem(nombre)) {
        campo.value = localStorage.getItem(nombre);
      }
    });
  
    // Guardar automáticamente cuando se modifican los campos
    form.addEventListener("input", () => {
      campos.forEach(nombre => {
        const campo = form.elements[nombre];
        if (campo) {
          localStorage.setItem(nombre, campo.value);
        }
      });
    });
  
    // Limpiar localStorage solo cuando se envía el formulario
    form.addEventListener("submit", () => {
      campos.forEach(nombre => localStorage.removeItem(nombre));
    });
  });
  