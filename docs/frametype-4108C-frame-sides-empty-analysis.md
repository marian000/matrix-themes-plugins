# Frame Type `4108C` (id 420) — câmpurile Frame Left/Right/Top/Bottom rămân goale

**Data analizei:** 3 august 2026
**Status:** **Faza 1 implementată** (3 august 2026). Faza 2 (sincronizare DB) și Faza 3
(template-uri de edit) necesită verificări pe producție — vezi secțiunea 11.
**Zonă:** `wp-content/plugins/shutter-module/`

**Decizie confirmată de client (3 august 2026):**
- `4108C` (id 420) permite `Yes`, `No` **și** `Sill` pe toate cele 4 laturi.
  → 12 intrări de modificat per fișier, nu 8. Vezi Faza 1.

---

## 1. Simptom

În configuratorul de shutter, la selectarea opțiunii de frame type:

```html
<label>
  Basswood<br> 4108C<br>
  <input type="radio" name="property_frametype" data-code="F70" data-title="4108C" value="420">
  <img src="/wp-content/plugins/shutter-module/imgs/4108C.png">
</label>
```

câmpurile **Frame Left**, **Frame Right**, **Frame Top**, **Frame Bottom** nu mai afișează
nicio valoare (dropdown gol / valoare ștearsă). Pentru celelalte frame types (ex. `4008C`,
id 313) aceleași câmpuri funcționează normal.

---

## 2. Cum funcționează filtrarea (lanțul de execuție)

| Pas | Fișier | Ce face |
|-----|--------|---------|
| 1 | `templates/prod-1-all.php:148-...` | Randează radio-urile frame type dintr-un array PHP `$FRAMETYPE_OPTIONS`. Linia 157 conține `['value' => 420, 'title' => '4108C', 'code' => 'F70', ...]` |
| 2 | `shutter-module.php:244` | Enqueue `js/property-values.js` — definește globalele `property_values` și `property_fields` |
| 3 | `js/property-values.js:2853-2870` | `property_fields`: `10 = frametype`, `11 = frameleft`, `12 = frameright`, `13 = frametop`, `14 = framebottom` |
| 4 | `js/product-script-custom.js:947` | Handler `$("#choose-frametype label").click(...)` |
| 5 | `js/product-script-custom.js:27-41` | `getRelatedFields(10)` → `[11, 12, 13, 14]` (câmpurile care depind de frametype) |
| 6 | `js/product-script-custom.js:57-76` | `getRelatedFieldData(11, 10, '420')` → lista de opțiuni valide pentru Frame Left |
| 7 | `js/product-script-custom.js:183-227` | `loadItems('property_frameleft', field_data)` → re-inițializează select2 cu lista primită |

Filtrul de la pasul 6 este simplu: pentru fiecare valoare cu `property_id == 11`, dacă
`all_property_values == 0`, se parsează `selected_property_values` și se verifică dacă
id-ul frame type-ului selectat apare în `property_value_ids`:

```js
selected_property_values = JSON.parse(property_values[i].selected_property_values);
if (changed_property_id == selected_property_values.property_field) {
    for (j = 0; j < selected_property_values.property_value_ids.length; j++) {
        if (selected_property_values.property_value_ids[j] == value) {
            data.push(property_values[i]);
        }
    }
}
```

Nu există niciun fallback: dacă id-ul nu apare nicăieri, `data` rămâne `[]`.

---

## 3. Cauza principală

**Frame type-ul `420` (`4108C`) nu a fost adăugat în nicio listă `property_value_ids` a
valorilor pentru Frame Left / Right / Top / Bottom.**

Cele 19 valori care depind de frametype (`property_field: "10"`) din
`js/property-values.js`:

