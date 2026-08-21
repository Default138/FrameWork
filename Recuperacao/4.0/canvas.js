
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.createElement('canvas');
    canvas.id = 'meuCanvas';
    canvas.width = 1500;
    canvas.height = 600;
    canvas.style.border = '2px solid black';
    canvas.style.marginTop = '20px';
    canvas.style.backgroundColor = '#f0f0f0';

    const form = document.querySelector('form');
    form.parentNode.insertBefore(canvas, form.nextSibling);

    const ctx = canvas.getContext('2d');

    let formaAtual = null;
    let animacaoId = null;
    let movimentoAtivo = false;
    let posicaoX = 0;
    let posicaoY = 0;
    let direcaoX = 1;
    let direcaoY = 1;

    function desenharCirculo(x, y, raio, corPreenchimento, corBorda) {
        ctx.beginPath();
        ctx.arc(x, y, raio, 0, Math.PI * 2);
        ctx.fillStyle = corPreenchimento;
        ctx.fill();
        ctx.strokeStyle = corBorda;
        ctx.lineWidth = 2;
        ctx.stroke();
    }

    function desenharRetangulo(x, y, largura, altura, corPreenchimento, corBorda) {
        ctx.fillStyle = corPreenchimento;
        ctx.fillRect(x, y, largura, altura);
        ctx.strokeStyle = corBorda;
        ctx.lineWidth = 2;
        ctx.strokeRect(x, y, largura, altura);
    }

    function limparCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#f0f0f0';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }

    function animarForma() {
        if (!movimentoAtivo || !formaAtual) return;

        limparCanvas();

        posicaoX += direcaoX * formaAtual.velocidade;
        posicaoY += direcaoY * formaAtual.velocidade;

        if (formaAtual.tipo === 'circulo') {
            if (posicaoX - formaAtual.raio <= 0 || posicaoX + formaAtual.raio >= canvas.width) {
                direcaoX *= -1;
            }
            if (posicaoY - formaAtual.raio <= 0 || posicaoY + formaAtual.raio >= canvas.height) {
                direcaoY *= -1;
            }

            desenharCirculo(posicaoX, posicaoY, formaAtual.raio,
                formaAtual.corPreenchimento, formaAtual.corBorda);
        } else if (formaAtual.tipo === 'retangulo') {
            if (posicaoX <= 0 || posicaoX + formaAtual.largura >= canvas.width) {
                direcaoX *= -1;
            }
            if (posicaoY <= 0 || posicaoY + formaAtual.altura >= canvas.height) {
                direcaoY *= -1;
            }

            desenharRetangulo(posicaoX, posicaoY, formaAtual.largura, formaAtual.altura,
                formaAtual.corPreenchimento, formaAtual.corBorda);
        }

        animacaoId = requestAnimationFrame(animarForma);
    }

    function iniciarMovimento() {
        if (animacaoId) {
            cancelAnimationFrame(animacaoId);
        }
        movimentoAtivo = true;
        animarForma();
    }

    function pararMovimento() {
        movimentoAtivo = false;
        if (animacaoId) {
            cancelAnimationFrame(animacaoId);
            animacaoId = null;
        }
    }

    function criarForma(event) {
        event.preventDefault();

        const tipo = document.querySelector('select[name="tipo"]').value;
        const corPreenchimento = document.querySelector('select[name="corPreenchimento"]').value;
        const corBorda = document.querySelector('select[name="corBorda"]').value;
        const movimento = document.querySelector('input[name="movimento"]:checked').value;

        if (!tipo) {
            alert('Por favor, selecione um tipo de forma!');
            return;
        }

        if (tipo === 'circulo') {
            const xInput = document.querySelector('input[name="x"]');
            const yInput = document.querySelector('input[name="y"]');
            const raioInput = document.querySelector('input[name="raio"]');

            if (!xInput || !yInput || !raioInput) {
                alert('Por favor, preencha todas as dimensões do círculo!');
                return;
            }

            const x = parseFloat(xInput.value);
            const y = parseFloat(yInput.value);
            const raio = parseFloat(raioInput.value);

            if (isNaN(x) || isNaN(y) || isNaN(raio)) {
                alert('Por favor, preencha valores numéricos válidos!');
                return;
            }

            if (x + raio > canvas.width || x - raio < 0 || y + raio > canvas.height || y - raio < 0) {
                alert('A forma está fora dos limites do canvas! Ajuste as coordenadas.');
                return;
            }

            formaAtual = {
                tipo: 'circulo',
                x: x,
                y: y,
                raio: raio,
                corPreenchimento: corPreenchimento,
                corBorda: corBorda,
                velocidade: 3
            };

            posicaoX = x;
            posicaoY = y;

            limparCanvas();
            desenharCirculo(x, y, raio, corPreenchimento, corBorda);

        } else if (tipo === 'retangulo') {
            const xInput = document.querySelector('input[name="x"]');
            const yInput = document.querySelector('input[name="y"]');
            const larguraInput = document.querySelector('input[name="largura"]');
            const alturaInput = document.querySelector('input[name="altura"]');

            if (!xInput || !yInput || !larguraInput || !alturaInput) {
                alert('Por favor, preencha todas as dimensões do retângulo!');
                return;
            }

            const x = parseFloat(xInput.value);
            const y = parseFloat(yInput.value);
            const largura = parseFloat(larguraInput.value);
            const altura = parseFloat(alturaInput.value);

            if (isNaN(x) || isNaN(y) || isNaN(largura) || isNaN(altura)) {
                alert('Por favor, preencha valores numéricos válidos!');
                return;
            }

            if (x + largura > canvas.width || x < 0 || y + altura > canvas.height || y < 0) {
                alert('A forma está fora dos limites do canvas! Ajuste as coordenadas.');
                return;
            }

            formaAtual = {
                tipo: 'retangulo',
                x: x,
                y: y,
                largura: largura,
                altura: altura,
                corPreenchimento: corPreenchimento,
                corBorda: corBorda,
                velocidade: 3
            };

            posicaoX = x;
            posicaoY = y;

            limparCanvas();
            desenharRetangulo(x, y, largura, altura, corPreenchimento, corBorda);
        }

        if (movimento === 'sim') {
            iniciarMovimento();
        } else {
            pararMovimento();
        }

        console.log('Forma criada:', formaAtual);
        console.log('Movimento:', movimento === 'sim' ? 'Ativado' : 'Desativado');
    }

    document.querySelector('button[type="button"]').addEventListener('click', criarForma);

});