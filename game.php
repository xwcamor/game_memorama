<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}
$cardImages = [
    'asset 1.jpg',
    'asset 2.jpg',
    'asset 3.jpg',
    'asset 4.jpg',
    'asset 5.jpg',
    'asset 6.jpg'
];
$cardImages = array_merge($cardImages, $cardImages);
shuffle($cardImages);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Juego de Memoria</title>
    
    <style>
    /* Fondo dinámico con degradado animado */
body {
    margin: 0;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-align: center;
    color: #fff;
    background: url("assets/images/fondo.gif") no-repeat center center fixed;
    background-size: cover;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    backdrop-filter: brightness(0.95) saturate(1.2); 
}

@keyframes gradientBG {
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

h2 {
    color: #e91e63;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
}

a {
    color: #2196F3;
    text-decoration: none;
    font-weight: 600;
}

a:hover {
    text-decoration: underline;
}

#status-panel {
    margin-bottom: 20px;
    font-size: 20px;
    font-weight: 700;
    color: #4CAF50;
    background-color: rgba(255, 255, 255, 0.9);
    padding: 14px 30px;
    border-radius: 30px;
    width: fit-content;
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
    user-select: none;
}

#reset-btn {
    margin: 10px auto 30px auto;
    display: none;
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 14px 40px;
    border-radius: 50px;
    font-size: 20px;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.6);
    transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
    font-weight: 700;
    letter-spacing: 1px;
    user-select: none;
}
#reset-btn:hover {
    background-color: #43a047;
    box-shadow: 0 12px 28px rgba(67, 160, 71, 0.8);
}
#reset-btn:active {
    transform: scale(0.95);
}

/* Tablero de cartas con sombra suave */
#game-board {
    display: grid;
    grid-template-columns: repeat(4, 110px);
    gap: 18px;
    justify-content: center;
}

/* Cartas con efecto 3D y animación */
.card {
    width: 110px;
    height: 110px;
    border-radius: 20px;
    box-shadow: 0 10px 15px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border: 3px solid #fff;
    background-color: #fff;
    user-select: none;
}
.card:hover:not(.matched) {
    transform: scale(1.12) rotate(5deg);
    box-shadow: 0 16px 30px rgba(0,0,0,0.3);
}
.card.matched {
    filter: brightness(1.25) saturate(1.4);
    border: 4px solid #4caf50;
    animation: pulse 0.7s ease infinite alternate;
}

#user-info {
    background-color: rgba(255, 255, 255, 0.85);
    color: #333;
    padding: 12px 30px;
    border-radius: 20px;
    margin-bottom: 20px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
    display: inline-block;
    text-align: center;
}

#user-info h2 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
    color: #d84315;
    text-shadow: none;
}

#user-info a {
    display: inline-block;
    margin-top: 5px;
    color: #1976d2;
    font-weight: bold;
    text-decoration: none;
}
#user-info a:hover {
    text-decoration: underline;
}


/* Animación pulsante para cartas acertadas */
@keyframes pulse {
    0% { transform: scale(1) rotate(0deg);}
    100% { transform: scale(1.05) rotate(3deg);}
}

    </style>
</head>
<body>
<div id="user-info">
    <h2>Bienvenido, <?= $_SESSION["username"] ?></h2>
    <a href="logout.php">Cerrar sesión</a>
</div>

<div id="status-panel">
    Intentos fallidos: <span id="failures">0</span> |
    Pares acertados: <span id="matches">0</span>
</div>

<button id="reset-btn">Jugar de Nuevo</button>

<div id="game-board">
    <?php foreach ($cardImages as $index => $image): ?>
        <img src="assets/images/back.png" data-image="<?= htmlspecialchars($image) ?>" data-index="<?= $index ?>" class="card" onclick="flipCard(this)">
    <?php endforeach; ?>
</div>

<script>
let selectedCards = [];
let failures = 0;
let matchedPairs = 0;

const totalPairs = <?= count($cardImages) / 2 ?>;
const resetBtn = document.getElementById('reset-btn');

function flipCard(card) {
    if (selectedCards.length < 2 && !card.classList.contains('matched')) {
        card.src = 'assets/images/cards/' + card.dataset.image;
        selectedCards.push(card);
        if (selectedCards.length === 2) {
            if (selectedCards[0].dataset.image === selectedCards[1].dataset.image) {
                selectedCards[0].classList.add('matched');
                selectedCards[1].classList.add('matched');
                matchedPairs++;
                document.getElementById('matches').textContent = matchedPairs;
                if (matchedPairs === totalPairs) {
                    alert('¡Felicidades! Has encontrado todos los pares.');
                    resetBtn.style.display = 'block'; // mostrar botón al ganar
                }
                selectedCards = [];
            } else {
                failures++;
                document.getElementById('failures').textContent = failures;
                setTimeout(() => {
                    selectedCards[0].src = 'assets/images/back.png';
                    selectedCards[1].src = 'assets/images/back.png';
                    selectedCards = [];
                }, 1000);
            }
        }
    }
}

resetBtn.addEventListener('click', () => {
    location.reload();
});
</script>

</body>
</html>
