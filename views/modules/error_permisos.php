<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Pac-Man con Doctores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f9;
        }
        #tablero {
            display: grid;
            grid-template-columns: repeat(10, 50px);
            grid-template-rows: repeat(10, 50px);
            gap: 2px;
            margin: 20px auto;
            width: 520px;
        }
        .celda {
            width: 50px;
            height: 50px;
            background-color: #e0e0e0;
            border: 1px solid #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
        }
        .moneda {
            background-color: #ffc107;
            border-radius: 50%;
            animation: palpitar 1s infinite ease-in-out;
        }
        .meta {
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
        }
        @keyframes palpitar {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.3);
            }
        }
        .modal {
    display: none; /* Ocultar inicialmente */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro semitransparente */
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 400px;
    text-align: center;
}

.modal-content img {
    width: 100%;
    max-width: 300px;
    border-radius: 10px;
}
    </style>
</head>
<body>
    <div id="gameOverModal" class="modal">
        <div class="modal-content" style="background-color: #fcfafa;">
            <h2>¡Game Over!</h2>
            <img src="../../assets/img/giphy2.gif" alt="Game Over GIF"><br>
            <button id="recargar" style="    color: #fff;
    background-color: #176B87;
    border-color: #176B87;
    box-shadow: 0 6px 10px 2px rgba(0, 0, 0, 0.3) !important;    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;">Volver a jugar</button>
        </div>
    </div>
    <h1>Juego: Doctores en Fila</h1>
    <p>Usa las flechas del teclado para moverte. ¡Recolecta 50 monedas y evita a los doctores!</p>
    <div id="tablero"></div>
    <p id="estado">¡Recolecta todas las monedas y llega a la meta!</p>

    <script>
        // Configuración inicial
        const botonRecargar = document.getElementById("recargar");

botonRecargar.addEventListener("click", () => {
    location.reload(); // Recarga la página
});
        const tablero = document.getElementById("tablero");
        const estado = document.getElementById("estado");

        const filas = 10;
        const columnas = 10;

        // Estado del juego
        const jugador = { x: 0, y: 0 };
        const enemigos = [
            { x: 5, y: 5 },
            { x: 5, y: 6 },
            { x: 5, y: 7 },
            { x: 5, y: 8 }
        ];
        const monedas = [];
        const meta = { x: 9, y: 0 };
        let monedasRecolectadas = 0;
        let juegoTerminado = false;

        // Generar 50 monedas en posiciones aleatorias
        while (monedas.length < 50) {
            const x = Math.floor(Math.random() * columnas);
            const y = Math.floor(Math.random() * filas);
            // Evitar que las monedas aparezcan en la posición inicial del jugador, la meta o un duplicado
            if (
                !(x === jugador.x && y === jugador.y) &&
                !(x === meta.x && y === meta.y) &&
                !monedas.some(m => m.x === x && m.y === y)
            ) {
                monedas.push({ x, y });
            }
        }

        // Crear el tablero
        function crearTablero() {
            tablero.innerHTML = "";
            for (let y = 0; y < filas; y++) {
                for (let x = 0; x < columnas; x++) {
                    const celda = document.createElement("div");
                    celda.classList.add("celda");

                    // Colocar jugador
                    if (jugador.x === x && jugador.y === y) {
                        celda.textContent = "🧑‍⚕️"; // Ícono del jugador
                    }

                    // Colocar enemigos
                    if (enemigos.some(e => e.x === x && e.y === y)) {
                        celda.textContent = "🥼"; // Ícono del enemigo
                    }

                    // Colocar monedas
                    if (monedas.some(m => m.x === x && m.y === y)) {
                        celda.classList.add("moneda");
                        celda.textContent = "💰";
                    }

                    // Colocar meta
                    if (meta.x === x && meta.y === y) {
                        celda.classList.add("meta");
                        celda.textContent = "🏁";
                    }

                    tablero.appendChild(celda);
                }
            }
        }
        // const estado2 = document.getElementById("estado");
const modal = document.getElementById("gameOverModal");
const closeModal = document.getElementById("closeModal");

        // Mover al jugador
        function moverJugador(dx, dy) {
            if (juegoTerminado) return;

            const nuevaX = jugador.x + dx;
            const nuevaY = jugador.y + dy;

            // Verificar límites del tablero
            if (nuevaX >= 0 && nuevaX < columnas && nuevaY >= 0 && nuevaY < filas) {
                jugador.x = nuevaX;
                jugador.y = nuevaY;

                // Verificar si recolecta una moneda
                const indiceMoneda = monedas.findIndex(m => m.x === jugador.x && m.y === jugador.y);
                if (indiceMoneda !== -1) {
                    monedas.splice(indiceMoneda, 1);
                    monedasRecolectadas++;
                    estado.textContent = `¡Recolectaste una moneda! Monedas recolectadas: ${monedasRecolectadas}/50.`;
                }

                // Verificar si llega a la meta
                if (jugador.x === meta.x && jugador.y === meta.y) {
                    if (monedas.length === 0) {
                        estado.textContent = "¡Felicidades! Llegaste a la meta y recolectaste todas las monedas.";
                        juegoTerminado = true;
                    } else {
                        estado.textContent = "¡Llegaste a la meta, pero te faltan monedas por recolectar!";
                    }
                }
            }

            // Verificar si un enemigo atrapa al jugador
            if (enemigos.some(e => e.x === jugador.x && e.y === jugador.y)) {
                estado.textContent = "¡Game Over! Un doctor te atrapó.";
                mostrarModal();
                juegoTerminado = true;
            }

            // Actualizar el tablero
            crearTablero();
        }
        function mostrarModal() {
            modal.style.display = "block";
        }
        // Mover a los enemigos en fila
        function moverEnemigos() {
            if (juegoTerminado) return;

            // Guardar posiciones actuales de los enemigos
            const posicionesAnteriores = enemigos.map(e => ({ x: e.x, y: e.y }));

            // El primer doctor persigue al jugador
            const primerEnemigo = enemigos[0];
            const dx = jugador.x > primerEnemigo.x ? 1 : jugador.x < primerEnemigo.x ? -1 : 0;
            const dy = jugador.y > primerEnemigo.y ? 1 : jugador.y < primerEnemigo.y ? -1 : 0;

            primerEnemigo.x += dx;
            primerEnemigo.y += dy;

            // Los demás doctores siguen al doctor de adelante
            for (let i = 1; i < enemigos.length; i++) {
                enemigos[i].x = posicionesAnteriores[i - 1].x;
                enemigos[i].y = posicionesAnteriores[i - 1].y;
            }

            // Verificar si un enemigo atrapa al jugador
            if (enemigos.some(e => e.x === jugador.x && e.y === jugador.y)) {
                estado.textContent = "¡Game Over! Un doctor te atrapó.";
                juegoTerminado = true;
                mostrarModal();
            }

            // Actualizar el tablero
            crearTablero();

            if (!juegoTerminado) {
                setTimeout(moverEnemigos, 500); // Mover a los enemigos cada 500 ms
            }
        }

        // Escuchar las teclas del teclado
        document.addEventListener("keydown", (event) => {
            switch (event.key) {
                case "ArrowUp":
                    moverJugador(0, -1);
                    break;
                case "ArrowDown":
                    moverJugador(0, 1);
                    break;
                case "ArrowLeft":
                    moverJugador(-1, 0);
                    break;
                case "ArrowRight":
                    moverJugador(1, 0);
                    break;
            }
        });

        // Inicializar el tablero
        crearTablero();
        moverEnemigos();
    </script>
</body>
</html>