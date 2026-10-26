# Insectron Arena — rimando

Questa cartella **non contiene più il gioco**: tiene tre pagine che portano al
sito del gioco, perché i vecchi indirizzi continuino a funzionare e perché la
vetrina dell'hub continui a elencare la demo.

| vecchio indirizzo | porta a |
|---|---|
| `index.html` | la vetrina del gioco |
| `gioca.html` | il gioco |
| `privacy.html` | l'informativa |

**Il gioco si gioca qui:**
<https://naplesexperiences-netizen.github.io/insectorarena/>

**Il progetto sta qui:**
<https://github.com/naplesexperiences-netizen/insectorarena>

Lì dentro c'è tutto: il gioco in un file solo, la documentazione tecnica
(`DOCUMENTAZIONE.md`), la roadmap, i cinque piani, le 31 suite di prove con i
loro 816 controlli, il materiale grafico e la telemetria.

Demo prodotta da **Experiences Srl**.

---

## Perché così, e non cancellando tutto

L'indirizzo `…/experiences-demos/demos/insectron-arena/` è stato pubblico, ed
è quello che compare nelle anteprime condivise da qui: cancellandolo, ogni
link già in giro finirebbe su una pagina che non c'è. Tre file da due
chilobyte lo tengono vivo.

In cambio sono spariti 1,8 MB di roba duplicata — il gioco e 1,5 MB di
schermate identiche a quelle del repository del gioco — e con loro il
problema di tenere allineate due copie dello stesso file.

Le pagine rimandano da sole, con `<meta http-equiv="refresh">` e con
`location.replace()` — che non lascia traccia nella cronologia, così il tasto
«indietro» non ti riporta sul ponte. Chi ha JavaScript spento e il rimando
bloccato trova comunque un pulsante.
