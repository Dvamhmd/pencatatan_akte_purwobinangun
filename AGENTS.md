# Project Agent Rules — Kalurahan Purwobinangun

## 0. ABSOLUTE TOKEN ECONOMY & ULTRA-FAST EXECUTION (HIGHEST PRIORITY)
- **Zero Overthinking**: Keep `thinking` strictly <= 3 concise bullets. Never debate alternatives; form a 1-line plan and batch independent tool calls in parallel.
- **Skill & Doc Suppression**: NEVER read `vendor`, `node_modules`, `storage`, `modern-web-guidance`, Chrome DevTools, web search, or optional skills (`.agents/skills/*`, styling/antislop/copywriting) unless explicitly requested by the user.
- **Target Isolation**: Work ONLY on target/active files. Locate lines first with PowerShell `Select-String` when needed, then read surgically via `StartLine`/`EndLine` (<= 40–80 lines; never full large files). Zero unsolicited directory scans or repo-wide audits.
- **Direct Execution & Minimal Response**: Apply surgical `edit_file` changes directly with zero unsolicited refactoring or reformatting. Final response: max 2–4 concise bullets (zero greeting, apology, or filler) **plus** the mandatory `git commit -m "..."` block when files are modified.

## 1. Tech Stack & Architecture Boundaries
- **Stack**: Laravel 12 (`PHP ^8.2`), Blade views (`resources/views/`), Tailwind CSS v4 (`resources/css/app.css`, `@tailwindcss/vite`), Vite 7 (`vite.config.js`), PHPUnit 11 (`tests/`).
- **Frontend Rule**: Do NOT add external JS/UI frameworks. Keep Blade + vanilla JS + Tailwind CSS v4 conventions. Run `npm run build` ONLY when CSS/JS assets are modified.
- **Schema & Models**: NEVER edit existing applied migrations in `database/migrations/`; create a new migration for schema changes and keep model `$fillable` / `$casts` synchronized.
- **Standalone Docs**: Ignore root PDF/preview scripts (`generate_pdf*.js/cjs`, `*_preview.html`) unless explicitly tasked with modifying them.

## 2. Authentication, Role & KK-Based Access Control
- **Strict Role Separation (`users.role`)**:
  - Citizen (`warga`) protected routes use `auth.warga` (`EnsureWargaAuthenticated`): requires `role === 'warga'` AND `status === 'active'` (`pending`, `rejected`, `archived` are logged out).
  - Staff (`admin`) protected routes use `auth.admin` (`EnsureAdminAuthenticated`): requires `role === 'admin'`.
  - NEVER weaken middleware checks, mix guards, or bypass `status === 'active'` verification for `warga`.
- **KK Isolation (`family_card_no`)**:
  - Citizen tracking, detail, and print endpoints (`TrackingController`, `BirthCertificateController`, `DeathCertificateController`) MUST restrict `warga` queries and access exclusively to `family_card_no === $user->family_card_no` (abort 403 on mismatch).

## 3. Domain Lifecycle & Cross-Flow Parity
- **Certificate Statuses (`BirthCertificate` & `DeathCertificate`)**:
  - `pending` (`Menunggu Verifikasi`), `in_process`/`verified` (`Sedang Diproses`), `revision` (`Revisi Berkas`), `rejected` (`Dibatalkan`), `ready_for_pickup`/`completed` (`Siap diambil`), `picked_up` (`Sudah diambil`), `archived` (`Diarsipkan`).
- **Archive & Account Invariants**:
  - Preserve `isArchived()` (`$this->is_archived || in_array($this->status, ['picked_up', 'archived'])`) and `User::hasActiveApplications()` logic.
  - Citizen account statuses (`users.status`): `active`, `pending`, `rejected`, `archived`.
- **Flow Parity**: Shared changes to status transitions, notifications (`AdminNotificationService`, `CitizenNotificationService`, `WhatsAppNotificationService`), or `ActivityLog` must stay consistent across Birth & Death flows unless scoped to one.

## 4. UI Integrity & Official Purwobinangun Color Palette
- **UI Freeze Rule**: NEVER change existing layout, component structure, typography (`Plus Jakarta Sans`), spacing, or colors unless explicitly requested by the user.
- **Official Theme Colors (`resources/css/app.css`)**:
  - `#059cb8` (`--clr1`, `.bg-kalurahan-primary`) — Toska Utama Purwobinangun (Primary brand accent, primary buttons, active states)
  - `#095b8c` (`--clr2`, `.bg-kalurahan-navy`) — Navy Biru Purwobinangun (Headers, navigation contrast, gradient base)
  - `#3887c2` (`--clr3`, `.bg-kalurahan-accent`) — Aksen Biru (Secondary highlights, gradient accent)
  - `#75b4c4` (`--clrsoft1`) & `#b8ede6` (`--clrsoft2`) — Soft Teal & Soft Mint accents
  - `#f0f4f8` / `#1e293b` — Base canvas background / primary slate text (`#334155` for `.btn-archive`)

## 5. Fast Verification & Regression Prevention
- **Targeted Verification**: After PHP edits, verify with `php -l <file>` or targeted PHPUnit runs (`php artisan test tests/Feature/<TargetTest>.php`). Do NOT run full test suites or `npm run build` unless needed by scope.
- **Zero Regression & Targeted Impact**: Only inspect callers/views if altering shared routes, controller signatures, model accessors, or Blade layout props. Never do repo-wide crawls for isolated fixes.
- **Conditional Confirmation**: If a task introduces regression risk across auth, KK security, or database schema, briefly state the impact and ask before editing; for safe isolated fixes, execute directly.

## 6. Git Commit Standards (MANDATORY ON FILE EDITS)
- **Always Output**: ALWAYS end EVERY response that creates or modifies files with a ready-to-copy single-line `git commit -m "..."` block. NEVER omit it.
- **Language & Types**: English only (`feat:`, `fix:`, `refactor:`, `perf:`, `style:`, `chore:`, `docs:`).
- **Strict Format**: Single-line `git commit -m "..."` only. **DO NOT include `git add`**.
- **Exclusion**: NEVER mention `AGENTS.md` in commit messages.

## 7. Final Response Checklist (Check Before Sending)
- Concise summary (`<= 4` bullets, zero filler, zero greeting/apology)?
- Only verified file paths, symbol names, and observed outputs stated?
- If any file was created/edited: included single-line `git commit -m "..."` in English (no `git add`, no `AGENTS.md` mention)?
