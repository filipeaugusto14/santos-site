# Site institucional — Santos Assessoria

Reconstrução em **HTML/CSS/JS estático** do site que estava no Website Builder da Hostinger
(assets do Zyro). Objetivo: migrar 100% do que existe hoje, com o site editável em código.

## Estrutura
```
site/
├── index.html              Início
├── areas-de-atuacao.html   Áreas de atuação
├── santos-alerta.html      Santos Alerta (planos + FAQ)
├── sobre-a-santos.html     Sobre a Santos
├── contato.html            Contato (formulário)
├── enviar.php              Handler do formulário (envia e-mail)
├── .htaccess               URLs limpas + HTTPS + cache (Apache/Hostinger)
└── assets/
    ├── css/style.css       Sistema de design (navy + dourado, DM Sans + Space Grotesk)
    ├── js/site.js          Menu mobile, acordeão do FAQ, envio do formulário
    └── img/                Imagens (baixadas do site original)
```

## Marca
- Fontes: **Space Grotesk** (títulos) + **DM Sans** (texto) — via Google Fonts.
- Cores: navy `#0d1b2e`, dourado `#b8963e`, dourado claro `#f5db99`, fundo suave `#f2f4f7`.

## Pré-visualizar local
Sem PHP local o formulário não envia, mas as páginas renderizam. Com Node:
```
node scripts/serve.js   # ou qualquer servidor estático apontando para esta pasta
```

## Publicar na Hostinger (hospedagem de arquivos)
1. No hPanel, apontar o domínio/subdomínio para a hospedagem (sair do Website Builder).
2. Subir **todo o conteúdo desta pasta** para `public_html` (File Manager, FTP ou Git).
3. O `enviar.php` roda no PHP da Hostinger (função `mail()`); confirmar que o e-mail
   `atendimento@santosassessoria.com` recebe. Ajustar remetente se cair em spam.
4. As URLs limpas (`/areas-de-atuacao`) funcionam pelo `.htaccess`.

## Observações
- **Links de assinatura** (Santos Alerta) apontam para o InfinitePay do Rafael Tadeu — preservados.
- **Corrigido:** o botão "Falar no WhatsApp" da página Contato original estava com número
  placeholder (`5511999990000`). Agora todos usam **(31) 98528-3733**.
- **Área do parceiro / integração com o Hub: NÃO incluída** (será feita depois, conforme combinado).
- Reconstruído em 2026-07-20 a partir de www.santosassessoria.com.
