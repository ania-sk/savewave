# Code Review: SaveWave — PHP Expense Tracking Application

**Data:** 2026-05-11
**Reviewer:** AI (Claude Opus 4.6)
**Repozytorium:** ahmosman/savewave
**Typ:** Pełny przegląd projektu (standalone, bez powiązania z Redmine)

## Kontekst projektu

SaveWave to aplikacja PHP do zarządzania przychodami, wydatkami i celami oszczędnościowymi. Projekt oparty na custom mini-frameworku (Router, Container, Validator, TemplateEngine) z architekturą MVC. Baza danych MySQL, frontend renderowany server-side z PHP templates.

## Zmienione pliki (pełny projekt)

Przeanalizowano **wszystkie** pliki źródłowe projektu:
- `src/Framework/` — 8 plików (App, Container, Database, Router, TemplateEngine, Validator, Contracts, Exceptions, Rules)
- `src/App/Controllers/` — 10 kontrolerów
- `src/App/Services/` — 5 serwisów
- `src/App/Middleware/` — 8 middleware
- `src/App/views/` — 10+ szablonów + partials + modals
- `public/` — entry point + assets (CSS, JS)
- `database.sql`, `cli.php`, `composer.json`

---

## Znalezione problemy

---

### 🔴 CRITICAL

---

#### C1. `cli.php:7-11` — **[Bezpieczeństwo] Hardcoded credentials bazy danych**

Plik `cli.php` zawiera zahardkodowane dane logowania do bazy danych — użytkownik `root` z pustym hasłem:

```php
$db = new Database('mysql', [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'savewave'
], 'root', '');
```

→ **Sugestia:** Użyj `.env` i `phpdotenv` tak jak w `bootstrap.php`. Plik `cli.php` powinien korzystać z tych samych zmiennych środowiskowych. Dodatkowo upewnij się, że `cli.php` jest w `.gitignore` (obecnie jest, ale lepiej nie commitować takich plików w ogóle).

---

#### C2. `src/App/Config/Routes.php:65-68,70-73` — **[Bezpieczeństwo] Brak AuthRequiredMiddleware na krytycznych endpointach**

Cztery endpointy zarządzania kontem **nie mają** middleware `AuthRequiredMiddleware`:

```php
$app->post('/settings/email/update', [SettingsController::class, 'updateEmail']);           // linia 65
$app->post('/settings/update/username', [SettingsController::class, 'updateUsername']);      // linia 66
$app->post('/settings/update/password', [SettingsController::class, 'updatePassword']);      // linia 67
$app->post('/settings/deleteAccount', [SettingsController::class, 'deleteAccount']);         // linia 68

$app->get('/settings/{category}', [CategoryController::class, 'editCategory']);              // linia 70
$app->post('/settings/{category}', [CategoryController::class, 'updateCategory']);           // linia 71
// linia 72 ma AuthRequiredMiddleware ✓
$app->post('/settings/{category}/delete', [CategoryController::class, 'deleteCategory']);    // linia 73
```

Niezalogowany użytkownik może zmienić email, hasło, usunąć konto dowolnego użytkownika, lub edytować kategorie — wystarczy znać user ID w sesji (lub manipulować sesję).

→ **Sugestia:** Dodaj `->add(AuthRequiredMiddleware::class)` do każdego z tych endpointów.

---

#### C3. `src/App/Controllers/GoalsController.php:79-86` — **[Bezpieczeństwo] Brak autoryzacji w getGoalContributions — IDOR**

Endpoint `/goals/{goal}/contributions` zwraca wpłaty dla dowolnego celu bez sprawdzenia, czy cel należy do zalogowanego użytkownika:

```php
public function getGoalContributions($params)
{
    $goalId = (int)$params['goal'];
    $contributions = $this->goalService->getGoalContributions($goalId);
    // ... zwraca JSON
}
```

→ **Sugestia:** Dodaj sprawdzenie `WHERE user_id = :userId` w query, lub zweryfikuj w kontrolerze, że goal należy do `$_SESSION['user']`.

---

#### C4. `src/App/Middleware/ValidationExceptionMiddleware.php:28` — **[Bezpieczeństwo] Open Redirect via HTTP_REFERER**

Middleware przekierowuje na wartość `$_SERVER['HTTP_REFERER']`, która jest nagłówkiem HTTP kontrolowanym przez klienta:

```php
$referer = $_SERVER['HTTP_REFERER'];
redirectTo($referer);
```

Atakujący może sfałszować nagłówek Referer i przekierować użytkownika na złośliwą stronę (np. phishing page).

→ **Sugestia:** Waliduj `$referer` — sprawdź, czy zaczyna się od oczekiwanego hosta aplikacji, lub użyj stałej ścieżki fallback.

---

#### C5. `src/App/Views/incomes.php:58-59` — **[Bezpieczeństwo] XSS w danych wykresu**

Dane JSON dla wykresów nie są escapowane w atrybutach HTML:

