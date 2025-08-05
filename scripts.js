document.addEventListener("DOMContentLoaded", function() {
    const horarioSelect = document.getElementById('horario');
    
    // Função para gerar os horários disponíveis
    function gerarHorarios() {
        const horarios = [];
        const intervalos = 20; // Intervalo de 20 minutos
        let hora = 8; // Começar às 08:30
        let minuto = 30;
        
        // Gerar horários até 18:50
        while (hora < 18 || (hora === 18 && minuto <= 50)) {
            if ((hora === 11 && minuto >= 30) && (hora === 13 && minuto <= 10)) {
                // Pular o intervalo de 11:30 a 13:10
                if (hora === 11 && minuto >= 30) {
                    hora = 13;
                    minuto = 10;
                }
            }

            // Adicionar horário no formato HH:MM
            horarios.push(`${String(hora).padStart(2, '0')}:${String(minuto).padStart(2, '0')}`);
            minuto += intervalos;
            if (minuto >= 60) {
                minuto = 0;
                hora++;
            }
        }

        // Preencher o select com os horários
        horarios.forEach(horario => {
            const option = document.createElement('option');
            option.value = horario;
            option.textContent = horario;
            horarioSelect.appendChild(option);
        });
    }

    gerarHorarios();
});
