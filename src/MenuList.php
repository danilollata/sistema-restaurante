<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Menús</title>
    <style>
        body {
            background-color: #ffe6e6;
            color: #333;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #d9534f;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .contenedor-platos {
            display: flex;
            overflow: hidden;
            justify-content: center;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .plato {
            background-color: #fff;
            border: 2px solid #d9534f;
            border-radius: 10px;
            width: 200px;
            padding: 10px;
            box-sizing: border-box;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .plato:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .imagen-plato {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .nombre-plato {
            color: #d9534f;
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 10px;
        }

        .fade {
            opacity: 1;
            transition: opacity 1s;
        }
        .fade-out {
            opacity: 0;
        }
    </style>
</head>
<body>
    <h1>Lista de Menús</h1>
    <div class="contenedor-platos" id="platosContainer">
    </div>

    <script>
        const platosContainer = document.getElementById('platosContainer');

        function fetchPlatos() {
            fetch('conexionList.php') 
                .then(response => response.json())
                .then(data => {
                    if (!data.platos || data.platos.length === 0) {
                        throw new Error('No se encontraron platos en la respuesta.');
                    }

                    const platos = data.platos;
                    let index = 0;

                    function showPlatos() {
                        platosContainer.innerHTML = '';
                        for (let i = 0; i < 5; i++) {
                            const plato = platos[index % platos.length];
                            platosContainer.innerHTML += `
                                <div class="plato fade">
                                    <img class="imagen-plato" src="${plato.imagen}" alt="${plato.nombre}">
                                    <p class="nombre-plato">${plato.nombre}</p>
                                </div>
                            `;
                            index++;
                        }

                        const platosElements = document.querySelectorAll('.plato');
                        platosElements.forEach((plato, i) => {
                            setTimeout(() => {
                                plato.classList.remove('fade-out');
                            }, i * 200); // Delay each fade-in
                        });

                        setTimeout(() => {
                            platosElements.forEach(plato => {
                                plato.classList.add('fade-out');
                            });
                            setTimeout(showPlatos, 1000);
                        }, 4000); 
                    }

                    showPlatos();
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        fetchPlatos();
    </script>
</body>
</html>
