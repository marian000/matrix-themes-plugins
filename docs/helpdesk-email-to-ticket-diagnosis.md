# Email-uri Repair Order care nu generează tickete (`stgh_ticket`)

**Data analizei:** 3 august 2026
**Status:** **Fazele 0, 1, 2 implementate** (3 august 2026). Faza 3 (recuperarea celor 10
email-uri) e operațională, necesită acces la cutia poștală. Faza 4 neatinsă.
**Zonă:** `wp-content/plugins/catchers-helpdesk-premium/` + `wp-content/themes/storefront-child/includes/mail-settings.php`

**Decizii confirmate de client:**
- Routare `LFR<n>` → **ticket-ul comenzii originale** (Opțiunea A).
- Referință LF validă dar comandă inexistentă → **ticket orfan + log + alertă**.

---

## 1. Simptom

Email-uri primite în mailbox-ul helpdesk (screenshot furnizat de client) nu apar deloc în
`/wp-admin/edit.php?post_type=stgh_ticket`. Subiectele afectate:

```
Re:Fwd: Repair Order LFR24708 for Original Order LF024708
Re:Fwd: Not Under Warranty - Repair Order LFR24332 for Original Order LF024332
Re:Fwd: Repair Order LFR24520 for Original Order LF024520
Re:Fwd: Repair Order LFR21794 for Original Order LF021794
Re:Fwd: Repair Order LFR23717 for Original Order LF023717
Re:Fwd: Repair Order LFR22113 for Original Order LF022113
Re:Fwd: Not Under Warranty - Repair Order LFR24124 for Original Order LF0...
Re:Fwd: Not Under Warranty - Repair Order LFR24011 for Original Order LF0...
Re:Fwd: Not Under Warranty - Repair Order LFR18652 for Original Order LF0...
Re:Fwd: Not Under Warranty - Repair Order LFR23422 for Original Order LF0...
```

Toate au **același tipar de subiect** și toate sunt forward-uri (`Re:Fwd:`) ale email-urilor
generate automat de site la crearea unei comenzi de reparație.

Fără eroare, fără notificare, fără intrare în log. Drop complet silențios.

---

## 2. Traseul email → ticket

| Pas | Cod |
|-----|-----|
| Cron orar `stgh_load_email_hook` | `src/Core/Stg_Helpdesk_Init.php:37` → `cronTask()` `:200` |
| Listare mail-uri necitite | `stgh_letters_handler()` — `src/mailbox-helpers.php:1055` |
| Per email | `stgh_letter_to_post()` `:1009` → `stgh_letter_save()` `:627` |
| Clasificare ticket / comment | `stgh_letter_type()` `:272` |
| Extragere referință comandă | `stgh_extract_order_number_from_subject()` — `src/order-ref-parser.php:29` |
| Găsire ticket existent | `stgh_find_ticket_for_order()` — `src/order-ref-parser.php:91` |
| Creare ticket nou | `stgh_letter_save()` `:863-970` |

Sursa subiectului (email-ul original, generat de site):

```php
// themes/storefront-child/includes/mail-settings.php:487-488
$subject_prefix = $warranty_find_yes ? '' : 'Not Under Warranty - ';
$subject = $subject_prefix . 'Repair Order LFR' . $order_id_scv . ' for Original Order LF0' . $order_id_scv;
```

`$order_id_scv` este meta `order-id-scv` de pe postul `order_repair`, scris în
`themes/storefront-child/ajax/repair-ajax.php:28`. **Este numărul comenzii WooCommerce
originale** (`_order_number`), nu un ID separat de reparație. Deci în `LFR24708` și `LF024708`
numărul `24708` este **același** și se referă la aceeași comandă.

Confirmare independentă a acestei convenții:
`themes/storefront-child/includes/ajax/container-list.php:20-27` — pentru referințe `LFR*`
caută `order_repair` cu `order-id-scv` = numărul, adică tot numărul comenzii originale.

---

## 3. Cauza primară — regex-ul preferă `LFR` în locul `LF0`

`src/order-ref-parser.php:37`:

