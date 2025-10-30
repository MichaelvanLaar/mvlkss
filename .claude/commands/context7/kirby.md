---
description: Pull Kirby CMS documentation as additional context using Context7.
---

# Context7: Kirby CMS Documentation

Pull official Kirby CMS documentation from Context7 to assist with Kirby-specific development tasks.

## Usage

```
/context7:kirby [topic]
```

### Parameters

- `[topic]` (optional): Specific topic to focus the documentation on (e.g., "blueprints", "templates", "fields", "routing", "controllers")

### Examples

```
/context7:kirby
/context7:kirby blueprints
/context7:kirby routing and controllers
```

## Instructions for Claude

When this command is executed:

1. **Resolve the Library ID**: Use the `resolve-library-id` tool with library name "getkirby" or use the direct Context7 URL https://context7.com/websites/getkirby

2. **Fetch Documentation**: Use the `get-library-docs` tool with:
   - The resolved library ID (format: `/websites/getkirby`)
   - The topic provided by the user (if any)
   - Token allocation:
     - **With topic**: 5,000-10,000 tokens for focused, specific content
     - **Without topic**: 3,000-5,000 tokens for general Kirby CMS context

3. **Summarize Context**: After loading, briefly confirm what was loaded (e.g., "Loaded Kirby CMS documentation for blueprints and field definitions")

4. **Apply Context**: Use the retrieved documentation to inform your responses and complete the user's original task

## Recommended Use Cases

- Implementing Kirby blueprints, templates, controllers, or models
- Working with Kirby's routing system
- Configuring Kirby fields and blocks
- Understanding Kirby panel customization
- Resolving Kirby-specific integration challenges
- Confirming Kirby conventions and best practices
