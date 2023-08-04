

// Array de imágenes de fondo
const backgroundImages = [
    "img/fondo1.jpg",
    "img/fondo2.jpg",
    "img/fondo3.jpg",
];

let currentBackgroundIndex = 0;

// Función para cambiar el fondo
function changeBackground() {
    const profileContainer = document.querySelector('.profile-container');
    profileContainer.style.backgroundImage = `url(${backgroundImages[currentBackgroundIndex]})`;

    currentBackgroundIndex = (currentBackgroundIndex + 1) % backgroundImages.length;
}

// Cambiar el fondo cada 10 segundos
setInterval(changeBackground, 10000);