```php
if ( ! preg_match( '/(?<![A-Z])LF\s*0*(R?)0*(\d+)/i', $subject, $m ) ) {
    return null;
}
$prefix = strtoupper( $m[1] );   // "R"
$digits = ltrim( $m[2], '0' );   // "24708"
return $prefix . $digits;        // "R24708"
```

`preg_match` returnează **prima** potrivire din subiect. În `... Repair Order LFR24708 for
Original Order LF024708`, prima potrivire este `LFR24708` → funcția întoarce `"R24708"`.

Verificat empiric (rulare PHP pe subiectele reale):

```
Re:Fwd: Repair Order LFR24708 for Original Order LF024708                       => 'R24708'
Re:Fwd: Not Under Warranty - Repair Order LFR24332 for Original Order LF024332  => 'R24332'
Repair Order LFR21794 for Original Order LF021794                               => 'R21794'
Order LF024708 question                                                         => '24708'   ← OK
```

Consecință în lanț:

1. `stgh_find_ticket_for_order('R24708')` — `order-ref-parser.php:103`
   caută `postmeta.meta_key = '_order_number' AND meta_value = 'R24708'`.
   `_order_number` conține **doar cifre** (`24708`). → `order_id = 0`, `ticket_id = 0`.

2. În `stgh_letter_save()` `:722` → `$post_id = 0`.
   Fallback `post_exists($string)` `:725` — subiectul e nou, nu există post cu acest titlu → 0.

3. Se intră pe ramura „creează ticket nou" `:859`.
   `!empty($customer_order)` este **true** (`"R24708"`), deci se merge pe `:863`:

```php
$articles = get_posts(array(
    'post_type'  => 'shop_order',
    'meta_query' => array(array('key' => '_order_number', 'value' => 'R24708', 'compare' => '=')),
));
foreach ($articles as $article) { ... }   // ← array gol, corpul nu rulează niciodată
```

4. `$articles` = `[]` → `foreach` nu execută nimic → `$res` rămâne `false`.
   **Nu se creează ticket, nu se trimite niciun email de alertă, nu se scrie niciun log.**