```php
data-income-chart-labels='<?php echo (json_encode($incomeChartLabels, JSON_UNESCAPED_UNICODE)); ?>'
data-income-chart-data='<?php echo (json_encode($incomeChartData)); ?>'
```

Nazwy kategorii (kontrolowane przez użytkownika) mogą zawierać `'` (apostrof), który zamknie atrybut HTML i pozwoli wstrzyknąć kod JS.

**Uwaga:** W `balance.php` te same dane SĄ escapowane przez `e()` — niespójność między widokami.

→ **Sugestia:** Użyj `e(json_encode(...))` wszędzie, lub zastosuj `JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG` jako flagi json_encode. Zastosuj tę samą poprawkę w `expenses.php:53-55`.

---

#### C6. `src/App/Controllers/CategoryController.php:275-283` — **[Błąd logiczny] Undefined variable $redirectTo → błąd runtime**

Metoda `deleteCategory()` odwołuje się do zmiennej `$redirectTo`, która nigdy nie jest zdefiniowana:

```php
public function deleteCategory(array $params)
{
    $id   = (int) $params['category'];
    $type = $_POST['categoryType'] ?? null;
    $this->categoryService->deactivateCategory($id, $type);
    $_SESSION['success'] = 'Category has been deleted successfully!';
    redirectTo($redirectTo ?? '/settings');  // $redirectTo NEVER DEFINED
}
```

W PHP 8+ z `strict_types=1` to wygeneruje `Warning: Undefined variable $redirectTo` i zawsze przekieruje na `/settings`. W najlepszym razie jest to bug, w najgorszym — może powodować nieoczekiwane zachowanie.

→ **Sugestia:** Zmień na `$redirectTo = $_POST['redirect_to'] ?? '/settings';` przed wywołaniem `redirectTo()`.

---

#### C7. `src/App/Controllers/CategoryController.php:113` — **[Błąd logiczny] Zapisanie pełnego $_POST zamiast nazwy kategorii**

W `addNewExpenseCategory()`:

```php
$_SESSION['newCategoryName'] = $_POST;  // CAŁA tablica $_POST!
```

Powinno być:
```php
$_SESSION['newCategoryName'] = $formData['newCategoryName'];
```

Jak w analogicznej metodzie `addNewIncomeCategory()` (linia 37). To powoduje, że w widoku zamiast nazwy kategorii wyświetli się "Array".

---

#### C8. `src/App/Services/TransactionService.php:152-174` — **[Błąd logiczny] getUserExpenses() — niespójna sygnatura metody**

`getUserExpenses()` nie przyjmuje parametru `$userId`, a zamiast tego czyta z `$_SESSION['user']`:

```php
public function getUserExpenses()  // BRAK parametru $userId!
{
    // ... WHERE e.user_id = :user_id
    ['user_id' => $_SESSION['user']]  // hardcoded do sesji
}
```

Ale `getUserIncomes($userId)` przyjmuje parametr. W `getBalance()` (linia 348) wywoływane jest:
```php
$expenses = $this->getUserExpenses($userId);  // $userId jest IGNOROWANY!
```

Równocześnie w `ExpensesController::expenses()` linia 40:
```php
$allExpenses = $this->transactionService->getUserExpenses($userId);  // $userId ignorowany
```

To powoduje, że w niektórych kontekstach mogą być pobierane dane złego użytkownika.

→ **Sugestia:** Dodaj parametr `int $userId` do `getUserExpenses()` i użyj go w query zamiast `$_SESSION['user']`.

---

#### C9. `src/App/Services/CategoryService.php:197-213` — **[Bezpieczeństwo] SQL Injection via dynamiczna nazwa tabeli**

W metodach `deactivateCategory()` i `updateCategoryLimit()` nazwa tabeli jest wstawiana dynamicznie z parametru `$type`:

```php
public function deactivateCategory(int $id, string $type): void
{
    $table = $type === 'income'
        ? 'incomes_category_assigned_to_users'
        : 'expenses_category_assigned_to_users';

    $this->db->query("UPDATE {$table} SET is_active = 0 WHERE ...");
}
```

Parametr `$type` pochodzi z `$_POST['categoryType']` i nie jest walidowany. Jeśli ktoś wyśle inną wartość niż `'income'`, wszystko trafi do tabeli `expenses_category_...` — co może być niepoprawne, ale akurat tu sam SQL injection nie jest możliwy, bo `$table` jest wynikiem ternary expression (zawsze jedna z dwóch stałych). **Jednak brak walidacji `$type`** oznacza, że przy złej wartości kategoria income zostanie zmodyfikowana w tabeli expenses.

→ **Sugestia:** Dodaj walidację: `if (!in_array($type, ['income', 'expense'])) throw new \InvalidArgumentException(...)`.

---

### 🟡 WARNING

---

#### W1. `src/App/Middleware/CsrfGuardMiddleware.php:22` — **[Bezpieczeństwo] Słaba weryfikacja CSRF tokena**

Token CSRF jest porównywany bezpośrednio bez sprawdzenia, czy w ogóle istnieje:

