// Agregar un evento de clic a los botones de eliminar libros
document.addEventListener("DOMContentLoaded", function () {
    const deleteBookButtons = document.querySelectorAll(".btn-delete-book");
    deleteBookButtons.forEach(button => {
        button.addEventListener("click", function () {
            const bookId = this.dataset.bookId;
            if (confirm("¿Estás seguro de que deseas eliminar este libro?")) {
            }
        });
    });
});