Ramura de avertizare (`:972`, „This mail has no order ref in subject!") **nu** se atinge,
pentru că `$customer_order` nu e gol — e doar invalid. Exact acest lucru face bug-ul invizibil.

### 3.1 De ce merge pentru comenzile normale

Un subiect ca `Query on order LF024708` nu conține `LFR`, regex-ul întoarce `24708`, lookup-ul
reușește și ticket-ul se creează. Bug-ul lovește **exclusiv** email-urile de reparație, adică
exact tipul din screenshot.

### 3.2 Este `R<n>` vreodată un `_order_number` valid?

Nu. `_order_number` este setat de plugin-ul de numerotare secvențială WooCommerce și e numeric.
Reparațiile sunt CPT `order_repair`, identificate prin meta `order-id-scv`, nu prin
`_order_number`. Ramura `(R?)` din regex nu are niciun consumator valid în cod — este
funcționalitate nefolosită care produce activ pagube.

---

## 4. Cauze secundare (nu blochează singure, dar agravează / ascund problema)

### 4.1 Logging complet dezactivat — zero traceabilitate

`src/Helpers/Stg_Helper_Logger.php:18-31`:

```php
private $_enabled = false;

public function __construct($name, $file = null)
{
//        if(isset($_REQUEST['stglen']) && $_REQUEST['stglen'] == STG_HELPDESK_SALT_USER)
//            $this->_enabled = true;

    if(!$this->_enabled)
        return;
    ...
}
```

Condiția de activare e comentată, `$_enabled` rămâne `false` mereu, iar `log()` face `return`
imediat. **Toate** apelurile `stgh_mailbox_logger()->log(...)` adăugate în `mailbox-helpers.php`
(liniile 211, 223, 227, 630, 713, 733, 736, 861, 974) nu scriu nimic. Directoarele
`catchers-helpdesk*/logs/` sunt goale. De aceea nu există nicio urmă a email-urilor pierdute.

Fără această problemă, incidentul s-ar fi văzut din prima linie de log.

### 4.2 Email-ul e marcat citit chiar dacă procesarea eșuează

`stgh_letter_get()` `:147` → `$connection->getMail($id)`.
În `static-vendor/php-imap/php-imap/src/PhpImap/Mailbox.php:426` semnătura este
`getMail($mailId, $markAsSeen = true)` — deci mesajul este flag-uit `\Seen`.

`stgh_letters_handler()` `:1063` selectează doar `unseen`. Rezultat: un email care eșuează la
procesare **nu mai este niciodată re-încercat**. Cele 10 email-uri din screenshot sunt deja
consumate; după fix trebuie **remarcate manual ca necitite** ca să fie reprocesate.

### 4.3 `foreach` fără `else` — eșec silențios prin construcție

`mailbox-helpers.php:880-964`. Toată logica de creare ticket trăiește în corpul unui `foreach`
peste `$articles`. Când array-ul e gol nu se loghează nimic, nu se notifică nimeni, nu se
returnează eroare. Orice referință de comandă care nu se rezolvă dispare fără urmă. Aceasta este
o problemă structurală independentă de regex — următoarea variantă de subiect nepotrivit va
produce exact același simptom.

De asemenea, limitarea `if ($k < 5)` (`:881`) e un rest de debug fără justificare.

### 4.4 Clasificare `ticket` vs `comment` pe forward-uri

`stgh_letter_type()` `:282-304`: dacă mesajul are header-e `References` + `In-Reply-To`
(orice `Re:` sau `Fwd:` are), `$hasReference = true` → clasificat `comment`.
Apoi `stgh_letter_get_parent_postId()` `:602` caută în `postmeta` `_stgh_references LIKE
'%<msgid>%'`. Dacă găsește accidental un ticket (LIKE parțial pe message-id), email-ul devine
comentariu la un ticket **greșit** în loc de ticket nou — invizibil ca ticket nou în listă.

Pentru email-urile din screenshot lookup-ul cel mai probabil eșuează (email-urile de reparație
sunt trimise de temă prin `wp_mail`, nu prin helpdesk, deci nu au `_stgh_references` salvat) și
se face fallback la `ticket` (`:685-686`). Rămâne totuși un risc real, de verificat.

### 4.5 Fallback `post_exists()` pe titlu

`:725` — `post_exists($string)` face match pe titlu exact, în **toate** tipurile de post.
Verificarea de tip a fost adăugată (`:729`), deci riscul e mitigat, dar rămâne fragil: două
comenzi diferite cu același subiect ar fuziona.

### 4.6 Conexiuni IMAP repetate per email

`stgh_mailbox_connect()` e apelat din nou la fiecare `stgh_letter_get()`, `stgh_letter_type()`,
`stgh_letter_get_reference()`, `stgh_letter_get_in_reply_to()`, `stgh_letter_get_parent_postId()`.
Un singur email deschide ~6-10 conexiuni. La un lot de 10 email-uri (exact cazul din screenshot,
toate primite la 14:41/14:53) se poate atinge timeout PHP sau rate-limit pe serverul de mail →
lotul se pierde parțial, iar mesajele rămân marcate citite (vezi 4.2).

### 4.7 Două plugin-uri helpdesk în `wp-content/plugins/`

`catchers-helpdesk` (free) și `catchers-helpdesk-premium` conțin **ambele**
`stgh_letter_save()`, `stgh_letter_type()`, `stgh_letters_handler()`, toate protejate cu
`if (!function_exists(...))`, și ambele înregistrează `add_action('stgh_load_email_hook', ...)`.

Versiunea free (`catchers-helpdesk/src/mailbox-helpers.php:580-684`) **nu are** logica LF /
`order_ref` / `ticket_id_for_order` — creează un ticket generic din orice email.

Dacă ambele sunt active, câștigă cel încărcat primul, iar `order-ref-parser.php` (inclus doar
din bootstrap-ul premium, `:37`) ar putea nici să nu se încarce. De verificat pe producție ce e
activ. **Nu** este cauza probabilă aici (dacă ar rula versiunea free, tickete generice *ar*
apărea în listă), dar este o mină pentru orice fix viitor.

### 4.8 Ipoteze de eliminat prin verificare pe producție

- **Mailbox greșit.** Screenshot-ul arată `To: dealers@lifetimes...`. Helpdesk-ul citește doar
  cutia `stg_mail_login` (`mailbox-helpers.php:18`). Dacă `dealers@` nu este cutia pollată, sau
  nu redirecționează spre ea, email-urile nici nu ajung la parser.
- **POP3 vs IMAP.** `mail_protocol` default `pop3` (`:25`). Peste POP3 semantica `UNSEEN` din
  `searchMailbox('unseen')` este nesigură.
- **Cron.** `stgh_load_email_hook` poate fi neprogramat (WP-Cron dezactivat / plugin cron).
  Verificabil în WP Crontrol (instalat).
- **HPOS.** Dacă WooCommerce rulează pe High-Performance Order Storage, căutările
  `_order_number` prin `$wpdb->postmeta` și `get_posts('shop_order')` întorc gol **pentru toate**
  comenzile, nu doar pentru reparații. Nu pare cazul (tot codul temei folosește `get_post_meta`
  pe comenzi), dar merge confirmat.

---

## 5. Verificări pe producție (înainte de orice modificare)

| # | Verificare | Cum |
|---|-----------|-----|
| V1 | Ce plugin helpdesk e activ | Plugins → confirmă că *doar* `catchers-helpdesk-premium` e activ |
| V2 | Cutia pollată | Helpdesk → Settings → Mail server: valoarea `stg_mail_login` vs `dealers@lifetimeshutters...` |
| V3 | Protocol + „leave on server" | aceeași pagină: `mail_protocol`, `mail_leave_on_server` |
| V4 | Cron programat | WP Crontrol → există `stgh_load_email_hook`? la ce interval? ultima rulare? |
| V5 | Comenzile există | Pentru 24708, 24520, 21794: `_order_number` = numărul, tip `shop_order`, negol |
| V6 | HPOS | WooCommerce → Settings → Advanced → Features → Order data storage |
| V7 | Tickete deja create greșit | Caută în `stgh_ticket` după `24708` etc.; verifică și `stgh_ticket_comments` (4.4) |
| V8 | Mail-uri consumate | În cutia poștală: sunt cele 10 marcate ca citite? |

V1-V4 sunt blocante: dacă pică, cauza reală e alta și restul planului nu se aplică.

---

## 6. Plan de reparare

Fără implementare acum. Fazele sunt independente și pot fi livrate separat.

### Faza 0 — Observabilitate (prima, obligatorie)

**Fișier:** `src/Helpers/Stg_Helper_Logger.php`

Reactivează logger-ul, dar **fără** condiția originală bazată pe `$_REQUEST` (era un mecanism de
debug prin URL, nesigur). Variantă propusă: activare printr-o constantă în `wp-config.php`
(`STG_HELPDESK_LOG`, default off) sau printr-o opțiune de setări.

Cerințe:
- fișierele de log în `logs/`, cu `.htaccess` deny + `index.php` (deja există `.gitignore` în
  `catchers-helpdesk/logs`, dar nu și în premium);
- rotire sau plafon de mărime — istoricul repo-ului are deja fișiere de log de MB (vezi
  `docs/shutter-module-validation-backlog.md` P3.2);
- fără date sensibile în log (parolele mailbox-ului nu ajung acolo, dar adresele de email da —
  de decis dacă e acceptabil).

Fără această fază, orice fix de mai jos se validează pe ghicite.

**Estimare:** 1-2 h.

### Faza 1 — Fix regex referință comandă (cauza primară)

**Fișier:** `src/order-ref-parser.php`

Comportament țintă:

| Subiect | Rezultat actual | Rezultat corect |
|---------|-----------------|-----------------|
| `Repair Order LFR24708 for Original Order LF024708` | `R24708` | `24708` |
| `Not Under Warranty - Repair Order LFR24332 for Original Order LF024332` | `R24332` | `24332` |
| `Query on order LF024708` | `24708` | `24708` |
| `LFR18652` (fără referința originală) | `R18652` | `18652` |
| `SELF1234` | `null` | `null` |
| `Fără referință` | `null` | `null` |

Decizia de design de confirmat cu clientul înainte de implementare:

> **O comandă de reparație trebuie să ajungă pe ticket-ul comenzii originale, sau pe un ticket
> separat?**
>
> - **Opțiunea A (recomandată)** — `LFR<n>` și `LF0<n>` se normalizează amândouă la `n`.
>   Toată corespondența unei comenzi (inclusiv reparațiile) ajunge pe un singur ticket.
>   Consistent cu `ticket_id_for_order` (o singură meta per comandă) și cu
>   `container-list.php`, unde `LFR<n>` mapează tot pe comanda `n`.
>   Implementare: elimină grupul `(R?)` din regex; `LF\s*0*R?0*(\d+)` → doar cifrele.
>   `stgh_format_order_ref()` returnează întotdeauna `LF0<n>`.
>
> - **Opțiunea B** — ticket separat pentru reparații. Necesită un al doilea mecanism de lookup
>   (CPT `order_repair` prin meta `order-id-scv`) și o meta proprie de tip
>   `ticket_id_for_repair`. Muncă semnificativ mai mare; `ticket_id_for_order` nu poate ține
>   ambele.

Planul de mai jos presupune **Opțiunea A**.

Subpuncte:
1. Regex fără ramura `R`, cu preferință explicită pentru forma numerică.
2. `stgh_format_order_ref()` simplificat (dispare ramura `strpos($customer_order, 'R') === 0`).
3. Un fișier de teste (script PHP standalone, nu PHPUnit — proiectul nu are suită) cu tabelul
   de mai sus plus cazurile marginale: `LF 024708`, `lf024708`, `LF0`, `LF000`,
   subiect cu două comenzi diferite (`LF024708 and LF024520` — se ia prima).

**Estimare:** 2-3 h inclusiv testele.

### Faza 2 — Eliminarea eșecului silențios

**Fișier:** `src/mailbox-helpers.php:859-993`

1. Înlocuiește `foreach ($articles as $article)` cu lookup unic al comenzii
   (`stgh_find_ticket_for_order()` întoarce deja `order_id` — se poate refolosi în loc de un al
   doilea `get_posts`, care duplică interogarea). Elimină `$k < 5`.
2. Adaugă ramura lipsă: referință extrasă dar comandă inexistentă →
   - `stgh_mailbox_logger()->log(...)` cu subiect, referință extrasă și motiv;
   - notificare pe adresa de administrare (aceeași ca la `:977`, `tudor@lifetimeshutters.com`),
     cu text distinct de „no order ref in subject";
   - **decizie de produs:** se creează totuși un ticket „orfan" (ca să nu se piardă mesajul) sau
     doar se alertează? Recomandare: se creează ticket fără `order_id`, ca să fie vizibil în
     `edit.php?post_type=stgh_ticket` și triabil manual.
3. `stgh_letter_save()` să returneze un motiv de eșec explicit, nu doar `false`, pentru ca
   `stgh_letter_to_post()` `:1011` să poată decide corect dacă marchează/șterge mesajul.
4. Nu marca mesajul ca citit când procesarea eșuează (`getMail($id, false)` pe traseele de
   inspecție) — altfel eroarea e ireversibilă (4.2).

**Estimare:** 4-6 h.

### Faza 3 — Reprocesarea celor 10 email-uri pierdute

Nu sunt recuperabile automat (sunt deja `\Seen` și posibil șterse, în funcție de
`mail_leave_on_server`).

Opțiuni, în ordinea preferinței:
1. Dacă mesajele mai există în cutie: marcare manuală ca necitite → rulare manuală
   „Take emails" din Helpdesk → Settings → Mail server (`Stg_Helpdesk_Admin.php:1017`,
   parametru `?takeemails`), după Fazele 1-2.
