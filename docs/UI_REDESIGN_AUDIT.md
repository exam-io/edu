# EduOS UI Audit and Redesign Blueprint

## 1. Current UI Weak Areas

- Navigation is a basic left list without grouped IA, favorites, recent activity, or command-first interactions.
- Dashboard pages rely on plain cards/text and lack insight density (trends, context, actionable widgets).
- Forms are functional but visually flat and do not guide user progress with clear hierarchy.
- Tables are mostly raw lists without premium controls (saved views, column controls, export behavior, sticky structure).
- Empty states and loading states are inconsistent across modules.
- Branding support exists for primary/secondary colors but is not fully expressed in shell experience.

## 2. Redesign Goals

- Elevate EduOS from admin panel aesthetic to premium, education-focused operating system.
- Introduce high-density, low-friction workflows with command palette and quick actions.
- Enforce visual consistency with a design-token-driven system.
- Preserve multi-tenant white-label capabilities for color, logo, favicon, and typography preferences.

## 3. Design System Decisions

- Typography:
  - UI font: Inter
  - Data/statistics font: JetBrains Mono
- Spacing: 4px rhythm reflected through Tailwind utility usage and component paddings.
- Radius:
  - Cards: 16px
  - Buttons: 12px
  - Inputs: 12px
- Shadows:
  - `--shadow-soft` for default elevation
  - `--shadow-elevated` for hover and focus lift
- Motion:
  - subtle rise-in page transitions
  - shimmer skeleton loaders
  - low-latency hover transitions
- Theme:
  - first-class light/dark palettes
  - tenant branding variables with optional accent and typography fields

## 4. Architecture Introduced

- Premium shell:
  - AppShell, Sidebar, TopBar, CommandPalette, NotificationCenter, Breadcrumbs, QuickActions, ProfileMenu, SearchBar, PageHeader
- Reusable component library:
  - StatCard, ChartCard, DataTable, FilterBar, NotificationCard, ActivityTimeline, CalendarWidget, ProgressCard, FileDropzone, AIUploadCard, ResultCard, EmptyState, SkeletonLoader
- Upgraded dashboard implementation:
  - AcademicHomePage now acts as Institute Dashboard with stats, schedule, timeline, table controls, calendar, AI quick generation, and progress tracking.

## 5. Remaining Rollout Tasks

- Migrate all feature pages to component-library building blocks.
- Introduce reusable form field primitives for TextInput/Textarea/Select/MultiSelect/DatePicker/Toggle/Checkbox/RadioGroup/TagInput.
- Add consistent form autosave and draft patterns where needed.
- Convert list-heavy pages to DataTable pattern with robust server-side pagination/filter integration.
- Add dedicated assessment attempt shell (timer, question navigator, autosave indicators).
- Expand calendar views into month/week/day/agenda with drag-drop event updates.
- Add accessibility passes: focus rings, skip links, keyboard navigation test matrix, contrast checks.

## 6. UX KPI Suggestions

- Time-to-action for primary tasks (create student, create assessment, start live class).
- Navigation success rate using command palette vs menu-only pathing.
- Dashboard comprehension score (first-visit user tests).
- Form completion rate and abandonment rate for onboarding + assessments.