```php
if ($_SESSION['token'] !== $_POST['token']) {
    redirectTo('/');
}
```

Problemy:
1. Jeśli `$_SESSION['token']` nie istnieje (np. sesja wygasła), PHP wygeneruje warning, a porównanie z `null !== null` da `false` — token nie zostanie sprawdzony prawidłowo.
2. Token nie jest rotowany po użyciu (zakomentowany `unset` w linii 26) — ten sam token działa wielokrotnie, co osłabia ochronę.
3. Brak sprawdzenia czy `$_POST['token']` w ogóle istnieje — brak POST tokena nie powinien przechodzić cicho.

→ **Sugestia:** Użyj `hash_equals()` do porównania (timing-safe) i sprawdź istnienie obu tokenów. Rozważ rotację tokena (odkomentuj `unset`).

---

#### W2. `src/App/Services/TransactionService.php` (wiele miejsc) — **[Bezpieczeństwo] Bezpośredni dostęp do $_SESSION w warstwie serwisów**

Serwisy (`TransactionService`, `CategoryService`, `GoalService`) czytają `$_SESSION['user']` bezpośrednio zamiast otrzymywać `userId` jako parametr. To:
- Uniemożliwia testowanie unitowe
- Tworzy ukryte zależności
- Może prowadzić do błędów przy zmianie kontekstu (np. CLI, API)

Przykłady: `TransactionService.php:31,59,170,251,264,280-296,310,324`, `CategoryService.php:81,119,154,184,194,209,229`, `GoalService.php:16,82,103,111,210,229,239`

→ **Sugestia:** Przekaż `$userId` jako parametr z kontrolera do każdej metody serwisu. Jedyne miejsce, które powinno czytać `$_SESSION`, to kontroler.

---

#### W3. `src/App/Controllers/IncomesController.php:39-51, ExpensesController.php:39-50` — **[Wydajność] Paginacja w PHP zamiast w SQL**

Wszystkie rekordy są pobierane z bazy, a następnie przycinane w PHP za pomocą `array_slice()`:

```php
$allIncomes = $this->transactionService->getUserIncomes($userId);
$incomes = array_slice($allIncomes, $offset, $limit);
```

Przy tysiącach rekordów to oznacza ogromne zużycie pamięci i wolne zapytania.

→ **Sugestia:** Użyj `LIMIT :limit OFFSET :offset` w zapytaniu SQL. Dodaj osobne query `SELECT COUNT(*)` do obliczenia liczby stron.

---

#### W4. `src/App/Services/TransactionService.php:332-376` — **[Wydajność] getBalance() wykonuje nadmiarowe zapytania**

`getBalance()` wywołuje 6-8 osobnych zapytań SQL, pobierając WSZYSTKIE transakcje, a następnie oblicza sumy w PHP za pomocą `array_sum()`:

```php
$totalExpense = array_sum(array_column($expenses, 'amount'));
$totalIncome = array_sum(array_column($incomes, 'amount'));
```

Ta metoda jest wywoływana wielokrotnie na jednej stronie (np. `HomePageController` wywołuje `getBalance()`, a potem osobno `getUserIncomes()` i `getUserExpenses()`).

→ **Sugestia:** Użyj `SELECT SUM(amount)` w SQL zamiast pobierania wszystkich rekordów. Dodaj cache na poziomie requestu lub refaktoryzuj, żeby dane były pobierane raz.

---

#### W5. `src/App/Services/CategoryService.php:78-113` — **[Wydajność] Sprawdzanie duplikatów kategorii w PHP zamiast SQL**

`createUserIncomeCategory()` pobiera WSZYSTKIE kategorie użytkownika, iteruje po nich w PHP porównując nazwy:

```php
$categoriesAssignedToUser = $this->getUserAllIncomeCategories($userId);
foreach ($categoriesAssignedToUser as $category) {
    if (strcasecmp($newCategoryName, $category['name']) === 0) { ... }
}
```

→ **Sugestia:** Użyj `SELECT ... WHERE LOWER(name) = LOWER(:name) AND user_id = :uid` zamiast pobierania wszystkich kategorii. Lub dodaj UNIQUE constraint na `(user_id, name)`.

---

#### W6. `src/App/Controllers/SettingsController.php:40-65` — **[Błąd logiczny] updateEmail() kontynuuje po wyjątku walidacji**

Metoda `updateEmail()` łapie `ValidationException` w bloku try-catch, ale po nim kontynuuje wykonywanie (brak `return` w catch):

```php
try {
    $this->validatorService->validateUpdateEmail($_POST);
} catch (ValidationException $e) {
    $_SESSION['errors'] = $e->errors;
    $_SESSION['oldFormData'] = $_POST;
    redirectTo($redirectTo);
    // redirectTo() wywołuje exit — OK, ale to zależy od implementacji
}
// Kod kontynuuje niezależnie od wyjątku...
$this->userService->isEmailTaken($email);
```

Aktualnie działa, bo `redirectTo()` wywołuje `exit`, ale to kruchy design — zmiana implementacji `redirectTo()` spowoduje, że walidacja zostanie pominięta.