2. Dacă au fost șterse de pe server: forward din nou din arhiva locală a expeditorului.
3. Dacă nici asta nu e posibil: creare manuală a celor 10 tickete, cu `order_ref` și
   `ticket_id_for_order` completate corect pe comenzile respective.

**Notă:** rularea manuală procesează **toate** mesajele necitite din cutie, nu doar cele 10.
De programat într-un moment controlat.

**Estimare:** 1 h + timpul de coordonare cu clientul.

### Faza 4 — Igienizare (opțional, după stabilizare)

- **P4.1** Verifică dacă `catchers-helpdesk` (free) mai e necesar; dacă nu, dezactivare +
  ștergere. Reduce riscul de coliziune `function_exists` / dublu `stgh_load_email_hook`.
- **P4.2** O singură conexiune IMAP per lot, injectată în funcții, în loc de
  `stgh_mailbox_connect()` la fiecare apel (4.6).
- **P4.3** `stgh_letter_type()` — nu clasifica drept `comment` doar pe baza prezenței
  header-elor `References`/`In-Reply-To`; cere și potrivire efectivă în `_stgh_references` (4.4).
- **P4.4** `attachmentDir` hardcodat la `/home/dematrix/public_html/...`
  (`mailbox-helpers.php:27`) — de înlocuit cu `wp_get_upload_dir()`. Rupe orice
  staging/migrare, iar atașamentele email-urilor se pierd tăcut.