| property_id | id valoare | value | conține `313` (4008C) | conține `462` (P4008R) | conține `420` (4108C) |
|---|---|---|---|---|---|
| 11 frameleft | 71 | No | ✅ | ✅ | ❌ |
| 11 | 74 | None | – | – | ❌ |
| 11 | 72 | Sill | – | – | ❌ |
| 11 | 70 | Yes | ✅ | ✅ | ❌ |
| 12 frameright | 76 | No | ✅ | ✅ | ❌ |
| 12 | 79 | None | – | – | ❌ |
| 12 | 77 | Sill | – | – | ❌ |
| 12 | 75 | Yes | ✅ | ✅ | ❌ |
| 13 frametop | 81 | No | ✅ | ✅ | ❌ |
| 13 | 84 | None | – | – | ❌ |
| 13 | 82 | Sill | – | – | ❌ |
| 13 | 135 | Top Track | – | – | ❌ |
| 13 | 80 | Yes | ✅ | ✅ | ❌ |
| 14 framebottom | 86 | No | ✅ | ✅ | ❌ |
| 14 | 89 | None | – | – | ❌ |
| 14 | 87 | Sill | – | – | ❌ |
| 14 | 136 | Track in Board | – | – | ❌ |
| 14 | 151 | M Track | – | – | ❌ |
| 14 | 85 | Yes | ✅ | ✅ | ❌ |

Verificare mecanică:

```
grep -c '"420"' js/property-values.js          → 0
grep -c '"462"' js/property-values.js          → 8
```

`462` (`P4008R`) este precedentul corect: a fost adăugat ca frame type **și** înscris în
cele 8 liste relevante. `420` a fost adăugat doar ca frame type.

### 3.1 De ce câmpul apare gol, nu doar "fără opțiuni"

După bucla de repopulare, același handler setează valorile implicite
(`js/product-script-custom.js:1000-1010`):

```js
$("#property_frameleft").select2("val", '70');
$("#property_frameright").select2("val", '75');
$("#property_framebottom").select2("val", '85');
```

select2 v3 caută id-ul `70` în `data.results` — lista tocmai a fost înlocuită cu `[]`, deci
valoarea nu poate fi rezolvată și se afișează gol. Rezultatul: câmpul nu are opțiuni **și**
pierde valoarea curentă.

### 3.2 De ce `getRelatedFields` nu previne problema

`getRelatedFields(10)` returnează `[11,12,13,14]` indiferent de frame type-ul selectat —
condiția testată este doar `field_id == selected_property_values.property_field`, fără
legătură cu valoarea aleasă. Deci bucla rulează întotdeauna și golește toate cele 4 câmpuri.

---

## 4. Zone afectate

### 4.1 Prod 1 — shutter standard (confirmat)

- Template: `templates/prod-1-all.php:157` — conține `420`
- Sursă date: `js/property-values.js` (globală, `js/product-script-custom.js` **nu** își
  declară propriul `property_values`)
- **Afectat.**

### 4.2 Prod Individual (confirmat, aceeași cauză)

- Template: `templates/prod-individual.php:50` — conține `420`
- Sursă date: `js/product-script-individual.js:1445` — copie locală `var property_values`
  care umbrește globala
- `grep -c '"420"' js/product-script-individual.js` → **0**
- Aceleași 8 intrări (id 70, 71, 75, 76, 80, 81, 85, 86) conțin `313` și `462`, dar nu `420`
- **Afectat.**

### 4.3 Prod 3 — Shutter & Blackout Blind (neafectat)

`js/product3-script-custom.js:1461` are o copie locală mult mai veche a listelor, care nu
conține nici `313`, nici `462`. Template-ul `prod-3.php` nu oferă `4108C`. **În afara
scopului** — nu se modifică.

### 4.4 Prod 5 — Battens (neafectat)

`product5-script-custom.js` / `-edit.js` nu au valori cu `property_field: "10"`. Frame
sides nu există în acest produs.

---

## 5. Probleme secundare descoperite (nu sunt cauza, dar sunt reale)

### S1. `4108C` lipsește din template-urile de edit

```
prod-1-all.php     4108C: 1  ✅
prod-individual.php 4108C: 1 ✅
prod-1.php         4108C: 0  ❌
prod-1-edit.php    4108C: 0  ❌
prod-1-admin.php   4108C: 0  ❌
```

