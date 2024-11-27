<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrusel de Imágenes Estilizado</title>
    <style>
        .carousel-container {
            width: 100%;
            overflow: hidden;
            position: relative;
            padding: 20px 0;
        }
        .carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-item {
            min-width: 200px;
            margin: 0 10px;
            overflow: hidden;
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .carousel-item .image-container {
            width: 100%;
            height: 150px;
            overflow: hidden;
            border-radius: 10px 10px 0 0; /* Bordes redondeados superiores */
        }
        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .carousel-item:hover img {
            transform: scale(1.1);
        }
        .carousel-item p {
            text-align: center;
            margin: 10px 0;
            padding: 0 5px;
            font-family: Arial, sans-serif;
        }
        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            color: #333;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border: none;
            cursor: pointer;
        }
        .prev { left: 10px; }
        .next { right: 10px; }
    </style>
</head>
<body>
    <div class="carousel-container">
        <div class="carousel">
            <div class="carousel-item">
                <div class="image-container">
                    <img src="uploads/66c8dfcb7f73a.png" alt="Desktops">
                </div>
                <p>Desktops</p>
            </div>
            <div class="carousel-item">
                <div class="image-container">
                    <img src="uploads/66c66e97ca223.png" alt="Laptops">
                </div>
                <p>Laptops</p>
            </div>
            <div class="carousel-item">
                <div class="image-container">
                    <img src="uploads/66c233b85476a.png" alt="Tablets">
                </div>
                <p>Tablets</p>
            </div>
            <div class="carousel-item">
                <div class="image-container">
                    <img src="watch.jpg" alt="Watches">
                </div>
                <p>Watches</p>
            </div>
            <div class="carousel-item">
                <div class="image-container">
                    <img src="tv.jpg" alt="TV & Home">
                </div>
                <p>TV & Home</p>
            </div>
        </div>
        <button class="nav-button prev">&lt;</button>
        <button class="nav-button next">&gt;</button>
    </div>

    <script>
        const carousel = document.querySelector('.carousel');
        const items = document.querySelectorAll('.carousel-item');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');
        let currentIndex = 0;

        function moveCarousel(direction) {
            currentIndex = (currentIndex + direction + items.length) % items.length;
            const translateX = -currentIndex * (items[0].offsetWidth + 20); // 20 es el margen total
            carousel.style.transform = `translateX(${translateX}px)`;
        }

        prevBtn.addEventListener('click', () => moveCarousel(-1));
        nextBtn.addEventListener('click', () => moveCarousel(1));
    </script>
</body>
</html>