- **P4.5** Blocuri de cod mort: `stgh_letter_save_attachments()` `:524-568` are ramurile `if` și
  `else` identice; `move_uploaded_file` comentat în ambele.

---

## 7. Plan de testare

**Fără acces la producție** (mediu local sau staging cu o cutie de test):

| # | Caz | Așteptat |
|---|-----|----------|
| T1 | `Repair Order LFR99999 for Original Order LF099999`, comanda 99999 există, fără ticket | Ticket nou, `order_ref = LF099999`, `ticket_id_for_order` setat pe comandă |
| T2 | Același subiect, a doua oară | Fără ticket nou; comentariu pe ticket-ul existent |
| T3 | `Not Under Warranty - Repair Order LFR99999 for Original Order LF099999` | Identic cu T1/T2 (prefixul nu schimbă routarea) |
| T4 | `Re:Fwd:` pe același subiect | Identic; prefixele se elimină corect |
| T5 | Comandă inexistentă (`LFR88888`) | Fără ticket pierdut: log + alertă + (dacă se decide) ticket orfan |
| T6 | Subiect fără referință LF | Ramura existentă `:972` — alertă „no order ref" |
| T7 | Ticket închis + email nou pe aceeași comandă | Ticket redeschis (`:795`) |
| T8 | Email cu atașamente | Atașamente salvate în locația corectă (depinde de P4.4) |
| T9 | Lot de 10 email-uri într-o singură rulare cron | Toate 10 procesate, fără timeout |
| T10 | Rulare eșuată (mailbox down) | Mesajele rămân necitite, se reprocesează la rularea următoare |

