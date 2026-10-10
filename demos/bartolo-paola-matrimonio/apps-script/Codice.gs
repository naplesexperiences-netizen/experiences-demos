/**
 * Conferme del matrimonio di Bartolo e Paola.
 *
 * Riceve le risposte del modulo del sito e le scrive nel foglio "Conferme"
 * del Foglio Google a cui lo script è collegato. Una riga per email:
 * se un invitato modifica la risposta, la sua riga viene aggiornata.
 *
 * Istruzioni di installazione: LEGGIMI.md nella stessa cartella.
 */

// Lascia vuoto se hai creato lo script da Estensioni → Apps Script dentro il
// Foglio (consigliato). Se invece lo script è separato, incolla qui l'ID del
// Foglio: è la parte dell'indirizzo tra /d/ e /edit.
var SPREADSHEET_ID = '';

var SHEET_NAME = 'Conferme';
var HEADERS = [
  'Aggiornato il', 'Presenza', 'Nome', 'Email', 'Persone',
  'Esigenze alimentari', 'Autobus', 'Posti autobus',
  'Hotel', 'Camere', 'Notti', 'Messaggio'
];
var EMAIL_COLUMN = 4; // colonna "Email", 1 = prima colonna

function doPost(e) {
  var p = (e && e.parameter) || {};

  // Campo trappola: le persone non lo vedono, i programmi di spam lo compilano.
  if (p.sito) return reply({ ok: true });

  var email = clean(p.email, 200).toLowerCase();
  var nome = clean(p.nome, 120);
  if (!email || !nome) return reply({ ok: false, error: 'Nome o email mancanti' });

  var yes = p.presenza === 'si';
  var bus = yes && p.bus === 'si';
  var hotel = yes && p.hotel === 'si';
  var row = [
    new Date(),
    yes ? 'Sì' : 'No',
    nome,
    email,
    yes ? count(p.ospiti) : 0,
    yes ? clean(p.dieta, 300) : '',
    bus ? 'Sì' : 'No',
    bus ? count(p.posti) : '',
    hotel ? 'Sì' : 'No',
    hotel ? count(p.camere) : '',
    hotel ? clean(p.notti, 20).split('+').join(' e ') + ' giugno' : '',
    clean(p.messaggio, 1000)
  ];

  var lock = LockService.getScriptLock();
  try {
    lock.waitLock(10000);
    var sheet = getSheet();
    var existing = findRowByEmail(sheet, email);
    if (existing) {
      sheet.getRange(existing, 1, 1, row.length).setValues([row]);
    } else {
      sheet.appendRow(row);
    }
  } catch (err) {
    console.error(err);
    return reply({ ok: false, error: String(err && err.message || err) });
  } finally {
    lock.releaseLock();
  }
  return reply({ ok: true });
}

// Aprendo l'indirizzo della Web App nel browser si vede se è attiva.
function doGet() {
  return ContentService.createTextOutput('Conferme Bartolo e Paola: attivo.');
}

// Da eseguire dall'editor (menu a tendina accanto a «Esegui»: scegli «prova»).
// Scrive una riga di test nel foglio Conferme; poi puoi cancellarla.
// Non eseguire doPost a mano: lo chiama il sito, con i dati del modulo.
function prova() {
  var risposta = doPost({ parameter: {
    presenza: 'si', nome: 'Prova Prova', email: 'prova@esempio.it', ospiti: '2',
    bus: 'no', hotel: 'no', messaggio: 'Riga di prova: puoi cancellarla'
  } });
  console.log(risposta.getContent());
}

function getSpreadsheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  if (ss) return ss;
  if (SPREADSHEET_ID) return SpreadsheetApp.openById(SPREADSHEET_ID);
  throw new Error('Lo script non è collegato a un Foglio. Crealo dal Foglio ' +
    '(Estensioni → Apps Script) oppure inserisci SPREADSHEET_ID in cima al file.');
}

function getSheet() {
  var ss = getSpreadsheet();
  var sheet = ss.getSheetByName(SHEET_NAME) || ss.insertSheet(SHEET_NAME);
  if (sheet.getLastRow() === 0) {
    sheet.appendRow(HEADERS);
    sheet.setFrozenRows(1);
    sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight('bold');
  }
  return sheet;
}

function findRowByEmail(sheet, email) {
  var last = sheet.getLastRow();
  if (last < 2) return 0;
  var values = sheet.getRange(2, EMAIL_COLUMN, last - 1, 1).getValues();
  for (var i = 0; i < values.length; i++) {
    if (String(values[i][0]).trim().toLowerCase() === email) return i + 2;
  }
  return 0;
}

// Testo pulito e accorciato; un apostrofo davanti a = + - @ impedisce che
// il foglio lo interpreti come formula.
function clean(value, max) {
  var text = String(value || '').trim().slice(0, max || 200);
  return /^[=+\-@]/.test(text) ? "'" + text : text;
}

function count(value) {
  var n = parseInt(value, 10);
  return isNaN(n) ? 0 : Math.max(0, Math.min(20, n));
}

function reply(body) {
  return ContentService.createTextOutput(JSON.stringify(body))
    .setMimeType(ContentService.MimeType.JSON);
}
