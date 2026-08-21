
;(function (global) {
  "use strict";

  function lerFormulario() {
    const tipo            = document.getElementById("tipo").value;
    const corPreenchimento = document.querySelector('[name="corPreenchimento"]').value;
    const corBorda         = document.querySelector('[name="corBorda"]').value;
    const movimentoEl      = document.querySelector('[name="movimento"]:checked');
    const movimento        = movimentoEl ? movimentoEl.value === "sim" : false;

    const dados = { tipo, corPreenchimento, corBorda, movimento };

    if (tipo === "circulo") {
      dados.x    = parseFloat(document.querySelector('[name="x"]').value)    || 0;
      dados.y    = parseFloat(document.querySelector('[name="y"]').value)    || 0;
      dados.raio = parseFloat(document.querySelector('[name="raio"]').value) || 40;
    } else if (tipo === "retangulo") {
      dados.x       = parseFloat(document.querySelector('[name="x"]').value)       || 0;
      dados.y       = parseFloat(document.querySelector('[name="y"]').value)       || 0;
      dados.largura = parseFloat(document.querySelector('[name="largura"]').value) || 80;
      dados.altura  = parseFloat(document.querySelector('[name="altura"]').value)  || 50;
    }

    return dados;
  }

  // ─────────────────────────────────────────────────
  //  CLASSES DE FORMAS
  // ─────────────────────────────────────────────────

  /**
   * Classe base: encapsula propriedades comuns e
   * a lógica de movimento (bounce).
   */
  class Forma {
    constructor(opcoes) {
      this.corPreenchimento = opcoes.corPreenchimento || "red";
      this.corBorda         = opcoes.corBorda         || "black";
      this.espessuraBorda   = 3;
      this.movimento        = opcoes.movimento        || false;

      // Velocidade inicial aleatória para o bounce
      const velocidade = 2.5;
      this.vx = (Math.random() * 2 - 1) * velocidade;
      this.vy = (Math.random() * 2 - 1) * velocidade;
      // Garante que nunca seja zero
      if (Math.abs(this.vx) < 0.5) this.vx = velocidade;
      if (Math.abs(this.vy) < 0.5) this.vy = velocidade;
    }

    /**
     * Atualiza a posição aplicando bounce nas bordas do canvas.
     * Deve ser chamado a cada frame antes de desenhar.
     * @param {number} larguraCanvas
     * @param {number} alturaCanvas
     */
    atualizar(larguraCanvas, alturaCanvas) {
      if (!this.movimento) return;
      this._mover();
      this._bounce(larguraCanvas, alturaCanvas);
    }

    // Sub-classes devem sobrescrever _mover() e _bounce()
    _mover() {}
    _bounce() {}

    /**
     * Aplica estilos de preenchimento e borda ao contexto 2D.
     * @param {CanvasRenderingContext2D} ctx
     */
    _aplicarEstilos(ctx) {
      ctx.fillStyle   = this.corPreenchimento;
      ctx.strokeStyle = this.corBorda;
      ctx.lineWidth   = this.espessuraBorda;
    }

    // Sub-classes devem sobrescrever desenhar()
    desenhar(ctx) {}
  }

  // ─────────────────────────────────────────────────

  /**
   * Círculo: herda de Forma.
   */
  class Circulo extends Forma {
    constructor(opcoes) {
      super(opcoes);
      this.x    = opcoes.x    || 100;
      this.y    = opcoes.y    || 100;
      this.raio = opcoes.raio || 40;
    }

    _mover() {
      this.x += this.vx;
      this.y += this.vy;
    }

    _bounce(lc, ac) {
      if (this.x - this.raio < 0)       { this.x = this.raio;      this.vx *= -1; }
      if (this.x + this.raio > lc)      { this.x = lc - this.raio; this.vx *= -1; }
      if (this.y - this.raio < 0)       { this.y = this.raio;      this.vy *= -1; }
      if (this.y + this.raio > ac)      { this.y = ac - this.raio; this.vy *= -1; }
    }

    desenhar(ctx) {
      this._aplicarEstilos(ctx);
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.raio, 0, Math.PI * 2);
      ctx.closePath();
      ctx.fill();
      ctx.stroke();
    }
  }

  // ─────────────────────────────────────────────────

  /**
   * Retângulo: herda de Forma.
   */
  class Retangulo extends Forma {
    constructor(opcoes) {
      super(opcoes);
      this.x       = opcoes.x       || 100;
      this.y       = opcoes.y       || 100;
      this.largura = opcoes.largura || 80;
      this.altura  = opcoes.altura  || 50;
    }

    _mover() {
      this.x += this.vx;
      this.y += this.vy;
    }

    _bounce(lc, ac) {
      if (this.x < 0)                   { this.x = 0;             this.vx *= -1; }
      if (this.x + this.largura > lc)   { this.x = lc - this.largura; this.vx *= -1; }
      if (this.y < 0)                   { this.y = 0;             this.vy *= -1; }
      if (this.y + this.altura > ac)    { this.y = ac - this.altura;  this.vy *= -1; }
    }

    desenhar(ctx) {
      this._aplicarEstilos(ctx);
      ctx.beginPath();
      ctx.rect(this.x, this.y, this.largura, this.altura);
      ctx.closePath();
      ctx.fill();
      ctx.stroke();
    }
  }

  // ─────────────────────────────────────────────────
  //  FÁBRICA DE FORMAS
  // ─────────────────────────────────────────────────

  /**
   * Recebe um objeto de opções e retorna a instância correta.
   * Lança erro se o tipo for desconhecido.
   * @param {object} opcoes
   * @returns {Forma}
   */
  function criarForma(opcoes) {
    switch (opcoes.tipo) {
      case "circulo":   return new Circulo(opcoes);
      case "retangulo": return new Retangulo(opcoes);
      default:
        throw new Error(`Tipo de forma desconhecido: "${opcoes.tipo}"`);
    }
  }

  // ─────────────────────────────────────────────────
  //  GERENCIADOR DE CANVAS
  // ─────────────────────────────────────────────────

  /**
   * Cria e gerencia um <canvas> na página.
   * Mantém a lista de formas e o loop de animação.
   */
  class GerenciadorCanvas {
    constructor(largura = 700, altura = 420) {
      this.formas = [];
      this.animacaoId = null;

      // Cria o elemento canvas
      this.canvas = document.createElement("canvas");
      this.canvas.width  = largura;
      this.canvas.height = altura;

      // Estilo visual do canvas
      Object.assign(this.canvas.style, {
        display:      "block",
        margin:       "24px auto",
        borderRadius: "12px",
        border:       "2px solid #334155",
        background:   "linear-gradient(135deg, #0f172a 0%, #1e293b 100%)",
        boxShadow:    "0 8px 32px rgba(0,0,0,0.4)",
        maxWidth:     "100%",
      });

      this.ctx = this.canvas.getContext("2d");

      // Insere o canvas após o formulário
      const form = document.querySelector("form");
      form.insertAdjacentElement("afterend", this.canvas);
    }

    /**
     * Adiciona uma forma ao gerenciador.
     * Inicia (ou reinicia) o loop de animação automaticamente.
     * @param {Forma} forma
     */
    adicionarForma(forma) {
      this.formas.push(forma);
      if (!this.animacaoId) this._iniciarLoop();
    }

    /**
     * Remove todas as formas e para a animação.
     */
    limpar() {
      cancelAnimationFrame(this.animacaoId);
      this.animacaoId = null;
      this.formas = [];
      this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

    // ── Loop interno ──────────────────────────────

    _iniciarLoop() {
      const loop = () => {
        this._renderizar();
        this.animacaoId = requestAnimationFrame(loop);
      };
      this.animacaoId = requestAnimationFrame(loop);
    }

    _renderizar() {
      const { ctx, canvas } = this;
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      // Redesenha o fundo (mantém o gradiente limpo a cada frame)
      const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
      grad.addColorStop(0, "#0f172a");
      grad.addColorStop(1, "#1e293b");
      ctx.fillStyle = grad;
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // Atualiza e desenha cada forma
      for (const forma of this.formas) {
        forma.atualizar(canvas.width, canvas.height);
        forma.desenhar(ctx);
      }
    }
  }

  // ─────────────────────────────────────────────────
  //  INICIALIZAÇÃO — aguarda o DOM estar pronto
  // ─────────────────────────────────────────────────

  function init() {
    const gerenciador = new GerenciadorCanvas(700, 420);

    // Injeta o handler no botão "Criar Forma" sem alterar o HTML
    const botao = document.querySelector('button[type="button"]');
    if (!botao) {
      console.warn("FormasCanvas.js: botão 'Criar Forma' não encontrado.");
      return;
    }

    botao.addEventListener("click", function () {
      const tipo = document.getElementById("tipo").value;

      // Validação básica
      if (!tipo) {
        alert("Por favor, selecione o tipo da forma antes de criar.");
        return;
      }

      // Verifica se os campos de dimensão foram preenchidos
      const xEl = document.querySelector('[name="x"]');
      if (!xEl) {
        alert("Por favor, selecione o tipo da forma para carregar os campos de dimensão.");
        return;
      }

      try {
        const opcoes = lerFormulario();
        const forma  = criarForma(opcoes);
        gerenciador.adicionarForma(forma);
      } catch (e) {
        alert("Erro ao criar forma: " + e.message);
        console.error(e);
      }
    });

    // Expõe o gerenciador globalmente para uso avançado (opcional)
    global.FormasCanvas = {
      gerenciador,
      criarForma,
      Circulo,
      Retangulo,
    };
  }

  // Garante execução após o DOM estar pronto
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

})(window);