---

## 8. Riscuri

| Risc | Impact | Mitigare |
|------|--------|----------|
| Plugin-ul e sub `.gitignore` (`/wp-content/plugins/*`) | Modificările nu sunt versionate; un update de plugin le șterge | Backup fișiere înainte; ia în calcul whitelist în `.gitignore` (cum e făcut deja pentru `shutter-module`) |
| Rulare manuală „take emails" | Procesează tot ce e necitit, poate genera zeci de tickete și email-uri către clienți | Rulează într-o fereastră controlată, după golirea cutiei de mesaje irelevante |
| Fazele 1-2 trimit email-uri către clienți (`wp_mail` la `:787` și `:934`) | Notificări nedorite la reprocesarea istoricului | Setează temporar `mail_matrix_radio = send_test` (`mail-settings.php:29`) în timpul testelor |
| Opțiunea A fuzionează reparațiile cu comanda originală | Dacă clientul vrea tickete separate, refacere | Confirmă decizia înainte de Faza 1 |
| Fără suită de teste în proiect | Regresii nedetectate | Script de test standalone pentru parser (Faza 1.3) |

---

## 9. Ce s-a implementat (3 august 2026)

### Faza 0 — `src/Helpers/Stg_Helper_Logger.php`

- `$_enabled` vine acum din constanta `STG_HELPDESK_LOG`. Activarea originală prin
  query-string (`?stglen=<salt>`) a fost eliminată — expunea logging-ul oricui ghicea salt-ul.
- **Necesar pe producție:** în `wp-config.php`
  ```php
  define('STG_HELPDESK_LOG', true);
  ```
  Fără această linie logging-ul rămâne oprit (comportamentul de dinainte).