Shortcode-urile `product_shutter1_edit` (`inc/shortcodes.php:52`) și
`product_shutter1_edit_admin` (linia 81) randează template-uri care **nu conțin** opțiunea
`420`. Un shutter salvat cu frametype `4108C` deschis în modul edit nu are radio-ul
corespunzător în DOM → niciun radio nu apare selectat, iar la salvare frame type-ul se
poate pierde sau se resetează la altă valoare.

**De verificat pe producție:** care template este folosit efectiv pentru editare. Dacă
paginile de edit folosesc `prod-1-all.php` (varianta combinată add/edit), S1 nu se
manifestă.

### S2. `image_file_name` greșit pentru id 420

`js/property-values.js:1991` → `image_file_name: '40078C.png'`, copiat din intrarea 313.
Fișierul `imgs/4108C.png` **există**. Impact practic: nul, pentru că frame type-urile sunt
randate ca radio din PHP (`prod-1-all.php` folosește `4108C.png`), nu prin `format()` din
select2. Rămâne o inconsistență de date.

### S3. Cinci copii ale acelorași date

Aceleași liste de dependențe există duplicate în:
`js/property-values.js`, `js/product-script-individual.js`, `js/product3-script-custom.js`,
`js/product5-script-custom.js`, `js/product5-script-custom-edit.js` — plus tabela
`wp_property_values` din DB și array-urile PHP din `templates/prod-*.php` și
`ajax/atributes_array.php`.

Aceasta este cauza **structurală**: orice frame type nou trebuie adăugat manual în 3-4
locuri necorelate, iar omiterea unuia nu produce nicio eroare vizibilă.

### S4. Cod mort care poate deruta

`js/custom-scripts.js:3653-3670` declară `var property_values = getSomething();` și
descarcă `ajax/shutter-values.php` (`SELECT * FROM wp_property_values`) în
`localStorage`/`sessionStorage`. `getSomething()` returnează `undefined` (return-ul e în
callback-ul `.done()`), iar datele nu sunt folosite nicăieri. Nu influențează bug-ul, dar
sugerează fals că sursa de adevăr ar fi DB-ul.

---

## 6. Verificări pe producție înainte de fix

| # | Verificare | Cum |
|---|---|---|
| V1 | Confirmă reproducerea | Deschide configuratorul, selectează `4108C`, DevTools → Console: `getRelatedFieldData` nu e expus, dar `property_values.filter(v=>v.property_id==11)` arată listele; caută `"420"` |
| V2 | Confirmă că nu e cache | Hard reload; verifică versiunea enqueue-ată (`property-values.js?ver=1.0.0` — versiune fixă, **cache busting absent**) |
| V3 | Verifică DB | `SELECT id, property_id, selected_property_values FROM wp_property_values WHERE property_id IN (11,12,13,14);` — vezi dacă tabela conține `420` (dacă da, doar JS-ul e desincronizat) |
| V4 | Verifică existența frame type-ului în DB | `SELECT * FROM wp_property_values WHERE id = 420;` |
| V5 | Confirmă ce template servește editarea | Identifică pagina de edit și shortcode-ul folosit |
| V6 | Verifică comenzi existente cu 420 | `SELECT post_id FROM wp_postmeta WHERE meta_key='property_frametype' AND meta_value='420';` — dacă există, ce valori de frame sides au salvate |
| V7 | Verifică prețul | `4108C` are uplift? Nu apare în `includes/class-pricing-config.php` — de confirmat dacă e intenționat |

---

## 7. Plan de reparare

### Faza 1 — fix minim, restabilește funcționalitatea

**Decizie client confirmată (3 august 2026):** `4108C` permite `Yes`, `No` **și** `Sill`.
Setul este deci mai larg decât al lui `4008C` (id 313), care nu apare în listele `Sill`.

