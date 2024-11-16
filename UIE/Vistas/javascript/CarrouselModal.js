document.addEventListener('DOMContentLoaded', () => {
    console.log('Script cargado.');

    document.querySelectorAll('.modal').forEach(modal => {
        // Detectar cuándo un modal está mostrado
        modal.addEventListener('shown.bs.modal', () => {
            console.log(`Modal abierto: ${modal.id}`);

            // Seleccionar el contenedor del carrusel dentro de este modal
            const imagesContainer = modal.querySelector('.carousel-images');
            const prevButton = modal.querySelector('.buttonCarrouselModal.prev');
            const nextButton = modal.querySelector('.buttonCarrouselModal.next');
            const images = modal.querySelectorAll('.carousel-images img');
            const totalImages = images.length;

            if (!imagesContainer || totalImages === 0) {
                console.warn('No hay imágenes en este carrusel.');
                return;
            }

            console.log(`Total de imágenes en el carrusel del modal "${modal.id}": ${totalImages}`);

            let currentIndex = 0;

            function updateCarousel() {
                const offset = -currentIndex * 100;
                console.log(`Actualizando carrusel del modal "${modal.id}". Índice actual: ${currentIndex}, Offset: ${offset}%`);
                imagesContainer.style.transform = `translateX(${offset}%)`;
            }

            prevButton.addEventListener('click', () => {
                console.log('Botón anterior clickeado en modal:', modal.id);
                currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                console.log(`Nuevo índice tras clic en "prev" en modal "${modal.id}": ${currentIndex}`);
                updateCarousel();
            });

            nextButton.addEventListener('click', () => {
                console.log('Botón siguiente clickeado en modal:', modal.id);
                currentIndex = (currentIndex + 1) % totalImages;
                console.log(`Nuevo índice tras clic en "next" en modal "${modal.id}": ${currentIndex}`);
                updateCarousel();
            });

            // Inicializar el carrusel al mostrar el modal
            updateCarousel();
        });
    });
});
