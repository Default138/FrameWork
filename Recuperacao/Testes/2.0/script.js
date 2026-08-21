
class FormaGeometrica {
    constructor(x, y, corPreenchimento, corBorda, temMovimento) {
        this.x = x;
        this.y = y;
        this.corPreenchimento = corPreenchimento;
        this.corBorda = corBorda;
        this.temMovimento = temMovimento;
        this.dx = 3;
        this.dy = 2;
    }
    
    desenhar(ctx) {
        throw new Error("Método desenhar deve ser implementado");
    }
    
    atualizarMovimento(canvasWidth, canvasHeight) {
        if (this.temMovimento) {
            this.x += this.dx;
            this.y += this.dy;
            this.verificarColisaoBordas(canvasWidth, canvasHeight);
        }
    }
    
    verificarColisaoBordas(canvasWidth, canvasHeight) {
        throw new Error("Método verificarColisaoBordas deve ser implementado");
    }
}

class Circulo extends FormaGeometrica {
    constructor(x, y, raio, corPreenchimento, corBorda, temMovimento) {
        super(x, y, corPreenchimento, corBorda, temMovimento);
        this.raio = raio;
    }
    
    desenhar(ctx) {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.raio, 0, Math.PI * 2);
        ctx.fillStyle = this.corPreenchimento;
        ctx.fill();
        ctx.strokeStyle = this.corBorda;
        ctx.lineWidth = 2;
        ctx.stroke();
    }
    
    verificarColisaoBordas(canvasWidth, canvasHeight) {
        if (this.x + this.raio >= canvasWidth || this.x - this.raio <= 0) {
            this.dx = -this.dx;
        }
        if (this.y + this.raio >= canvasHeight || this.y - this.raio <= 0) {
            this.dy = -this.dy;
        }
    }
}

class Retangulo extends FormaGeometrica {
    constructor(x, y, largura, altura, corPreenchimento, corBorda, temMovimento) {
        super(x, y, corPreenchimento, corBorda, temMovimento);
        this.largura = largura;
        this.altura = altura;
    }
    
    desenhar(ctx) {
        ctx.fillStyle = this.corPreenchimento;
        ctx.fillRect(this.x, this.y, this.largura, this.altura);
        ctx.strokeStyle = this.corBorda;
        ctx.lineWidth = 2;
        ctx.strokeRect(this.x, this.y, this.largura, this.altura);
    }
    
    verificarColisaoBordas(canvasWidth, canvasHeight) {
        if (this.x + this.largura >= canvasWidth || this.x <= 0) {
            this.dx = -this.dx;
        }
        if (this.y + this.altura >= canvasHeight || this.y <= 0) {
            this.dy = -this.dy;
        }
    }
}

