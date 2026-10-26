/* FMA Strutture — schede di modifica: righe ripetibili, scelta immagini, galleria. */
(function ($) {
  'use strict';

  var contatore = Date.now();

  function scegliImmagine(opzioni, alScelta) {
    var frame = wp.media(opzioni);
    frame.on('select', function () { alScelta(frame.state().get('selection').toJSON()); });
    frame.open();
  }

  function anteprima(att) {
    var url = (att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url);
    return $('<img>', { src: url, alt: '' });
  }

  // Singola immagine (logo, foto della camera)
  $(document).on('click', '[data-fma-media] [data-scegli]', function () {
    var box = $(this).closest('[data-fma-media]');
    scegliImmagine({ title: 'Scegli un’immagine', library: { type: 'image' }, multiple: false, button: { text: 'Usa questa immagine' } }, function (sel) {
      box.find('[data-valore]').val(sel[0].id);
      box.find('[data-anteprima]').empty().append(anteprima(sel[0]));
      box.find('[data-togli]').prop('hidden', false);
    });
  });
  $(document).on('click', '[data-fma-media] [data-togli]', function () {
    var box = $(this).closest('[data-fma-media]');
    box.find('[data-valore]').val('');
    box.find('[data-anteprima]').empty();
    $(this).prop('hidden', true);
  });

  // Righe ripetibili (camere, dintorni)
  $(document).on('click', '[data-aggiungi-riga]', function () {
    var rip = $(this).closest('[data-ripetibile]');
    var html = rip.find('template[data-modello]').html().replace(/__i__/g, 'n' + (contatore++));
    var riga = $(html);
    rip.find('[data-righe]').append(riga);
    riga.find('input[type=text], textarea').first().trigger('focus');
  });
  $(document).on('click', '[data-rimuovi-riga]', function () {
    $(this).closest('[data-riga]').remove();
  });
  $('[data-righe]').sortable({ handle: '.fma-riga__maniglia', axis: 'y' });

  // Galleria
  $('[data-galleria]').each(function () {
    var box = $(this), lista = box.find('[data-lista]'), campo = box.find('[data-valore]');
    function sincronizza() {
      campo.val(lista.children().map(function () { return $(this).data('id'); }).get().join(','));
    }
    lista.sortable({ update: sincronizza });
    box.on('click', '[data-aggiungi-foto]', function () {
      scegliImmagine({ title: 'Aggiungi foto alla galleria', library: { type: 'image' }, multiple: 'add', button: { text: 'Aggiungi alla galleria' } }, function (sel) {
        sel.forEach(function (att) {
          if (lista.children('[data-id="' + att.id + '"]').length) return;
          var li = $('<li>').attr('data-id', att.id).append(anteprima(att)).append($('<button type="button" class="fma-galleria__via" aria-label="Rimuovi foto">×</button>'));
          lista.append(li);
        });
        sincronizza();
      });
    });
    box.on('click', '.fma-galleria__via', function () { $(this).closest('li').remove(); sincronizza(); });
  });
})(jQuery);