→ **Sugestia:** Użyj try-catch obejmującego cały blok (jak w `TransactionsController`), lub dodaj `return` po `redirectTo()`.

---

#### W7. `src/App/Controllers/SettingsController.php:69-86, 88-106` — **[Błąd logiczny] updateUsername/updatePassword — brak obsługi ValidationException**

Metody `updateUsername()` i `updatePassword()` wywołują walidację, ale **nie łapią** `ValidationException`:

```php
public function updateUsername()
{
    // ...
    $this->validatorService->validateUsername($_POST);  // może rzucić wyjątek!
    $this->userService->updateUsername($userId, $newUsername);
    // ...
}
```

Wyjątek walidacji jest łapany dopiero przez `ValidationExceptionMiddleware`, który przekierowuje na `HTTP_REFERER` (co samo w sobie jest CRITICAL — patrz C4). Ale brak ustawienia `$_SESSION['activeForm']` przed walidacją oznacza, że formularz nie otworzy się ponownie z komunikatem błędu w odpowiednim kontekście.

→ **Sugestia:** Dodaj try-catch i ustaw `$_SESSION['activeForm']` w catch, analogicznie jak w `updateEmail()`.

---

#### W8. `src/Framework/Rules/LengthMaxRule.php:18` — **[Błąd logiczny] Brak declare(strict_types=1) + off-by-one + brak mb_strlen**

1. Brakuje `declare(strict_types=1)` (jako jedyny plik w `Framework/Rules/`)
2. Walidacja używa `<` zamiast `<=`, więc string o długości dokładnie `max` przechodzi walidację
3. Używa `strlen()` zamiast `mb_strlen()`, więc polskie znaki (UTF-8) będą liczone jako 2+ bajtów

```php
return strlen($data[$field]) < $length;  // < zamiast <=, strlen zamiast mb_strlen
```

→ **Sugestia:** Użyj `mb_strlen($data[$field], 'UTF-8') <= $length`.

---

#### W9. `src/Framework/Rules/NumericRule.php` — **[Błąd logiczny] Brak declare(strict_types=1)**

Brak `declare(strict_types=1)` w pliku. Niespójne z resztą projektu.

---

#### W10. `src/App/Services/GoalService.php:86-103` — **[Błąd logiczny] updateGoal() nie waliduje właściciela celu**

`updateGoal()` używa `$_SESSION['user']` w WHERE, ale `$formData['goalDate']` może być pustym stringiem (deadline jest opcjonalny wg formularza), co spowoduje błąd SQL:

```php
'deadline' => $formData['goalDate'],  // może być ''
```

W `createNewGoal()` jest warunek `if (!empty($formData['goalDate']))`, ale w `updateGoal()` go brakuje.

→ **Sugestia:** Dodaj taką samą walidację null/empty jak w `createNewGoal()`.

---

#### W11. `src/App/Controllers/TransactionsController.php:19,43` — **[Jakość kodu] Zbędne sprawdzanie REQUEST_METHOD w POST route**

Metody `addIncome()` i `addExpense()` sprawdzają `$_SERVER['REQUEST_METHOD'] === 'POST'`, mimo że router już gwarantuje, że są wywoływane tylko dla POST. To martwy warunek.

→ **Sugestia:** Usuń zbędne warunki `if ($_SERVER['REQUEST_METHOD'] === 'POST')`.

---

#### W12. `src/App/Controllers/TransactionsController.php:34,57` — **[Jakość kodu] Duplikacja logiki redirect w catch**

Bloki catch w `TransactionsController` używają `header("Location: " . $redirectPath); exit();` zamiast istniejącej helper function `redirectTo()`. Niespójne z resztą kodu.

→ **Sugestia:** Użyj `redirectTo($redirectPath)` zamiast ręcznego `header()` + `exit()`.

---

#### W13. `src/Framework/TemplateEngine.php:15-16` — **[Bezpieczeństwo] extract() z niekontrolowanymi danymi**

`extract()` wypłaszcza tablice do zmiennych lokalnych. Jeśli `$data` lub `$globalTemplateData` zawierają klucz np. `this`, `basePath`, może to nadpisać zmienne lokalne i spowodować nieoczekiwane zachowanie.

Flaga `EXTR_SKIP` pomaga (nie nadpisuje istniejących), ale nadal pozwala tworzyć dowolne zmienne.

→ **Sugestia:** Rozważ jawne przekazywanie zmiennych do szablonu lub przynajmniej udokumentuj to ryzyko. Dodaj blacklistę kluczy (`this`, `basePath`, `output`).

---

#### W14. `src/App/Services/UserService.php:91-96` — **[Bezpieczeństwo] logout() nie niszczy sesji**

`logout()` tylko usuwa `$_SESSION['user']` i regeneruje ID, ale nie wywołuje `session_destroy()`. Inne dane sesji (np. CSRF token, flash messages) pozostają, a sesja nadal jest aktywna.

