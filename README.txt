README — Bundle personalizzabile per Dietisti (compila solo config.js)

Cosa contiene
-------------
- index.html              (landing ottimizzata per dietisti, con placeholder {{...}})
- config.js               (compila qui i tuoi dati: nome, città, P.IVA, albo, contatti, social, indirizzo)
- fill-config.js          (sostituisce i placeholder in tutta la pagina)
- save-review.php         (salva recensioni in data/reviews.jsonl)
- list-reviews.php        (API JSON per caricare recensioni)
- reviews-auto.js         (caricamento iniziale + bottone "Mostra altre")
- reviews-aggregate.js    (badge media stelle in hero)
- data/                   (cartella vuota — rendila scrivibile: 755/775)

Come usarlo (2 minuti)
----------------------
1) Apri e **compila `config.js`** con i tuoi dati (nome, città, P.IVA, n° albo, telefono, email, social, indirizzo).
2) Carica tutti i file sul server (stessa cartella). Assicurati che `/data` sia scrivibile.
3) (Opz.) Incolla nella pagina il blocco \"Lascia una recensione\" già pronto con `action=\"save-review.php\"` per raccogliere recensioni dal sito.

Note
----
- Niente database: le recensioni sono in `data/reviews.jsonl` (un JSON per riga).
- Il badge in hero si aggiorna leggendo fino a 200 recensioni da `list-reviews.php`.
- I placeholder `{{CHIAVE}}` si aggiornano su TUTTA la pagina e dentro gli attributi (es. mailto:, src, mappe).

Suggerimenti SEO/Local
----------------------
- Mantieni coerenti Nome/Indirizzo/Telefono (NAP) tra sito, Google Business Profile e directory.
- Compila i social in `config.js` per generare segnali di attendibilità.
