---
name: cc-init
description: Bootstrap a best-practice Claude Code configuration for a new or unconfigured project. Use this skill when a user asks to set up Claude Code, initialize a project, create a CLAUDE.md, or configure permissions/hooks/settings for the first time. Also use when the user says things like "set up this project", "configure Claude Code", "bootstrap config", or "better /init". This skill replaces the built-in /init with a leaner, more opinionated setup grounded in current best practices.
allowed-tools: Read, Write, Edit, Bash, Grep, Glob
argument-hint: "[optional: brief project description]"
---

# Bootstrap Claude Code Configuration

You are setting up a Claude Code configuration from scratch. The project directory may be empty or nearly empty. Your goal is to create a lean, high-quality baseline that works for any project regardless of language, framework, or tooling.

## Philosophy

Every line in CLAUDE.md costs context tokens on every single message. Frontier models follow ~150–200 instructions reliably; the system prompt already uses ~50. That leaves ~100–150 slots before quality degrades across all rules. Configuration is a multiplier on everything Claude Code does — invest in it upfront, keep it lean, let automation compound.

The single most impactful principle: give Claude a way to verify its work. If Claude has a feedback loop (tests, linters, type checkers), output quality doubles or triples.

## Step 1: Gather context

Before creating any files, understand what you're working with.

1. Check if a git repo exists. If not, do NOT create one — just note it for the user.
2. Look for existing config: `CLAUDE.md`, `AGENTS.md`, `.claude/`, `.mcp.json`. If any exist, tell the user this skill is for fresh setups and suggest using `/cc-optimize` instead.
3. Scan for clues about the project. Cover both code and content projects:
   - **Code**: `package.json`, `composer.json`, `Cargo.toml`, `pyproject.toml`, `go.mod`, `Makefile`, `Gemfile`, `pom.xml`, `build.gradle`, any `*.sln` or `*.csproj` files.
   - **Content / static sites / docs**: `hugo.toml`, `config.toml`, `config.yaml` (Hugo), `_config.yml` (Jekyll), `astro.config.*`, `.eleventy.js`, `mkdocs.yml`, `content/`, `articles/`, `posts/`, `_posts/`, dominant `.md` files, knowledge base or style guide files (`STYLE.md`, `style-guide.md`).
   - Always check `README.md` for purpose.
4. Check for existing quality tools:
   - **Code**: `.eslintrc*`, `.prettierrc*`, `phpcs.xml*`, `rustfmt.toml`, `.editorconfig`, CI configs (`.github/workflows/`, `.gitlab-ci.yml`), pre-commit configs.
   - **Content**: `.vale.ini` / `vale.ini`, `.markdownlint.{json,yaml,yml}`, prettier configured for Markdown.
5. Check for sensitive files: `.env`, `.env.*`, `secrets/`, any `*credentials*` or `*secret*` files.

If the project directory is truly empty or has minimal content, ask the user:

- What does this project produce? (e.g. a web app, a library, articles for a tutorial site, documentation)
- What stack or toolchain is involved? (e.g. Next.js + npm, Hugo, Pandoc, plain Markdown, etc.)
- Are there inputs you'll reference repeatedly, like a shared knowledge base or style guide?

If `$ARGUMENTS` was provided, use that as the project description and infer what you can. Only ask about things you genuinely cannot determine.

## Step 2: Create .claude/settings.json

This file provides permissions, hooks, and environment variables. It goes into version control.

Build it from these components:

### Permissions

Always include `permissions.deny` for sensitive files. Adapt the patterns to what you found in Step 1:

```json
{
  "$schema": "https://json.schemastore.org/claude-code-settings.json",
  "permissions": {
    "deny": [
      "Read(./.env)",
      "Read(./.env.*)",
      "Read(./secrets/**)",
      "Bash(curl:*)",
      "Bash(wget:*)",
      "Bash(rm -rf:*)"
    ]
  }
}
```

Adjust deny rules based on what you found:

- If there are credential files, add patterns for them.
- If SSH keys or cloud credentials exist nearby, add those too.

For `permissions.allow`, add entries only if you can identify concrete, safe commands from the project (e.g., `Bash(npm run test:*)`, `Bash(cargo test:*)`). If the project is too empty to know, leave `allow` out — the user will add it interactively and can persist choices via `/permissions`.

### Hooks

If you identified a formatter in Step 1, add a PostToolUse hook:

```json
{
  "hooks": {
    "PostToolUse": [
      {
        "matcher": "Edit|Write",
        "hooks": [
          {
            "type": "command",
            "command": "<formatter-command> || true"
          }
        ]
      }
    ]
  }
}
```

Common formatter commands by ecosystem:

- JS/TS: `jq -r '.tool_input.file_path' | xargs npx prettier --write`
- PHP: `jq -r '.tool_input.file_path' | xargs php-cs-fixer fix`
- Rust: `jq -r '.tool_input.file_path' | xargs rustfmt`
- Python: `jq -r '.tool_input.file_path' | xargs ruff format`
- Go: `jq -r '.tool_input.file_path' | xargs gofmt -w`
- Markdown: `jq -r '.tool_input.file_path' | xargs npx prettier --write` or `jq -r '.tool_input.file_path' | xargs markdownlint --fix`

Vale is a prose linter, not a formatter — don't wire it into PostToolUse. If you want Vale to run, suggest it as a manual command in CLAUDE.md instead.

If no formatter is detected, skip the hook — don't guess. Note it in the summary for the user to add later.

The `|| true` suffix is mandatory. Hooks must never crash Claude Code.

### Environment variables

Always include these cost-optimization defaults:

```json
{
  "env": {
    "CLAUDE_AUTOCOMPACT_PCT_OVERRIDE": "50"
  }
}
```

This overrides auto-compaction from the default ~83% (too late for good quality) to 50%.

## Step 3: Create CLAUDE.md

Build a project-level CLAUDE.md. Target 20–40 lines for a fresh project. It will grow as the project grows.

Structure:

```markdown
# <Project Name>

<One-line description. Stack or toolchain summary — works for code (e.g. "Next.js + Prisma") or content (e.g. "Hugo site, articles in Markdown, edited with Vale").>

## Commands

<List exact commands the project uses. Examples:

- Code projects: `npm test`, `cargo build`, `pytest tests/`
- Content/static-site projects: `hugo build`, `vale .`, `markdownlint **/*.md`, `pandoc input.md -o output.pdf`
  If unknown yet, add placeholders with TODO markers.>

## Structure

<Only if you can already identify a meaningful directory layout. Otherwise omit. For content projects, mention things like the article output directory or where the knowledge base lives.>

## References

<Optional. For content projects with a shared knowledge base or style guide, use progressive disclosure rather than inlining content:
```

@knowledge-base/index.md
@style-guide.md **Read when:** writing or editing articles

```
Only include this section if such files exist.>

## Conventions

<Only concrete rules that deviate from defaults or that Claude commonly gets wrong. For content projects this might be voice/tone, terminology, or output format requirements. For code projects, conventions that aren't enforced by the linter. If the project is too new, keep this minimal or omit.>

## Don't

<Explicit prohibitions. Always include at minimum:>
- Don't commit secrets or credentials to git
- Don't use --force flags — fix the underlying issue instead

## Learnings

When the user corrects a mistake or points out a recurring issue, append a one-line
summary to .claude/learnings.md. Don't modify CLAUDE.md directly.

## Compact Instructions

When compacting, preserve: list of modified files, current test status, open TODOs, and key decisions made.
```

Rules for writing CLAUDE.md:

- Never include standard language conventions Claude already knows.
- Never include rules that the linter/formatter enforces — "never send an LLM to do a linter's job."
- Never include personality instructions ("be a senior engineer").
- Never include file-by-file codebase descriptions.
- Use `IMPORTANT:` or `YOU MUST` sparingly — if everything is important, nothing is.
- Prefer concrete commands over vague advice ("run `npm test -- auth.test.ts`" beats "run the relevant tests").

## Step 4: Create AGENTS.md (if multi-tool environment)

Only create this if you have evidence that other AI coding tools are used (e.g., `.codex/`, `.gemini/`, `.github/copilot/`, cursor-related configs, or the user mentions it).

AGENTS.md is the vendor-neutral standard read by Codex, Amp, Cursor, Copilot, and others. It demonstrably reduces runtime (~29%) and output token consumption (~17%).

If created, keep it focused on universal concerns: setup commands, architecture boundaries, code style rules, testing conventions, and safety rules. Then reference it from CLAUDE.md via `@AGENTS.md`.

## Step 5: Update .gitignore

Append these lines if they're not already present:

```
# Claude Code — personal files
.claude/settings.local.json
.claude/local.md
```

## Step 6: Create Key Config Files table auto-sync

Add a "Key Config Files" table to CLAUDE.md and a pre-commit hook that keeps it in sync with the filesystem. This gives Claude instant orientation on every message without manual maintenance.

### 6a: Add the table to CLAUDE.md

After the project description line, add a `## Key Config Files` section with a Markdown table listing every config file you created in the previous steps. Use this format:

```markdown
## Key Config Files

| File                    | Purpose                                    |
| ----------------------- | ------------------------------------------ |
| `CLAUDE.md`             | Project instructions, loaded every message |
| `.claude/settings.json` | Permissions, hooks, environment variables  |
| `.gitignore`            | Git ignore patterns                        |
```

Include only files that actually exist and are tracked by git (not gitignored). Write a concise, specific purpose for each.

### 6b: Create the sync script

Create `scripts/sync-config-table.sh` — a bash script that automatically keeps the table in sync:

```bash
#!/usr/bin/env bash
# Keeps the "Key Config Files" table in CLAUDE.md in sync with the filesystem.
# - Removes rows for files that no longer exist
# - Appends rows for new config files with a placeholder description
# - Excludes gitignored files (they are per-machine, not part of the committed state)
# Preserves all existing hand-written descriptions.
# Invoked automatically by the pre-commit hook.

set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
CLAUDE_MD="$ROOT/CLAUDE.md"

if [[ ! -f "$CLAUDE_MD" ]]; then
  echo "sync-config-table: CLAUDE.md not found, skipping"
  exit 0
fi

# Collect config files
config_files=()

# Root-level config files (by extension)
while IFS= read -r -d '' f; do
  name="$(basename "$f")"
  # Skip non-config files
  case "$name" in
    package-lock.json|README.md|CHANGELOG.md|AGENTS.md|CLAUDE.md|LICENSE) continue ;;
  esac
  config_files+=("$name")
done < <(find "$ROOT" -maxdepth 1 -type f \( -name '*.json' -o -name '*.js' -o -name '*.ts' -o -name '*.mjs' -o -name '*.cjs' -o -name '*.yaml' -o -name '*.yml' -o -name '*.toml' \) -print0 2>/dev/null | sort -z)

# Root-level dotfiles that are config files
for dotfile in .gitignore .npmignore .prettierignore .editorconfig .nvmrc .node-version .vale.ini .markdownlint.json .markdownlint.yaml .markdownlint.yml; do
  [[ -f "$ROOT/$dotfile" ]] && config_files+=("$dotfile")
done

# .claude/ direct children (skip subdirectories like skills/)
if [[ -d "$ROOT/.claude" ]]; then
  while IFS= read -r -d '' f; do
    config_files+=(".claude/$(basename "$f")")
  done < <(find "$ROOT/.claude" -maxdepth 1 -type f -print0 2>/dev/null | sort -z)
fi

# .claude/skills/ skill definitions
if [[ -d "$ROOT/.claude/skills" ]]; then
  while IFS= read -r -d '' f; do
    relpath="${f#$ROOT/}"
    config_files+=("$relpath")
  done < <(find "$ROOT/.claude/skills" -maxdepth 2 -name 'SKILL.md' -type f -print0 2>/dev/null | sort -z)
fi

# .github/workflows/
if [[ -d "$ROOT/.github/workflows" ]]; then
  while IFS= read -r -d '' f; do
    config_files+=(".github/workflows/$(basename "$f")")
  done < <(find "$ROOT/.github/workflows" -maxdepth 1 -type f -print0 2>/dev/null | sort -z)
fi

# Filter out gitignored files (per-machine / personal files don't belong
# in the committed config table — they may not exist on other clones).
# git check-ignore exits 0 if the path is ignored, 1 if tracked/untracked-but-not-ignored.
filtered_files=()
cd "$ROOT"
for file in "${config_files[@]}"; do
  if ! git check-ignore -q "$file" 2>/dev/null; then
    filtered_files+=("$file")
  fi
done
config_files=("${filtered_files[@]}")

# Sort config files
IFS=$'\n' sorted_files=($(sort <<<"${config_files[*]}")); unset IFS

# Parse existing descriptions from CLAUDE.md
declare -A descriptions
section_found=false
while IFS= read -r line; do
  if [[ "$line" == *"## Key Config Files"* ]]; then
    section_found=true
    continue
  fi
  if $section_found; then
    if [[ "$line" =~ ^\|[[:space:]]*\`([^\`]+)\`[[:space:]]*\|[[:space:]]*(.+)[[:space:]]*\| ]]; then
      file="${BASH_REMATCH[1]}"
      desc="${BASH_REMATCH[2]}"
      [[ "$file" == "File" ]] && continue
      descriptions["$file"]="$desc"
    fi
  fi
done < "$CLAUDE_MD"

# Build new table
new_table="| File | Purpose |
|------|---------|"

for file in "${sorted_files[@]}"; do
  desc="${descriptions[$file]:-TODO: add description}"
  new_table+=$'\n'"| \`$file\` | $desc |"
done

# Replace the table in CLAUDE.md
# Find the section, skip old blank lines + table rows, emit new table
tmpfile="$(mktemp)"
in_section=false
table_replaced=false

while IFS= read -r line; do
  if [[ "$line" == *"## Key Config Files"* ]]; then
    in_section=true
    echo "$line" >> "$tmpfile"
    continue
  fi

  if $in_section && ! $table_replaced; then
    # Skip blank lines and old table rows between heading and next content
    if [[ "$line" == "" ]] || [[ "$line" == "|"* ]]; then
      continue
    fi
    # First non-blank, non-table line: emit new table, then this line
    echo "" >> "$tmpfile"
    echo "$new_table" >> "$tmpfile"
    echo "" >> "$tmpfile"
    echo "$line" >> "$tmpfile"
    table_replaced=true
    in_section=false
    continue
  fi

  echo "$line" >> "$tmpfile"
done < "$CLAUDE_MD"

# If we hit EOF while still in the section (table is the last thing)
if $in_section && ! $table_replaced; then
  echo "" >> "$tmpfile"
  echo "$new_table" >> "$tmpfile"
fi

# Check for changes
if diff -q "$CLAUDE_MD" "$tmpfile" > /dev/null 2>&1; then
  echo "sync-config-table: no changes"
  rm "$tmpfile"
else
  mv "$tmpfile" "$CLAUDE_MD"
  echo "sync-config-table: updated CLAUDE.md"
  git add CLAUDE.md
fi
```

Make the script executable: `chmod +x scripts/sync-config-table.sh`

### 6c: Create the pre-commit hook

Create `.githooks/pre-commit`:

```bash
#!/usr/bin/env bash
# Keep CLAUDE.md config file table in sync
bash scripts/sync-config-table.sh
```

Make it executable: `chmod +x .githooks/pre-commit`

### 6d: Activate the hooks directory

Run this command to tell git to use `.githooks/` instead of the default `.git/hooks/`:

```bash
git config core.hooksPath .githooks
```

This needs to be run once per clone. Note this in the summary (Step 7) so the user is aware.

**Important:** If the project already uses Husky or another hook manager, skip this entire step and note it in the summary. The sync script would conflict with existing hook infrastructure.

## Step 7: Present summary

After creating all files, give the user a concise summary:

1. List every file created with a one-line description.
2. Note any TODO placeholders that need filling in once the project takes shape.
3. Mention what was intentionally left out and why (e.g., "No PostToolUse hook yet because no formatter was detected — add one once you pick a formatter.").
4. Remind the user of three high-leverage next steps:
   - Add test/build/lint commands to CLAUDE.md once they exist.
   - Run `/cc-optimize` after the project has some code to get a project-aware configuration pass.
   - Consider adding MCP servers to `.mcp.json` as needs arise (Context7 for docs, GitHub for PRs, etc.).
5. If the Key Config Files auto-sync was set up (Step 6), remind the user:
   - The pre-commit hook requires a one-time activation per clone: `git config core.hooksPath .githooks`
   - This command was already run for the current clone, but collaborators or fresh clones need to run it too.
   - Suggest documenting it in the project README's setup instructions.
6. Explain the Learnings mechanism:
   - When the user corrects a mistake, Claude appends a one-line summary to `.claude/learnings.md` instead of modifying CLAUDE.md directly.
   - This file grows uncurated over time. Running `/cc-optimize` reviews it and proposes promoting recurring patterns into CLAUDE.md or skills, and deleting one-off entries.
7. Suggest committing the new config files to git.

## What NOT to do

- Don't run `/init`. This skill replaces it.
- Don't create MCP configs. MCP choices are project-specific and premature for an empty project.
- Don't create skills. Skills encode recurring workflows that don't exist yet.
- Don't create `.claude/local.md` or `settings.local.json`. Those are personal and should be created by the user.
- Don't over-engineer. A 20-line CLAUDE.md that's accurate beats an 80-line one full of guesses.
- Don't include information you're not confident about. TODOs are better than wrong instructions.