→ **Sugestia:** Wywołaj `session_destroy()` i `session_start()` z nowym ID, lub przynajmniej wyczyść całą tablicę `$_SESSION = []`.

---

#### W15. `database.sql:159-170` — **[Architektura] ALTER TABLE w pliku inicjalizacyjnym**

Plik `database.sql` zawiera `ALTER TABLE` na końcu, dodające kolumny `is_active` i `monthly_limit`. Jeśli plik jest uruchamiany wielokrotnie (np. przez `cli.php`), te ALTER TABLE będą failować z „Duplicate column name".

→ **Sugestia:** Użyj `ALTER TABLE ... ADD COLUMN IF NOT EXISTS` (MySQL 8+) lub przenieś kolumny do definicji `CREATE TABLE`.

---

#### W16. `src/Framework/Router.php:36-77` — **[Architektura] Brak obsługi 404**

Jeśli żaden route nie pasuje, `dispatch()` po prostu nic nie robi — użytkownik widzi pustą stronę bez informacji o błędzie.

→ **Sugestia:** Dodaj obsługę 404 na końcu metody `dispatch()`: `http_response_code(404); echo "404 Not Found";`.

---

#### W17. `src/Framework/Router.php:39` — **[Bezpieczeństwo] HTTP Method Override bez ograniczeń**

```php
$method = strtoupper($_POST['_METHOD'] ?? $method);
```

Pozwala to klientowi na dowolną zmianę metody HTTP via POST. Nie ma listy dozwolonych metod — ktoś mógłby wysłać `_METHOD=PUT` czy inną nieobsługiwaną metodę.

→ **Sugestia:** Ogranicz do dozwolonych metod: `in_array($override, ['DELETE', 'PUT', 'PATCH']) ? $override : $method`.

---

#### W18. `src/App/Services/ValidatorService.php` — **[Jakość kodu] Brak walidacji siły hasła**

Rejestracja i zmiana hasła wymagają tylko `required` — brak minimalnej długości, złożoności, etc.:

```php
'password' => ['required'],
```

→ **Sugestia:** Dodaj regułę `lengthMin:8` lub dedykowaną regułę `passwordStrength`.

---

### ℹ️ INFO

---

- `I1.` **Typos w kodzie/widokach** — Wszędzie "conteiner" zamiast "container" (CSS klasy), "Sing-up" zamiast "Sign-up", "alredy" zamiast "already" (`SessionMiddleware.php:16`), "Remaind" zamiast "Remaining" (`GoalService.php:59`, `goals.php:176`), "Enetr" zamiast "Enter" (`settings.php:218`), "categorie" zamiast "category" (`settings.php:96,172`), "Confrirm" zamiast "Confirm" (`settings.php:246`).

- `I2.` **`UserService.php:100,110`** — Zbędne przypisanie zmiennej w `return $username = ...` i `return $email = ...`. Wystarczy `return $this->db->query(...)->find();`.

- `I3.` **`BalanceController.php:45`** — Zbędna podwójna assgination: `$transactions = $allTransactions = array_slice(...)` — overwrite `$allTransactions`.

- `I4.` **`BalanceController.php:10`** — Whitespace w deklaracji klasy: `class   BalanceController` (podwójna spacja).

- `I5.` **`GoalService.php:248-281`** — Zakomentowany kod (stare wersje metod). Powinien być usunięty — do tego służy git history.

- `I6.` **`Routes.php:52,56`** — Zakomentowane route'y. Analogicznie — powinny być usunięte.

- `I7.` **Masywne naruszenia DRY** — Logika income/expense jest niemal identyczna w kontrolerach, serwisach i widokach. `CategoryService` ma zduplikowane pary metod: `createUserIncomeCategory` / `createUserExpenseCategory`, `getUserActiveIncomeCategories` / `getUserActiveExpenseCategories`, itp. Rozważ parametryzację typu (income/expense) zamiast duplikacji.

- `I8.` **Brak typów zwracanych** — Większość metod nie ma deklaracji return type. Dotyczy praktycznie wszystkich kontrolerów i serwisów. Dodanie return types poprawi czytelność i pomoże IDE.

- `I9.` **`HomePageController.php:14`** — Za długi konstruktor w jednej linii. Podziel na wiele linii jak w innych kontrolerach.

- `I10.` **Brak indeksu na `date_of_income` / `date_of_expense`** — Zapytania `BETWEEN` po datach nie mają indeksu, co przy dużych tabelach spowolni filtrowanie.

- `I11.` **`homePage.php:136`** — Link "View all" dla Expenses prowadzi do `/incomes` zamiast `/expenses`.

- `I12.` **Brak `.htaccess` / konfiguracji serwera** — Nie ma pliku konfigurującego URL rewriting dla Apache/Nginx. Bez tego aplikacja nie będzie działać na typowym hostingu.

- `I13.` **`TransactionsController.php:22-23,45-46`** — Komentarze po polsku ("Próba walidacji danych...") pomieszane z angielskimi. Zachowaj spójność językową.