Adaugă `"420"` în `property_value_ids` la **12 intrări** din `js/property-values.js`
(la `Yes`/`No` imediat după `"313"`; la `Sill` la finalul listei — ordinea nu contează
pentru filtru):

| id | property_id | value | linia (aprox.) |
|----|-------------|-------|----------------|
| 71 | 11 frameleft | No | 267 |
| 72 | 11 frameleft | **Sill** | 290 |
| 70 | 11 frameleft | Yes | 302 |
| 76 | 12 frameright | No | 314 |
| 77 | 12 frameright | **Sill** | 337 |
| 75 | 12 frameright | Yes | 349 |
| 81 | 13 frametop | No | 361 |
| 82 | 13 frametop | **Sill** | 384 |
| 80 | 13 frametop | Yes | 407 |
| 86 | 14 framebottom | No | 209 |
| 87 | 14 framebottom | **Sill** | 232 |
| 85 | 14 framebottom | Yes | 255 |

Aceleași 12 intrări în `js/product-script-individual.js`:

| id | value | linia |
|----|-------|-------|
| 71 | No | 1950 |
| 72 | **Sill** | 2008 |
| 70 | Yes | 2037 |
| 76 | No | 2097 |
| 77 | **Sill** | 2155 |
| 75 | Yes | 2184 |
| 81 | No | 2244 |
| 82 | **Sill** | 2302 |
| 80 | Yes | 2360 |
| 86 | No | 1774 |
| 87 | **Sill** | 1832 |
| 85 | Yes | 1890 |

Total: 12 intrări × 2 fișiere = **24 modificări de o linie**.

**Notă:** listele `None` (74, 79, 84, 89), `Top Track` (135), `M Track` (151) și
`Track in Board` (136) **nu** primesc `420` — sunt rezervate frame type-urilor de tracked
(`143`, `144`). `4108C` este un L-frame de insert (`ajax/atributes_array.php:271` →
`'4108C 76.2mm insert L frame'`).

### Faza 2 — sincronizare DB

Dacă V3 arată că tabela `wp_property_values` nu conține `420` în cele 12 rânduri, aplică
același update în DB, ca sursele să nu diverge mai departe.

Cele două grupuri necesită tratament diferit: `Yes`/`No` conțin `"313"` (ancoră de
inserție), `Sill` nu — acolo se adaugă la finalul listei.

```sql
-- BACKUP înainte:
-- CREATE TABLE wp_property_values_bak_20260803 AS SELECT * FROM wp_property_values;

-- Grup 1: Yes / No — inserție după "313"
UPDATE wp_property_values
   SET selected_property_values = REPLACE(selected_property_values, '"313"', '"313","420"')
 WHERE id IN (70,71,75,76,80,81,85,86)
   AND selected_property_values LIKE '%"313"%'
   AND selected_property_values NOT LIKE '%"420"%';

-- Grup 2: Sill — adăugare la finalul array-ului
UPDATE wp_property_values
   SET selected_property_values = REPLACE(selected_property_values, ']}', ',"420"]}')
 WHERE id IN (72,77,82,87)
   AND selected_property_values NOT LIKE '%"420"%';
```

**Atenție:** coloana `selected_property_values` este `varchar(200)`
(`shutter-module.php:145`), iar listele `Yes`/`No` au deja 38-57 de id-uri — depășesc cu
mult 200 de caractere. Dacă DB-ul conține efectiv aceste liste, coloana a fost lărgită
ulterior; **verifică `SHOW CREATE TABLE wp_property_values` înainte de UPDATE**, altfel
MySQL trunchiază silențios (mod non-strict) și corupe JSON-ul.

Verificare după:

```sql
SELECT id, property_id, selected_property_values
  FROM wp_property_values
 WHERE id IN (70,71,72,75,76,77,80,81,82,85,86,87);
```

### Faza 3 — S1: opțiunea în template-urile de edit

Dacă V5 confirmă că `prod-1-edit.php` / `prod-1-admin.php` sunt folosite, adaugă opțiunea
`4108C` și acolo, în același bloc unde apare `4008C`.

