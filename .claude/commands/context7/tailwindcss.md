---
description: Pull Tailwind CSS documentation as additional context using Context7.
---

# Context7: Tailwind CSS Documentation

Pull official Tailwind CSS documentation from Context7 to assist with Tailwind-specific styling tasks.

## Usage

```
/context7:tailwindcss [topic]
```

### Parameters

- `[topic]` (optional): Specific topic to focus the documentation on (e.g., "configuration", "responsive design", "dark mode", "custom colors", "typography")

### Examples

```
/context7:tailwindcss
/context7:tailwindcss responsive design
/context7:tailwindcss custom colors and themes
```

## Instructions for Claude

When this command is executed:

1. **Resolve the Library ID**: Use the `resolve-library-id` tool with library name "tailwindcss" or use the direct Context7 URL https://context7.com/websites/tailwindcss

2. **Fetch Documentation**: Use the `get-library-docs` tool with:
   - The resolved library ID (format: `/websites/tailwindcss`)
   - The topic provided by the user (if any)
   - Token allocation:
     - **With topic**: 5,000-10,000 tokens for focused, specific content
     - **Without topic**: 3,000-5,000 tokens for general Tailwind CSS context

3. **Summarize Context**: After loading, briefly confirm what was loaded (e.g., "Loaded Tailwind CSS documentation for responsive design utilities and breakpoints")

4. **Apply Context**: Use the retrieved documentation to inform your responses and complete the user's original task

## Recommended Use Cases

- Configuring Tailwind CSS (tailwind.config.js customization)
- Working with Tailwind's utility classes
- Implementing responsive designs with Tailwind breakpoints
- Setting up custom colors, fonts, and spacing
- Working with Tailwind plugins (Typography, Forms, etc.)
- Implementing dark mode with Tailwind
- Understanding Tailwind's purge/content configuration
- Optimizing Tailwind builds for production