// Gerenciador de Formas
const GerenciadorFormas = {
    shapes: [],
    animationId: null,
    canvas: null,
    ctx: null,
    
    init() {
        this.criarCanvas();
        this.configurarEventos();
        this.redesenhar();
    },
    
    criarCanvas() {
        this.canvas = document.createElement('canvas');
        this.canvas.id = 'meuCanvas';
        this.canvas.width = 800;
        this.canvas.height = 500;
        this.canvas.style.border = '2px solid #333';
        this.canvas.style.display = 'block';
        this.canvas.style.marginTop = '20px';
        this.canvas.style.backgroundColor = '#f0f0f0';
        
        const form = document.querySelector('form');
        form.parentNode.insertBefore(this.canvas, form.nextSibling);
        
        this.ctx = this.canvas.getContext('2d');
    },
    
    configurarEventos() {
        const botaoCriar = document.querySelector('button[type="button"]');
        if (botaoCriar) {
            botaoCriar.textContent = 'Criar Forma';
            botaoCriar.addEventListener('click', () => this.criarForma());
        }
    },
    
    criarForma() {
        try {
            const tipo = document.getElementById('tipo').value;
            
            if (!tipo) {
                this.exibirMensagem('Por favor, selecione um tipo de forma.', 'error');
                return;
            }
            
            const corPreenchimento = document.querySelector('select[name="corPreenchimento"]').value;
            const corBorda = document.querySelector('select[name="corBorda"]').value;
            const movimentoRadio = document.querySelector('input[name="movimento"]:checked');
            const temMovimento = movimentoRadio && movimentoRadio.value === 'sim';
            
            let forma = null;
            
            if (tipo === 'circulo') {
                const x = parseFloat(document.querySelector('input[name="x"]')?.value);
                const y = parseFloat(document.querySelector('input[name="y"]')?.value);
                const raio = parseFloat(document.querySelector('input[name="raio"]')?.value);
                
                if (isNaN(x) || isNaN(y) || isNaN(raio) || raio <= 0) {
                    this.exibirMensagem('Preencha todos os campos do círculo corretamente.', 'error');
                    return;
                }
                
                forma = new Circulo(x, y, raio, corPreenchimento, corBorda, temMovimento);
                
            } else if (tipo === 'retangulo') {
                const x = parseFloat(document.querySelector('input[name="x"]')?.value);
                const y = parseFloat(document.querySelector('input[name="y"]')?.value);
                const largura = parseFloat(document.querySelector('input[name="largura"]')?.value);
                const altura = parseFloat(document.querySelector('input[name="altura"]')?.value);
                
                if (isNaN(x) || isNaN(y) || isNaN(largura) || largura <= 0 || isNaN(altura) || altura <= 0) {
                    this.exibirMensagem('Preencha todos os campos do retângulo corretamente.', 'error');
                    return;
                }
                
                forma = new Retangulo(x, y, largura, altura, corPreenchimento, corBorda, temMovimento);
            }
            
            if (forma) {
                this.shapes.push(forma);
                this.exibirMensagem(`Forma criada com sucesso! Total: ${this.shapes.length}`, 'success');
                
                if (temMovimento) {
                    this.iniciarAnimacao();
                } else {
                    if (!this.temFormaComMovimento()) {
                        this.pararAnimacao();
                    }
                    this.redesenhar();
                }
            }
            
        } catch (error) {
            this.exibirMensagem('Erro ao criar forma: ' + error.message, 'error');
        }
    },
    
    temFormaComMovimento() {
        return this.shapes.some(shape => shape.temMovimento === true);
    },
    
    iniciarAnimacao() {
        if (this.animationId === null) {
            this.animar();
        }
    },
    
    pararAnimacao() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
            this.animationId = null;
        }
    },
    
    animar() {
        this.atualizarMovimento();
        this.redesenhar();
        this.animationId = requestAnimationFrame(() => this.animar());
    },
    
    atualizarMovimento() {
        for (let shape of this.shapes) {
            shape.atualizarMovimento(this.canvas.width, this.canvas.height);
        }
    },
    
    redesenhar() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        for (let shape of this.shapes) {
            shape.desenhar(this.ctx);
        }
        
        if (this.shapes.length === 0) {
            this.ctx.font = "16px Arial";
            this.ctx.fillStyle = "#666";
            this.ctx.textAlign = "center";
            this.ctx.fillText("Clique em 'Criar Forma' para adicionar formas ao canvas", this.canvas.width / 2, this.canvas.height / 2);
            this.ctx.textAlign = "left";
        }
    },
    
    exibirMensagem(mensagem, tipo) {
        let msgDiv = document.getElementById('mensagemForma');
        
        if (!msgDiv) {
            msgDiv = document.createElement('div');
            msgDiv.id = 'mensagemForma';
            const botoes = document.querySelector('button[type="button"]');
            botoes.parentNode.insertBefore(msgDiv, botoes.nextSibling);
        }
        
        msgDiv.textContent = mensagem;
        msgDiv.style.color = tipo === 'error' ? 'red' : 'green';
        msgDiv.style.marginTop = '10px';
        msgDiv.style.padding = '5px';
        
        setTimeout(() => {
            if (msgDiv) {
                msgDiv.style.opacity = '0';
                setTimeout(() => {
                    if (msgDiv && msgDiv.parentNode) {
                        msgDiv.textContent = '';
                        msgDiv.style.opacity = '1';
                    }
                }, 500);
            }
        }, 3000);
    },
    
    limparTodasFormas() {
        if (this.shapes.length > 0) {
            this.shapes = [];
            this.pararAnimacao();
            this.redesenhar();
            this.exibirMensagem('Todas as formas foram removidas.', 'success');
        } else {
            this.exibirMensagem('Não há formas para remover.', 'error');
        }
    }
};

// Adicionar botão de limpar sem modificar HTML original
function adicionarBotaoLimpar() {
    const botoesExistentes = document.querySelectorAll('button');
    const botaoLimparExiste = Array.from(botoesExistentes).some(btn => btn.textContent === 'Limpar Todas');
    
    if (!botaoLimparExiste) {
        const botaoCriar = document.querySelector('button[type="button"]');
        const botaoLimpar = document.createElement('button');
        botaoLimpar.textContent = 'Limpar Todas';
        botaoLimpar.type = 'button';
        botaoLimpar.style.marginLeft = '10px';
        botaoLimpar.addEventListener('click', () => GerenciadorFormas.limparTodasFormas());
        botaoCriar.parentNode.insertBefore(botaoLimpar, botaoCriar.nextSibling);
    }
}

// Adicionar contador de formas
function adicionarContadorFormas() {
    let contadorDiv = document.getElementById('contadorFormas');
    
    if (!contadorDiv) {
        contadorDiv = document.createElement('div');
        contadorDiv.id = 'contadorFormas';
        contadorDiv.style.marginTop = '10px';
        contadorDiv.style.fontWeight = 'bold';
        contadorDiv.style.padding = '5px';
        
        const mensagemDiv = document.getElementById('mensagemForma');
        if (mensagemDiv) {
            mensagemDiv.parentNode.insertBefore(contadorDiv, mensagemDiv.nextSibling);
        } else {
            const botaoCriar = document.querySelector('button[type="button"]');
            botaoCriar.parentNode.insertBefore(contadorDiv, botaoCriar.nextSibling);
        }
    }
    
    function atualizarContador() {
        contadorDiv.textContent = `📊 Formas ativas: ${GerenciadorFormas.shapes.length}`;
        requestAnimationFrame(atualizarContador);
    }
    
    atualizarContador();
}

window.addEventListener('DOMContentLoaded', () => {
    GerenciadorFormas.init();
    adicionarBotaoLimpar();
    adicionarContadorFormas();
});