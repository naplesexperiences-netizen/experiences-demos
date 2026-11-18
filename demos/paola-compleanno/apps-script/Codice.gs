/**
 * Biglietto di compleanno di Paola: riceve la busta scelta dal sito,
 * la scrive nel foglio "Scelte" del Foglio Google collegato e manda un'email.
 *
 * Facoltativo: senza questo script il sito invia l'email tramite FormSubmit.
 * Istruzioni: LEGGIMI.md nella stessa cartella.
 */

var DESTINATARIO = 'ing.bartolocastellano@gmail.com';
var SPREADSHEET_ID = ''; // vuoto se lo script è creato da dentro il Foglio
var SHEET_NAME = 'Scelte';
var HEADERS = ['Ricevuta il', 'Busta', 'Regalo', 'Scelta il (dispositivo)', 'Prova'];

function doPost(e) {
  var p = (e && e.parameter) || {};
  var scelta = String(p.scelta || '').toUpperCase();
  if (scelta !== 'A' && scelta !== 'B') return reply({ ok: false, error: 'Scelta non valida' });
  var prova = p.prova === 'si';
  var regalo = clean(p.regalo, 300);

  var lock = LockService.getScriptLock();
  try {
    lock.waitLock(10000);
    getSheet().appendRow([new Date(), scelta, regalo, clean(p.quando, 40), prova ? 'Sì' : 'No']);
  } catch (err) {
    console.error(err);
  } finally {
    lock.releaseLock();
  }

  MailApp.sendEmail({
    to: DESTINATARIO,
    subject: (prova ? '[PROVA] ' : '') + 'Paola ha scelto la busta ' + scelta,
    body: 'Paola ha aperto la busta ' + scelta + '.\n\nRegalo: ' + regalo +
      '\n\nScelta il: ' + (p.quando || '') + (prova ? '\n\n(Invio di prova)' : '')
  });
  return reply({ ok: true });
}

function doGet() {
  return ContentService.createTextOutput('Biglietto di Paola: attivo.');
}

// Dall'editor: scegli «prova» accanto a «Esegui». Scrive una riga e manda un'email di prova.
function prova() {
  console.log(doPost({ parameter: {
    scelta: 'A', regalo: 'Riga di prova: puoi cancellarla', quando: new Date().toISOString(), prova: 'si'
  } }).getContent());
}

function getSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet() || (SPREADSHEET_ID && SpreadsheetApp.openById(SPREADSHEET_ID));
  if (!ss) throw new Error('Script non collegato a un Foglio: crealo da Estensioni → Apps Script o imposta SPREADSHEET_ID.');
  var sheet = ss.getSheetByName(SHEET_NAME) || ss.insertSheet(SHEET_NAME);
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(HEADERS);
    sheet.setFrozenRows(1);
    sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight('bold');
  }
  return sheet;
}

function clean(value, max) {
  var text = String(value || '').trim().slice(0, max || 200);
  return /^[=+\-@]/.test(text) ? "'" + text : text;
}

function reply(body) {
  return ContentService.createTextOutput(JSON.stringify(body)).setMimeType(ContentService.MimeType.JSON);
}