- Fișierele ajung în `catchers-helpdesk-premium/logs/` — cel relevant e `mailbox.log`.
- Directorul primește automat `.htaccess` (deny) + `index.php` la prima scriere.
- Rotire la 5 MB (`mailbox.log` → `mailbox.log.1`), o singură generație păstrată.
- `fopen` eșuat sau handle invalid → logging-ul se auto-dezactivează. Un log nescriibil nu mai
  poate rupe procesarea email-urilor.

### Faza 1 — `src/order-ref-parser.php`

- Regex: `/(?<![A-Z])LF\s*0*(R?)0*(\d+)/i` → `/(?<![A-Z])LF\s*0*R?\s*0*(\d+)/i`.
  Grupul `R` e consumat și aruncat; `LFR24708` și `LF024708` dau amândouă `24708`.
- `stgh_format_order_ref()` returnează întotdeauna `LF0<n>`; taie defensiv un `R` inițial
  pentru valorile vechi rămase în meta.
- `stgh_find_ticket_for_order()` — `JOIN wp_posts` cu `post_type = 'shop_order'` și
  `post_status != 'trash'`, ca un `_order_number` rătăcit pe alt tip de post să nu fie luat
  drept comandă validă.
- Adăugat `stgh_helpdesk_alert_recipients()` — destinatarii alertelor interne într-un singur
  loc, filtrabil prin `stgh_helpdesk_alert_recipients`. Default `tudor@lifetimeshutters.com`,
  aceeași adresă hardcodată anterior în 3 locuri.

### Faza 2 — `src/mailbox-helpers.php`

- **Bucla eliminată.** `get_posts()` duplicat + `foreach` + `if ($k < 5)` au dispărut.
  Se folosește `$order_id` din lookup-ul deja făcut. Fără iterații = fără eșec silențios.
- **Ramura comandă lipsă.** Referință LF validă dar comandă inexistentă → ticket orfan
  (`_stgh_order_missing = 1`, `order_ref` completat, fără `order_id`), log și alertă pe adresa
  internă. Vizibil în `edit.php?post_type=stgh_ticket` pentru triaj manual.
- **Ramura `saveTicket` eșuat** (comandă găsită, dar inserția pică) → log + alertă. Înainte
  trecea complet neobservată.
- **Crash fix.** `get_userdata($user_id_ticket)` pe un ticket fără `_stgh_contact` returna
  `false`, iar `$user_info->user_email` producea `Error` fatal pe PHP 8. Acum se verifică, se
  loghează și se trimite alertă internă în loc să moară cron-ul.
- **Marcarea ca citit.** `stgh_letter_get()` primește parametrul `$markAsSeen` (default derivat
  din `$delete`): citirile de inspecție nu mai flag-uiesc mesajul. Flag-ul se pune explicit la
  finalul lui `stgh_letter_to_post()`:
  | Situație | Comportament |
  |---|---|
  | Post creat, `mail_leave_on_server` off / POP3 | șters de pe server (ca înainte) |
  | Post creat, păstrat pe server | `markMailAsRead()` — fără reprocesare / duplicat |
  | Fără post, dar mesajul s-a putut citi | `markMailAsRead()` + log — decizia e deja logată și alertată, fără buclă de alerte |
  | Mesajul nu s-a putut citi deloc | rămâne necitit, se reîncearcă la rularea următoare |

  Efect secundar util: un fatal la mijlocul procesării nu mai pierde email-ul. La reîncercare,
  `ticket_id_for_order` (scris imediat după `saveTicket`) face ca mesajul să devină comentariu
  pe ticket-ul existent, nu ticket duplicat.

### Code review pe modificări — ce a ieșit și s-a corectat

**R1. `$res` nu e setat pe ramura „comentariu la ticket existent"** (pre-existent,
`mailbox-helpers.php:741-901`). Când un email ajunge pe o comandă care are deja ticket, se
adaugă comentariul prin `Stg_Helpdesk_TicketComments::addToPost()`, dar `$res` rămâne `false`,
deci `stgh_letter_save()` raportează eșec pe un traseu care a reușit.

