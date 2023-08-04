// Obtener el formulario de búsqueda
const searchForm = document.getElementById("search-form");

// Agregar un evento de envío al formulario de búsqueda
searchForm.addEventListener("submit", function (event) {
  event.preventDefault(); // Prevenir el envío del formulario

  // Obtener el valor del campo de búsqueda
  const searchInput = document.querySelector("input[name='key']").value;

  // Redirigir a la página de búsqueda con el término de búsqueda como parámetro
  window.location.href = `search.php?key=${searchInput}`;
});