- `I14.` **`init-project.sh`** — Plik istnieje, ale nie został przeanalizowany. Upewnij się, że nie zawiera hardcoded credentials.

- `I15.` **`database.sql:2`** — Tabela `users` nie ma indeksu na `username`. Jeśli będziesz szukać po username, dodaj indeks.

- `I16.` **`src/Framework/Container.php:20-57`** — `resolve()` jest zdefiniowany, ale nigdy nie jest wywoływany bezpośrednio (kontrolery są rozwiązywane w `Router.php:59`, ale przez `resolve()` a nie `get()`). Metoda `resolve()` nie korzysta z definicji — próbuje auto-wire'ować, ale `get()` wymaga definicji. Dwie drogi rozwiązywania zależności mogą mylić.

- `I17.` **Brak obsługi błędów globalnych** — Nie ma global error/exception handlera. Nieobsłużony wyjątek wyświetli stack trace w produkcji (informacje wrażliwe).

---

## Podsumowanie

Projekt ma solidne fundamenty architektoniczne (custom framework z DI, Router, Middleware pipeline, Validator) — widać zrozumienie wzorców projektowych. Niemniej występują poważne problemy bezpieczeństwa i spójności:

**Najważniejsze kwestie do naprawienia:**
1. 🔴 **Bezpieczeństwo:** Brak `AuthRequiredMiddleware` na endpointach zarządzania kontem (C2) — krytyczna luka pozwalająca na modyfikację konta bez logowania
2. 🔴 **Bezpieczeństwo:** IDOR w `/goals/{goal}/contributions` (C3) — wyciek danych między użytkownikami
3. 🔴 **Bezpieczeństwo:** Open Redirect via `HTTP_REFERER` (C4)
4. 🔴 **Bezpieczeństwo:** XSS w danych wykresu (C5)
5. 🔴 **Bug:** Niespójna sygnatura `getUserExpenses()` (C8) — potencjalnie zwraca dane złego użytkownika
6. 🔴 **Bug:** Undefined variable `$redirectTo` w `deleteCategory()` (C6)
7. 🔴 **Bug:** `$_POST` zamiast nazwy kategorii w sesji (C7)
8. 🟡 **Wydajność:** Paginacja w PHP (W3), nadmiarowe zapytania w `getBalance()` (W4)
9. 🟡 **Jakość:** Masowe naruszenia DRY (I7), `$_SESSION` bezpośrednio w serwisach (W2)

**Pozytywne aspekty:**
- ✅ Poprawne hashowanie haseł (bcrypt, cost 12)
- ✅ Prepared statements (brak SQL injection w standardowych query)
- ✅ CSRF protection (choć wymaga wzmocnienia)
- ✅ Helper `e()` do HTML escaping (choć nie wszędzie użyty)
- ✅ Sesja z `httponly`, `samesite=lax`, `secure` w produkcji
- ✅ `session_regenerate_id()` po login/register

**Werdykt:** CHANGES REQUESTED

---

*CRITICAL: 9 | WARNING: 18 | INFO: 17*

---

## Rekomendacje: Jak budować projekty PHP zgodnie z nowoczesnymi wzorcami

Poniższa sekcja to **przewodnik na przyszłość** — wskazówki jak rozwijać ten projekt lub budować nowe, opierając się na sprawdzonych wzorcach i architekturze.

---

### 1. 🏗️ Architektura i struktura projektu

#### a) Używaj frameworka lub ucz się z niego

Twój custom framework (Router, Container, Validator) to **świetne ćwiczenie edukacyjne** — widać, że rozumiesz DI, middleware pipeline i routing. Ale do produkcji rozważ:

- **Laravel** (najbardziej popularny, ogromna społeczność, dużo materiałów edukacyjnych)
- **Symfony** (bardziej modularny, profesjonalny, lżejszy)
- **Slim Framework** (mikro-framework — najbliższy temu co budujesz, idealny do nauki)

**Dlaczego?** Dojrzałe frameworki mają przetestowane rozwiązania na problemy, które tu odkryliśmy (CSRF, sesje, walidacja, routing, error handling).

#### b) Wzorzec Repository

Zamiast pisać SQL bezpośrednio w serwisach, wydziel warstwę Repository:

```
Controller → Service (logika biznesowa) → Repository (dostęp do danych) → Database
```

```php
// ❌ Teraz: SQL w serwisie
class TransactionService {
    public function getUserIncomes(int $userId) {
        return $this->db->query("SELECT ... FROM incomes ...", [...])->fetchAll();
    }
}

// ✅ Lepiej: Repository
class IncomeRepository {
    public function findByUserId(int $userId, int $limit = null, int $offset = null): array { ... }
    public function sumByUserId(int $userId): float { ... }
    public function findByDateRange(int $userId, DateTime $from, DateTime $to): array { ... }
}

class TransactionService {
    public function __construct(
        private IncomeRepository $incomeRepo,
        private ExpenseRepository $expenseRepo
    ) {}
}
```

