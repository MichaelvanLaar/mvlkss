---
name: cc-optimize
description: Audit and optimize an existing Claude Code configuration against current best practices. Use this skill when a user asks to review, improve, clean up, or optimize their Claude Code setup, CLAUDE.md, settings, hooks, MCP servers, or skills. Also use when the user says things like "check my config", "is my CLAUDE.md too long", "reduce token costs", "tighten permissions", or "my Claude Code setup feels bloated". This skill assumes the project has code, and possibly documentation or OpenSpec specs, that inform the optimization.
allowed-tools: Read, Write, Edit, Bash, Grep, Glob
argument-hint: "[optional: specific area to focus on, e.g. 'CLAUDE.md', 'hooks', 'costs']"
---

# Optimize Claude Code Configuration

You are auditing and improving an existing Claude Code setup. The project has code, possibly documentation, and possibly OpenSpec specifications. Your job is to identify what's good (preserve it), what's missing, what's bloated, and what violates current best practices — then fix it with the user's approval.

## Philosophy

Configuration is a multiplier, but only if it's lean. A 60-line CLAUDE.md with progressive disclosure outperforms a 300-line monolith. Three well-chosen MCP servers beat twenty poorly managed ones. Proactive compaction at 50% beats reactive auto-compact at 83%. A PostToolUse hook that runs the formatter on every edit eliminates an entire class of manual intervention forever.

The guiding question for every instruction in CLAUDE.md: "Would removing this line cause Claude to make a concrete mistake?" If no — remove it.

## Step 1: Full inventory

Read and catalog everything that exists. Do this thoroughly before suggesting any changes.

### Configuration files

