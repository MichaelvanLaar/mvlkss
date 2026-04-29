---
name: cc-update
description: Update the cc-init, cc-optimize, and cc-update skills to their latest versions from the source repository. Use when the user says "update skills", "get the latest cc-init", "refresh skills", "update cc-optimize", or similar.
allowed-tools: Bash, Read
---

# Update Claude Code Skills

Fetch the latest versions of the installed skills from `MichaelvanLaar/claude-code-config-skills` and replace the local copies.

## Step 1: Check prerequisites

Verify `.claude/skills/` exists in the current directory:

```bash
ls .claude/skills/ 2>/dev/null || echo "NOT_FOUND"
```

If the directory is missing, abort and tell the user: "No `.claude/skills/` directory found. Run `install.sh` from the source repository first — see the README at github.com/MichaelvanLaar/claude-code-config-skills."

## Step 2: Detect installed skills

```bash
for skill in cc-init cc-optimize cc-update; do
  [ -f ".claude/skills/$skill/SKILL.md" ] && echo "$skill: installed" || echo "$skill: not installed"
done
```

Update rules:

- **`cc-update`** — always update (enables self-update).
- **`cc-init` and `cc-optimize`** — only if already installed. Do not install skills the user has not chosen to install.

## Step 3: Download and replace

```bash
BASE_URL="https://raw.githubusercontent.com/MichaelvanLaar/claude-code-config-skills/main"

for skill in cc-update cc-init cc-optimize; do
  target=".claude/skills/$skill/SKILL.md"
  if [ "$skill" = "cc-update" ] || [ -f "$target" ]; then
    mkdir -p ".claude/skills/$skill"
    if curl -fsSL "$BASE_URL/.claude/skills/$skill/SKILL.md" -o "$target"; then
      echo "✓ updated $skill"
    else
      echo "✗ failed to update $skill"
    fi
  else
    echo "— skipped $skill (not installed)"
  fi
done
```

## Step 4: Report and wrap up

After the downloads complete:

1. List each skill: updated, skipped, or failed.
2. If `cc-init` was skipped (not installed), mention: "`cc-init` is available but not installed. Run `install.sh` to add it — see github.com/MichaelvanLaar/claude-code-config-skills."
3. Remind the user to commit the updated files: `git add .claude/skills/ && git commit`.

## What NOT to do

- Do not install `cc-init` or `cc-optimize` if they were not already present.
- Do not run any skill after updating.
- Do not modify any other project files.
