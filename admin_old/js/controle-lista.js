/*
function calcularPermanenciaEmMinutos(entradaTimestamp, saidaTimestamp) {
    // Calcula a diferença em segundos entre os dois timestamps
    const diferencaEmSegundos = saidaTimestamp - entradaTimestamp;
    
    // Converte a diferença de segundos para minutos
    const diferencaEmMinutos = diferencaEmSegundos / 60;
    
    // Retorna o resultado arredondado para o inteiro mais próximo
    return Math.round(diferencaEmMinutos);
}
*/
function calcularPermanenciaEmMinutos(entradaTimestamp, saidaTimestamp) {
    // Calcula a diferença em segundos entre os dois timestamps
    const diferencaEmSegundos = saidaTimestamp - entradaTimestamp;
    
    // Converte a diferença de segundos para minutos completos
    const diferencaEmMinutos = diferencaEmSegundos / 60;
    
    // Retorna o valor inteiro, descartando os segundos restantes
    return Math.floor(diferencaEmMinutos);
}

function formatCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        while (cpf.length < 11) {
            cpf = '0' + cpf;
        }
        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        return cpf;
    }

    function updateElapsedTime() {
        $('.tabela-lista-controle tr').each(function() {
            var horaEntrada = $(this).find('.hora-entrada').text();
            var horaSaida = $(this).find('.hora-saida').text();
            if (horaEntrada && horaSaida) {
                var partsEntrada = horaEntrada.split(':');
                var partsSaida = horaSaida.split(':');

                // Criar DateTime para a entrada e saída, usando a data atual para os componentes de data
                var now = new Date();
                var entradaDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), partsEntrada[0], partsEntrada[1], partsEntrada[2]);
                var saidaDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), partsSaida[0], partsSaida[1], partsSaida[2]);

                // Calcular o tempo decorrido
                var elapsed = new Date(now - entradaDate);

                var hours = String(elapsed.getUTCHours()).padStart(2, '0');
                var minutes = String(elapsed.getUTCMinutes()).padStart(2, '0');
                var seconds = String(elapsed.getUTCSeconds()).padStart(2, '0');

                var timeString = hours + ':' + minutes + ':' + seconds;
                $(this).find('.tdecorrido').text(timeString);

                // Verificar se o tempo atual excede a hora de saída
                if (now > saidaDate) {
                    $(this).addClass('expired');
                } else {
                    $(this).removeClass('expired');
                }
            }
        });
    }