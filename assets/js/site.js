/* Santos Assessoria — interações do site */
(function () {
  // menu mobile
  var nav = document.querySelector('.nav');
  var toggle = document.querySelector('.nav__toggle');
  if (toggle && nav) {
    toggle.addEventListener('click', function () { nav.classList.toggle('open'); });
  }

  // acordeão do FAQ
  document.querySelectorAll('.faq__q').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.closest('.faq__item');
      var aberto = item.classList.contains('open');
      // fecha todos e abre o clicado (accordion)
      document.querySelectorAll('.faq__item.open').forEach(function (it) {
        it.classList.remove('open');
        it.querySelector('.faq__a').style.maxHeight = null;
      });
      if (!aberto) {
        item.classList.add('open');
        var a = item.querySelector('.faq__a');
        a.style.maxHeight = a.scrollHeight + 'px';
      }
    });
  });

  // marca o link ativo no menu conforme a página
  var path = location.pathname.replace(/\/$/, '') || '/';
  document.querySelectorAll('.nav__links a').forEach(function (a) {
    var href = a.getAttribute('href').replace(/\/$/, '') || '/';
    if (href === path) a.classList.add('active');
  });

  // formulário de contato (envia para enviar.php)
  var form = document.getElementById('form-contato');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var msg = document.getElementById('form-msg');
      var btn = form.querySelector('button[type="submit"]');
      msg.className = 'form__msg';
      msg.textContent = 'Enviando…';
      btn.disabled = true;
      fetch('enviar.php', { method: 'POST', body: new FormData(form) })
        .then(function (r) { return r.json(); })
        .then(function (d) {
          if (d && d.ok) {
            msg.className = 'form__msg ok';
            msg.textContent = d.msg || 'Recebido! Nossa equipe entra em contato em breve.';
            form.reset();
          } else {
            msg.className = 'form__msg err';
            msg.textContent = (d && d.erro) || 'Não foi possível enviar. Tente pelo WhatsApp.';
          }
        })
        .catch(function () {
          msg.className = 'form__msg err';
          msg.textContent = 'Erro de conexão. Fale com a gente pelo WhatsApp.';
        })
        .finally(function () { btn.disabled = false; });
    });
  }
})();