### Faza 4 — igienă (opțional, separat)

- **P4.1** Fail-safe în `getRelatedFieldData`: dacă rezultatul e gol pentru un câmp care
  are opțiuni, loghează în consolă în loc să golească select-ul în tăcere. Alternativ,
  păstrează lista anterioară. Previne reapariția aceleiași clase de bug.
- **P4.2** Corectează `image_file_name` pentru id 420 → `'4108C.png'` (S2).
- **P4.3** Elimină codul mort din `custom-scripts.js:3653-3670` (S4).
- **P4.4** Adaugă cache busting la enqueue (`filemtime()` în loc de `'1.0.0'` fix) —
  `shutter-module.php:244`. Altfel fix-ul nu ajunge la browserele cu fișierul în cache.
- **P4.5** Sursă unică de adevăr pentru frame types + dependențe (S3). Efort mare, dar
  fiecare frame type nou repetă acest bug.

---

## 8. Plan de test

| # | Test | Rezultat așteptat |
|---|------|-------------------|
| T1 | Selectează `4108C` în configuratorul prod1 | Frame Left/Right/Top/Bottom afișează `Yes`, `No` **și** `Sill` |
| T2 | Idem, valorile implicite | Left=`Yes`(70), Right=`Yes`(75), Bottom=`Yes`(85), Top=`Yes`(80) |
| T2b | Selectează `Sill` pe fiecare din cele 4 câmpuri, apoi salvează | Valorile 72/77/82/87 persistă în postmeta |
| T3 | Selectează `4008C` (regresie) | Comportament neschimbat — `Sill` **nu** apare (313 nu e în listele Sill) |
| T4 | Selectează `143` (Track in Board) | Bottom = `Track in Board`, restul `Yes` — neschimbat |
| T5 | Selectează `144` (M Track) | Bottom = `M Track`, Top include `Top Track` — neschimbat |
| T6 | Comută `4008C` → `4108C` → `4008C` | Fără valori pierdute, fără erori în consolă |
| T7 | Prod Individual, `4108C` | Identic cu T1 |
| T8 | Salvează un shutter cu `4108C` | `property_frameleft` etc. salvate corect în postmeta |
| T9 | Redeschide în edit acel shutter | Frame type `4108C` selectat, frame sides păstrate (depinde de Faza 3) |
| T10 | Preț | Totalul se calculează, fără `NaN` |
| T11 | XLS / raport producție | `4108C` apare corect (`ajax/atributes_array.php:271`) |

---

## 9. Riscuri

- **R1 — cache.** `property-values.js` e enqueue-at cu versiune fixă `'1.0.0'`. Fără
  P4.4 sau bump manual de versiune, utilizatorii cu fișierul în cache nu văd fix-ul și
  bug-ul pare nereparat.
- **R2 — divergență DB/JS.** Dacă se aplică doar Faza 1 și DB-ul rămâne nesincronizat,
  orice regenerare viitoare a `property-values.js` din DB reintroduce bug-ul.
- **R3 — ~~set greșit de opțiuni~~.** Rezolvat: client a confirmat `Yes` + `No` + `Sill`
  (3 august 2026). `4108C` primește un set mai larg decât `4008C`, care nu are `Sill`.
  Dacă apar reclamații de producție pe combinația `4108C` + `Sill`, aici e decizia.
- **R4 — comenzi deja salvate.** Dacă V6 găsește comenzi cu frametype `420`, acestea au
  probabil frame sides goale sau greșite în postmeta și trebuie corectate manual.
- **R5 — trunchiere DB.** `selected_property_values` e declarat `varchar(200)`
  (`shutter-module.php:145`) iar listele reale depășesc 200 de caractere. Vezi Faza 2 —
  UPDATE-ul fără verificarea schemei poate corupe JSON-ul în mod silențios.

---

## 10. Rezumat executiv

