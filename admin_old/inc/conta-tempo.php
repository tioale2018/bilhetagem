<?php if ($_SESSION['evento']['mostra_tempo']>0) { ?>
<div id="session-timer" style="position: fixed;top: 80px;right: 10px;padding: 10px;background: rgba(0, 0, 0, 0.7);color: white;border-radius: 5px;font-size: 16px; z-index: 9999">Tempo restante: <span id="time"></span></div>

<script>
    // Recebe o tempo de expiração da sessão em segundos do PHP
    var timeRemaining = <?php echo $time_remaining; ?>;

    function updateTimer() {
        if (timeRemaining <= 0) {
            document.getElementById('time').textContent = "Sessão expirada";
            return;
        }

        var minutes = Math.floor(timeRemaining / 60);
        var seconds = timeRemaining % 60;
        document.getElementById('time').textContent = minutes + "m " + seconds + "s";

        timeRemaining--; // Decrementa o tempo restante
    }

    // Atualiza o timer a cada segundo
    setInterval(updateTimer, 1000);
    updateTimer(); // Chama imediatamente para exibir o tempo restante
</script>


<?php } ?>