#### c) Separation of Concerns — serwis ≠ sesja

**Złota zasada:** Warstwa serwisów **nigdy** nie powinna czytać `$_SESSION`, `$_POST`, `$_GET`. Te superglobale to warstwa HTTP — powinny być czytane wyłącznie w kontrolerze i przekazywane jako parametry:

```php
// ❌ Źle (teraz)
class GoalService {
    public function createNewGoal(array $formData) {
        $userId = $_SESSION['user'];  // ukryta zależność!
    }
}

// ✅ Dobrze
class GoalService {
    public function createNewGoal(int $userId, string $name, float $amount, ?DateTime $deadline): void {
        // parametry jawne, testowalny, reużywalny
    }
}
```

---

### 2. 🔒 Bezpieczeństwo — lista kontrolna

Na każdym nowym projekcie przejdź tę checklistę:

| Obszar | Co zrobić | Narzędzie/wzorzec |
|---|---|---|
| **SQL Injection** | ✅ Masz — prepared statements | PDO z parametrami (już robisz!) |
| **XSS** | Escapuj ZAWSZE output | `htmlspecialchars()` / Twig autoescaping |
| **CSRF** | Token per-request, `hash_equals()` | Framework middleware lub `symfony/security-csrf` |
| **Autoryzacja** | Sprawdzaj właściciela zasobu | `WHERE user_id = :userId` na KAŻDYM query |
| **Hasła** | ✅ Masz — bcrypt | `password_hash()` / `password_verify()` |
| **Sesje** | ✅ Masz częściowo | `httponly`, `secure`, `samesite`, `session_regenerate_id()` |
| **Walidacja input** | Waliduj typ, długość, format | Dedykowany Validator z regułami |
| **Error handling** | Nie pokazuj stacktrace w prod | Global exception handler z logowaniem |
| **HTTPS** | Wymuszaj w produkcji | `.htaccess` redirect lub middleware |
| **Rate limiting** | Ogranicz próby logowania | Middleware z licznikiem prób |

---

### 3. 📐 Wzorce projektowe do poznania

#### a) Data Transfer Objects (DTO)

Zamiast przekazywać surowe tablice `$_POST` przez cały stos, utwórz typowane obiekty:

```php
// ❌ Teraz
$this->transactionService->createIncome($_POST);

// ✅ Lepiej
class CreateIncomeDTO {
    public function __construct(
        public readonly int $userId,
        public readonly int $categoryId,
        public readonly float $amount,
        public readonly DateTime $date,
        public readonly ?string $comment = null
    ) {}

    public static function fromRequest(array $post, int $userId): self {
        return new self(
            userId: $userId,
            categoryId: (int)$post['incomeCategory'],
            amount: (float)$post['incomeAmount'],
            date: new DateTime($post['incomeDate']),
            comment: $post['incomeComment'] ?? null
        );
    }
}

// W kontrolerze:
$dto = CreateIncomeDTO::fromRequest($_POST, (int)$_SESSION['user']);
$this->transactionService->createIncome($dto);
```

#### b) Enum zamiast magic strings

```php
// ❌ Teraz
$type = 'income'; // magic string, łatwo o literówkę

// ✅ PHP 8.1+
enum TransactionType: string {
    case Income = 'income';
    case Expense = 'expense';
}
```

#### c) Value Objects

```php
// ❌ Teraz
$amount = $formData['amount']; // co jeśli ujemne? string?

// ✅ Lepiej
class Money {
    public function __construct(private float $amount) {
        if ($amount < 0) throw new \InvalidArgumentException('Amount must be non-negative');
    }
    public function value(): float { return $this->amount; }
}
```

---

### 4. 🧪 Testowanie

Ten projekt nie ma żadnych testów. Kolejne projekty powinny mieć przynajmniej:

#### a) Narzędzia

- **PHPUnit** — testy unitowe i integracyjne
- **Pest** — bardziej czytelna składnia (nakładka na PHPUnit)

#### b) Co testować w pierwszej kolejności

1. **Logikę biznesową** (serwisy) — obliczanie salda, walidacja limitów
2. **Reguły walidacji** — każda reguła to osobny unit test
3. **Repozytoria** — z bazą testową (SQLite in-memory)

```php
// Przykład testu dla LessThanBalanceRule
public function test_rejects_amount_exceeding_balance(): void {
    $rule = new LessThanBalanceRule();
    $result = $rule->validate(
        ['amount' => '150.00'],
        'amount',
        ['100.00']
    );
    $this->assertFalse($result);
}
```

#### c) Struktura testów

```
tests/
├── Unit/
│   ├── Rules/
│   │   ├── RequiredRuleTest.php
│   │   ├── EmailRuleTest.php
│   │   └── LessThanBalanceRuleTest.php
│   ├── Services/
│   │   ├── TransactionServiceTest.php
│   │   └── CategoryServiceTest.php
├── Integration/
│   ├── UserRegistrationTest.php
│   └── IncomeCreationTest.php
└── bootstrap.php
```

---