`4108C` (id `420`) a fost adăugat ca opțiune de frame type în template-urile PHP
(`prod-1-all.php:157`, `prod-individual.php:50`) și în tabelul de atribute
(`atributes_array.php:271`), dar **nu** a fost adăugat în listele de dependențe ale
câmpurilor Frame Left/Right/Top/Bottom din `js/property-values.js` și
`js/product-script-individual.js`.

Filtrul `getRelatedFieldData()` nu are fallback: nicio potrivire → listă goală → select2 e
reinițializat fără opțiuni și pierde și valoarea implicită setată imediat după.

Fix: `"420"` în 12 intrări × 2 fișiere = 24 modificări de o linie — `Yes`, `No` și `Sill`
pentru fiecare din cele 4 laturi, conform deciziei clientului. Precedentul parțial există
deja: `462` (`P4008R`) este prezent în cele 8 intrări `Yes`/`No`; intrările `Sill` sunt
specifice lui `4108C`.

---

## 11. Ce s-a implementat (3 august 2026)

### Modificări

| Fișier | Modificări | Detaliu |
|--------|-----------|---------|
| `js/property-values.js` | 12 linii | `"420"` adăugat în `property_value_ids` la id 70, 71, 72, 75, 76, 77, 80, 81, 82, 85, 86, 87 |
| `js/product-script-individual.js` | 12 linii | aceleași 12 intrări din copia locală `var property_values` |
| `shutter-module.php:244` | 1 linie + comentariu | versiune enqueue `shutter-property-values` `1.0.0` → **`1.0.1`** (cache busting, R1) |
| `shutter-module.php:258` | 1 linie | versiune `product-script-individual` `1.6.3` → **`1.6.4`** |

Poziționare: la `Yes`/`No` imediat după `"313"`; la `Sill` la finalul listei. Nicio altă
modificare — diff-ul conține exact 12 linii schimbate per fișier, toate conținând `420`.

### Verificare

`js/property-values.js` și `js/product-script-individual.js`: `node --check` curat.
`shutter-module.php`: `php -l` curat.

Funcția reală `getRelatedFieldData()` rulată pe datele modificate (extrase din fișierul
patch-uit, nu reimplementate):

```
420  4108C           frameleft=[No,Sill,Yes]  frameright=[No,Sill,Yes]  frametop=[No,Sill,Yes]  framebottom=[No,Sill,Yes]
313  4008C           frameleft=[No,Yes]       frameright=[No,Yes]       frametop=[No,Yes]       framebottom=[No,Yes]
143  Track in Board  frameleft=[None,Yes]     frameright=[None,Yes]     frametop=[None,Yes]     framebottom=[None,Track in Board]
144  M Track         frameleft=[None,Yes]     frameright=[None,Yes]     frametop=[None,Top Track,Yes] framebottom=[M Track,None]
```

`4108C` returnează cele 3 opțiuni; `4008C`, `143` și `144` nemodificate (T3, T4, T5).

### Neimplementat — necesită acces la producție

- **Faza 2 (DB).** Nu am acces la `wp_property_values`. Rulează V3; dacă tabela nu conține
  `420`, aplică SQL-ul din Faza 2 **după** verificarea schemei (R5 — `varchar(200)`).
- **Faza 3 (S1).** `prod-1.php`, `prod-1-edit.php`, `prod-1-admin.php` conțin radio-uri
  hardcodate (35-36 bucăți) și **nu au nici `4108C`, nici `P4008R`**. Faptul că `P4008R`
  (id 462, adăugat anterior) funcționează în producție sugerează că aceste template-uri
  **nu mai sunt folosite** — `prod-1-all.php` și `prod-individual.php` generează radio-urile
  dintr-un array PHP și le conțin pe amândouă. De confirmat (V5) înainte de a le atinge sau
  a le șterge.
- **Faza 4 (P4.1-P4.5).** Neatinsă. P4.4 (cache busting cu `filemtime()`) a fost înlocuit
  temporar cu bump manual de versiune.

### De testat pe producție

T1, T2, T2b, T6, T7, T8, T10 din secțiunea 8 — necesită mediul live.