Nu se poate seta `$res = $comment_id`: `stgh_letter_to_post()` ar intra apoi pe ramura
`sendEventTicket('ticket_open')` / `('ticket_assign')` cu ID-ul comentariului și ar trimite
notificări „ticket deschis" false către clienți. Lăsat neschimbat intenționat.

Impact asupra modificării mele: prima variantă distingea „nu s-a putut citi" de „gestionat
deja" printr-un al doilea `stgh_letter_get()` — o conexiune IMAP în plus **pentru fiecare**
email de follow-up, plus o linie de log înșelătoare („produced no post"). Corectat cu
`stgh_letter_fetch_failed()` (flag static setat de `stgh_letter_save()`), fără round-trip
suplimentar și cu text de log corect.

**R2. `add_post_meta($res, '_stgh_reply_cc', ...)` cu `$res === false`** (`:1056`,
pre-existent). Se executa ori de câte ori mesajul avea CC și nu se crea post. Adăugat guard
`if ($res && ...)`.

**Verificat, fără modificări necesare:**
- `$post = array()` se reinițializează la intrarea pe ramura `ticket`, deci cheile de comentariu
  nu se scurg în `saveTicket()`.
- Sub POP3, `markMailAsRead()` e no-op, dar traseele care produc ticket șterg oricum mesajul
  (`mail_protocol_visible == 'POP3'`), deci nu apar tichete duplicate.
- Ticket orfan + email ulterior cu același subiect → `post_exists()` (`:725`) îl prinde și
  adaugă comentariu, nu al doilea ticket orfan.
- HPOS: dacă `_customer_user` e gol, se cade pe `$author_id` și se loghează, în loc de fatal.
- `esc_html()` pe subiect și adresă în corpurile HTML ale alertelor.

**Riscuri reziduale, neadresate (necesită decizie):**
- Ambele plugin-uri helpdesk declară aceeași clasă `StgHelpdesk\Helpers\Stg_Helper_Logger` și
  aceleași funcții `stgh_*`. Dacă `catchers-helpdesk` (free) e activ și se încarcă primul,
  **modificările de mai sus nu se aplică deloc**. Vezi V1.
- `.htaccess`-ul din `logs/` nu are efect pe nginx. Dacă serverul e nginx, directorul de log
  trebuie blocat din configurația serverului.

### Teste

`tests/test-order-ref-parser.php` — script standalone, fără WordPress:

```
php wp-content/plugins/catchers-helpdesk-premium/tests/test-order-ref-parser.php
```

40/40 trec. Acoperă subiectele reale din incident, `LFR` singur, `LF R0123`, litere mici,
`SELF1234`, `LF0`/`LF000`, două comenzi în același subiect, input non-string, plus round-trip
`extract() → format()`.

### Neimplementat intenționat

- Faza 3 (recuperare) — operațională.
- Faza 4 (igienizare) — inclusiv `attachmentDir` hardcodat la `/home/dematrix/public_html/`
  (`mailbox-helpers.php:27`), care rupe atașamentele pe orice alt mediu.
- Dezactivarea plugin-ului free `catchers-helpdesk`.

### Atenție: fișierele nu sunt versionate

`.gitignore:15` ignoră `/wp-content/plugins/*`, cu excepție doar pentru `shutter-module`.
Modificările de mai sus **nu sunt în git** și dispar la un update de plugin. Linie de adăugat
dacă se dorește versionarea:

```
!/wp-content/plugins/catchers-helpdesk-premium
```

---

## 10. Rezumat executiv

Email-urile de reparație au în subiect **două** referințe: `LFR<n>` (reparația) și `LF0<n>`
(comanda originală). Parser-ul din `order-ref-parser.php:37` o ia pe prima și produce `"R24708"`,
un număr de comandă care nu există nicăieri în baza de date. Căutarea comenzii întoarce gol,
bucla de creare a ticket-ului nu se execută niciun ciclu, iar funcția returnează `false` fără
niciun log și fără nicio alertă — email-ul rămâne marcat citit și nu mai este reîncercat
vreodată.

Ordinea recomandată: **Faza 0 (logging) → verificările V1-V4 → Faza 1 (regex) → Faza 2
(eșec silențios) → Faza 3 (recuperare)**.