### 5. 🗄️ Baza danych — migracje zamiast monolitycznego SQL

Zamiast jednego pliku `database.sql`, użyj systemu migracji:

```
migrations/
├── 001_create_users_table.sql
├── 002_create_income_categories.sql
├── 003_create_incomes.sql
├── 004_add_is_active_column.sql
└── 005_add_monthly_limit_column.sql
```

**Narzędzia:** `phinx`, `doctrine/migrations`, lub wbudowane migracje Laravela.

**Korzyści:**
- Każda zmiana schematu jest wersjonowana
- Łatwo cofnąć (rollback)
- Nie trzeba martwić się o `IF NOT EXISTS` / `IF NOT EXISTS COLUMN`
- Współpraca zespołowa bez konfliktów

---

### 6. 🧹 Jakość kodu — narzędzia do automatyzacji

Dodaj te narzędzia do projektu i uruchamiaj przed każdym commitem:

| Narzędzie | Co robi | Komenda |
|---|---|---|
| **PHP-CS-Fixer** | Autoformatuje kod wg standardu (PSR-12) | `php-cs-fixer fix src/` |
| **PHPStan** / **Psalm** | Statyczna analiza — wykrywa bugi bez uruchamiania | `phpstan analyse src/ --level=5` |
| **PHPUnit** | Testy | `phpunit --testdox` |

Dodaj do `composer.json`:
```json
{
    "require-dev": {
        "phpunit/phpunit": "^11.0",
        "phpstan/phpstan": "^2.0",
        "friendsofphp/php-cs-fixer": "^3.0"
    },
    "scripts": {
        "test": "phpunit",
        "analyse": "phpstan analyse src/ --level=5",
        "fix": "php-cs-fixer fix src/"
    }
}
```

---

### 7. 🌐 Frontend — nowoczesne podejście

Obecny frontend (PHP templates + vanilla JS + inline CSS) działa, ale do większych projektów rozważ:

| Poziom | Technologia | Kiedy |
|---|---|---|
| **Minimalny** | Twig/Blade (szablony z autoescaping) | Już teraz — zamień PHP templates |
| **Średni** | htmx + Alpine.js | Dynamiczne UI bez SPA |
| **Zaawansowany** | Vue.js / React + API backend | Duże, interaktywne aplikacje |

**Twig** w szczególności rozwiązuje problem XSS — autoescaping jest domyślnie włączony:
```twig
{# Automatycznie escapowane — bezpieczne #}
<p>{{ categoryName }}</p>

{# Jawnie surowe — tylko gdy wiesz co robisz #}
<p>{{ rawHtml|raw }}</p>
```

---

### 8. 📦 Git workflow i CI/CD

#### a) Branching

- `main` — stabilna wersja, zawsze deployowalna
- `develop` — integracja feature'ów
- `feature/nazwa-ficzera` — osobny branch na każdą funkcjonalność

#### b) Pre-commit hooks (z `grumphp` lub `captain-hook`)

Automatycznie uruchamiaj przed commitem:
1. PHP-CS-Fixer (formatowanie)
2. PHPStan (analiza statyczna)
3. PHPUnit (testy)

#### c) GitHub Actions (CI)

```yaml
# .github/workflows/ci.yml
name: CI
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with: { php-version: '8.3' }
      - run: composer install
      - run: composer analyse
      - run: composer test
```

---

### 9. 📚 Polecane materiały do nauki

1. **PHP: The Right Way** — https://phptherightway.com/
2. **Clean Code** (Robert C. Martin) — ogólne zasady czystego kodu
3. **Laravel od zera** — nawet jeśli nie używasz Laravela, nauczysz się wzorców (Eloquent, Middleware, Validation, Events)
4. **OWASP Top 10** — https://owasp.org/www-project-top-ten/ — lista najczęstszych zagrożeń bezpieczeństwa
5. **PHP-FIG PSR** — standardy PHP (PSR-4 autoloading ✅ masz, PSR-12 coding style, PSR-7 HTTP messages)

---

### 10. 🗺️ Sugerowana ścieżka rozwoju tego projektu

Jeśli chcesz kontynuować rozwijanie SaveWave, oto sugerowana kolejność:

1. **🔴 Napraw CRITICAL** — najpierw bezpieczeństwo (C2, C3, C4, C5, C8)
2. **Dodaj PHPStan** — poziom 5 wykryje wiele bugów automatycznie
3. **Wyciągnij $_SESSION z serwisów** — przekazuj userId jako parametr
4. **Dodaj SQL LIMIT/OFFSET** — zamiast paginacji w PHP
5. **Przenieś ALTER TABLE do CREATE TABLE** — napraw database.sql
6. **Dodaj return types** — na wszystkie metody
7. **Dodaj PHPUnit** — zacznij od testów reguł walidacji
8. **Rozważ Twig** — autoescaping rozwiąże problemy XSS systemowo
9. **Refaktoryzuj DRY** — zjednolicaj pary income/expense
10. **Dodaj global error handler** — nie pokazuj stacktrace w produkcji