- `CLAUDE.md` (project root and any subdirectories)
- `AGENTS.md`
- `.claude/settings.json` and `.claude/settings.local.json`
- `.claude/local.md`
- `.claude/rules/*.md`
- `.claude/skills/*/SKILL.md`
- `.claude/commands/*.md` (legacy format)
- `.claude/agents/*.md`
- `.claude/learnings.md`
- `.mcp.json` (project root)
- `~/.claude/CLAUDE.md` (user level — read but don't modify without asking)
- `~/.claude.json` (user-level MCP — read but don't modify without asking)

### Project context

- Package manager and dependencies (package.json, composer.json, Cargo.toml, etc.)
- Build/test/lint commands (scripts in package.json, Makefile targets, etc.)
- Formatter and linter configs (.prettierrc, .eslintrc, phpcs.xml, rustfmt.toml, etc.)
- CI/CD configuration
- OpenSpec artifacts (`openspec/` directory, `openspec/project.md`, change specs)
- Documentation (`docs/`, `README.md`, architecture docs)
- Directory structure and apparent architecture patterns
- Hook managers and their hook files (`.husky/`, `lefthook.yml`, `.pre-commit-config.yaml`)
- Project-local git hooks directory (`.githooks/`) and sync scripts (`scripts/sync-config-table.{sh,js}`)

### Current state metrics

Count and report:

- CLAUDE.md line count (target: 40–80, hard max: 200)
- Number of `@`-imports in CLAUDE.md
- Number of active MCP servers
- Number of skills
- Number of hooks
- Permissions: what's allowed, what's denied
- Environment variables set in settings.json
- Number of entries in `.claude/learnings.md` (if it exists)

## Step 2: Analyze against best practices

Work through each area systematically. If `$ARGUMENTS` specified a focus area, prioritize that but still scan everything.

### 2a: CLAUDE.md audit

Check for these anti-patterns:

**Bloat indicators** (things to remove or move):

- Standard language conventions Claude already knows → remove
- Rules that the configured linter/formatter already enforces → remove ("never send an LLM to do a linter's job")
- Personality instructions ("be a senior engineer", "think carefully") → remove
- File-by-file codebase descriptions → remove (Claude can read files itself)
- Domain knowledge that's rarely needed → move to a skill
- Long inline documentation → extract to a reference file and use `@`-import with a trigger condition
- Duplicated information that also exists in AGENTS.md or OpenSpec → remove from CLAUDE.md, reference instead

**Missing essentials** (things to add if absent):

- Exact build/test/lint/dev commands (not vague — actual command strings)
- Key directory structure (only non-obvious parts)
- Conventions that deviate from standard or that Claude commonly gets wrong
- Explicit "Don't" section for known failure modes
- Compact instructions (what to preserve when compacting)
- Progressive disclosure pointers for reference docs (`@path **Read when:** <trigger>`)
- Learnings section (instructs Claude to log corrections to `.claude/learnings.md` instead of modifying CLAUDE.md directly)

**Structural checks:**

- Is the file using `@`-imports for large reference material? (imports reduce token waste by up to 59%)
- If AGENTS.md exists, does CLAUDE.md import it via `@AGENTS.md` instead of duplicating content?
- If OpenSpec is used, does CLAUDE.md reference `@openspec/project.md` for project context?
- Are there too many `IMPORTANT:` or `YOU MUST` markers? (if everything is marked important, nothing is)

### 2b: AGENTS.md audit

- Does it exist? Should it? (yes if multiple AI tools are used in the project)
- Is it genuinely tool-agnostic? (no Claude-specific features like `@`-imports inside AGENTS.md)
- Does it cover: setup commands, architecture boundaries, code style, testing, safety?
- Is there unnecessary duplication between AGENTS.md and CLAUDE.md?

### 2c: Settings audit

**Permissions:**

- Are sensitive files protected by `permissions.deny`? At minimum: `.env`, `.env.*`, `secrets/**`.
- Is `permissions.deny` used instead of the deprecated `ignorePatterns`?
- Are destructive commands blocked? (`rm -rf`, and consider `curl`/`wget` unless specifically needed)
- Are safe, frequently-used commands in `permissions.allow`? (reduces approval fatigue)

**Hooks (Claude Code):**

- Is there a PostToolUse formatter hook? If a formatter exists in the project but no hook runs it, this is a high-impact gap.
- Is there a PreToolUse hook protecting sensitive files? (defense in depth beyond permissions.deny)
- Do all hooks use `|| true` for graceful degradation?
- Are hooks doing "block at submit" rather than "block at write"? (fewer interrupts, smoother flow)

**Git hooks and hook-manager drift:**

`/cc-init` creates a project-local `.githooks/pre-commit` that runs `scripts/sync-config-table.sh` and activates it via `git config core.hooksPath .githooks`. If a hook manager like Husky is added later, it takes over `core.hooksPath` — the `.githooks/pre-commit` is still present in the repo but silently stops running. This is a silent drift scenario. Check for it:

1. Detect hook managers:
   - Husky: `husky` in `package.json` devDependencies, or `.husky/` directory present
   - Lefthook: `lefthook.yml` or `lefthook` in devDependencies
   - pre-commit: `.pre-commit-config.yaml`
2. Detect cc-init hook infrastructure: `.githooks/pre-commit` exists and references `sync-config-table`
3. If both are present, flag as **conflict** and propose one of these migrations:
   - **Migrate to the hook manager** (recommended if the hook manager is the project standard): move the `sync-config-table` invocation into the hook manager's pre-commit config (e.g., append it to `.husky/pre-commit`), then delete `.githooks/pre-commit` and — if empty — the `.githooks/` directory. Optionally run `git config --unset core.hooksPath` so the setting doesn't confuse future contributors.
   - **Keep the project-local hook** (only if the hook manager was added by mistake or is being removed): leave `.githooks/` in place and note that the user needs to resolve which hook system owns `core.hooksPath`.
4. Also check if `scripts/sync-config-table.*` exists but `.githooks/pre-commit` is missing entirely — the script is orphaned and never runs. Same proposal: wire it into the active hook manager or recreate the `.githooks/` setup.
5. If the sync script exists in a variant that doesn't match the filesystem conventions of the project (e.g., a `.sh` script in a Node-only project where the team prefers `.js`), note it as a nice-to-have for harmonization but don't force the change.

**Environment variables:**

- Is `CLAUDE_AUTOCOMPACT_PCT_OVERRIDE` set? Recommended: `50`. Default 83% is too late.
- Is `MAX_THINKING_TOKENS` set? Consider `10000` (down from default 31999) for ~70% thinking cost savings.
- Is `CLAUDE_CODE_MAX_OUTPUT_TOKENS` set? Consider `16000` to prevent unnecessarily verbose responses.
- Is `CLAUDE_CODE_SUBAGENT_MODEL` set? `haiku` gives ~80% cost savings for exploration subagents.

### 2d: MCP audit

- How many servers are active? (5–10 is the sweet spot for most projects)
- Are all servers actually used? Check if they match the project's real needs.
- Are secrets hardcoded or using `${VAR}` expansion?
- Is the project using `.mcp.json` (project-scope, recommended) or `~/.claude.json` (user-scope)?
- Could any MCP server be replaced by a simpler CLI tool? (e.g., `gh` CLI instead of GitHub MCP for basic operations — no permanent context overhead)
- Is Tool Search active? (auto-enabled on Sonnet 4+ / Opus 4+ when MCP tool descriptions exceed 10% of context)

### 2e: Skills audit

- Are there skills that duplicate CLAUDE.md content? → Deduplicate.
- Are skills with side effects (deploy, commit, publish) using `disable-model-invocation: true`?
- Are read-only analysis skills using `allowed-tools` restrictions?
- Are there `.claude/commands/` files that should be migrated to the skills format?
- Is skill content concise? (target <50 lines per SKILL.md, split if longer)
- If OpenSpec is used: are OpenSpec skills duplicated across multiple tool directories (`.claude/`, `.codex/`, `.gemini/`, `.github/`)? If so, flag this as a maintenance risk and suggest consolidation.

### 2f: Multi-tool consistency check

If the project uses multiple AI tool directories:

- Is there a single source of truth (ideally AGENTS.md) that all tools reference?
- Are there contradictions between tool-specific configs?
- Is duplicated content maintained in sync, or is it drifting?

### 2g: Learnings review

If `.claude/learnings.md` exists:

1. Read all entries.
2. Group similar entries to identify recurring patterns (3+ similar corrections suggest a real gap in the config).
3. For each recurring pattern, propose one of:
   - Adding a concrete rule to CLAUDE.md (if it's a universal project convention).
   - Adding it to an existing or new skill (if it's domain-specific or rarely needed).
   - Adding it as a hook (if it's something that should happen deterministically, not by instruction).
4. For one-off entries that don't recur, propose deleting them.
5. Present the full list to the user grouped as "promote to config" vs "delete as one-off", with rationale for each. Wait for approval before changing anything.

If `.claude/learnings.md` does not exist but CLAUDE.md also has no Learnings section, suggest adding the Learnings section to CLAUDE.md:

```markdown
## Learnings

When the user corrects a mistake or points out a recurring issue, append a one-line
summary to .claude/learnings.md. Don't modify CLAUDE.md directly.
```

## Step 3: Generate findings report

Organize findings into three categories:

### Must fix (security or correctness issues)

- Missing permissions.deny for sensitive files
- Hardcoded secrets in config files
- Deprecated patterns (ignorePatterns, npm-installed Claude Code)
- Contradictory instructions
- Hook-manager conflict: `.githooks/pre-commit` present alongside an active hook manager (the sync script is not running)

### Should fix (quality and cost improvements)

- CLAUDE.md bloat (>80 lines without good reason)
- Missing formatter hook
- Missing cost-optimization env vars
- Redundant content between files
- Skills without proper frontmatter guards
- Learnings entries that should be promoted to CLAUDE.md or a skill
- Orphaned `scripts/sync-config-table.*` with no active hook wiring

### Nice to have (polish)

- Missing progressive disclosure for reference docs
- Missing compact instructions
- Missing Learnings section in CLAUDE.md
- Skills that could be created for recurring workflows
- MCP servers that could be added or removed
- Sync script format mismatch with project conventions (e.g., `.sh` in a Node-only repo)

Present the findings to the user as a concise list, grouped by category. For each finding, state: what the issue is, why it matters, and what you'd change. Ask for approval before making changes.

## Step 4: Apply approved changes

Make the approved changes. For each file modified:

- Show a before/after summary (not full diffs for large files — just the key changes).
- Explain briefly what changed and why.

When applying learnings review results:

- For entries promoted to CLAUDE.md or a skill, remove them from `.claude/learnings.md`.
- For entries marked as one-off, remove them from `.claude/learnings.md`.
- If all entries are processed, delete `.claude/learnings.md` entirely (it will be recreated naturally when the next correction occurs).

When resolving hook-manager conflicts:

- If migrating to Husky: append the sync-script call to `.husky/pre-commit` (create it if missing), delete `.githooks/pre-commit`, remove the empty `.githooks/` directory, and suggest the user runs `git config --unset core.hooksPath` on each clone.
- If migrating to Lefthook or pre-commit: add the appropriate entry to the respective config file instead.
- Never delete `scripts/sync-config-table.*` itself — the script is still useful, only the wiring changes.

Preserve things that work well. Don't refactor for the sake of refactoring. If an existing config is well-structured and correct, say so and move on.

## Step 5: Final summary

After all changes:

1. List every file modified or created, with one-line descriptions of changes.
2. Report the new metrics: CLAUDE.md line count, number of active MCP servers, hooks configured, etc.
3. Compare key metrics to before (e.g., "CLAUDE.md: 247 lines → 62 lines").
4. If learnings were reviewed: report how many entries were promoted, how many deleted, and how many remain.
5. Note anything you deliberately left unchanged and why.
6. Suggest running `/cc-optimize` again periodically (e.g., after major features, after a few weeks of work) to prevent config drift.
7. Remind the user to commit the changes.

## Common optimization patterns

These are recurring improvements you'll often apply:

**CLAUDE.md → Skills migration:**
When CLAUDE.md contains domain knowledge that's only needed for specific tasks, extract it into a skill. The skill loads on demand (~100 tokens metadata at rest), while CLAUDE.md content loads every message.

**Monolithic docs → Progressive disclosure:**
Replace inline documentation in CLAUDE.md with `@`-import pointers:

```markdown
### API Architecture — @docs/api-architecture.md

**Read when:** Adding or modifying API endpoints
```

**AGENTS.md as single source of truth:**
If the project has both CLAUDE.md and AGENTS.md with overlapping content, consolidate the universal parts into AGENTS.md and reduce CLAUDE.md to a slim adapter:

```markdown
@AGENTS.md

## Claude-Code-specific

- <only Claude-specific additions here>
```

**OpenSpec integration:**
If OpenSpec is present, CLAUDE.md should reference it rather than duplicate project context:

```markdown
@openspec/project.md
```

**Hook-ification of repeated instructions:**
If CLAUDE.md says "always run prettier after editing" — that's a hook, not an instruction. Replace the instruction with a deterministic PostToolUse hook and remove the line from CLAUDE.md.

**Learnings graduation:**
When `.claude/learnings.md` has accumulated entries, recurring patterns graduate into CLAUDE.md rules, skills, or hooks. One-off corrections get deleted. The file stays lean or gets removed entirely until the next correction cycle.

**Hook-manager migration for sync-config-table:**
When a project gains Husky or another hook manager after `/cc-init` was used, the `.githooks/pre-commit` goes silent because `core.hooksPath` is taken over. Migrate the sync script into the active hook manager's pre-commit hook and remove the now-dead `.githooks/` directory.

## What NOT to do

- Don't refactor things that work well. If a config is correct and clean, say so.
- Don't add MCP servers speculatively. Only suggest servers that address a concrete gap.
- Don't create skills for workflows that haven't been repeated yet.
- Don't modify user-level files (`~/.claude/CLAUDE.md`, `~/.claude.json`) without explicit permission.
- Don't remove functionality. If something serves a purpose, keep it — just optimize how it's expressed.
- Don't make the config dependent on tools or servers the user hasn't installed.
