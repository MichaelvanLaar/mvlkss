---
description: Pull both Kirby CMS and Tailwind CSS documentation as additional context using Context7.
---

# Context7: Full Context (Kirby + Tailwind CSS)

Pull both Kirby CMS and Tailwind CSS documentation from Context7 for comprehensive context when working on features that span both systems.

## Usage

```
/context7:full [topic]
```

### Parameters

- `[topic]` (optional): Specific topic to focus the documentation on for both systems (e.g., "styling templates", "responsive design", "configuration")

### Examples

```
/context7:full
/context7:full styling and theming
/context7:full page builder with custom styles
```

## Instructions for Claude

When this command is executed:

1. **Resolve Library IDs**: Use the `resolve-library-id` tool for both:
   - Kirby CMS: "getkirby" (https://context7.com/websites/getkirby)
   - Tailwind CSS: "tailwindcss" (https://context7.com/websites/tailwindcss)

2. **Fetch Documentation**: Use the `get-library-docs` tool for both libraries:
   - Use the resolved library IDs (format: `/websites/getkirby` and `/websites/tailwindcss`)
   - Apply the topic provided by the user (if any) to both requests
   - Token allocation per source:
     - **With topic**: 5,000-7,500 tokens each for focused content (10,000-15,000 total)
     - **Without topic**: 3,000-5,000 tokens each for general context (6,000-10,000 total)

3. **Summarize Context**: After loading both sources, briefly confirm what was loaded (e.g., "Loaded Kirby CMS and Tailwind CSS documentation for template styling and utility classes")

4. **Apply Context**: Use the retrieved documentation from both systems to inform your responses and complete the user's original task

## Recommended Use Cases

- Building Kirby templates with complex Tailwind styling
- Implementing page builders with custom Tailwind components
- Configuring both Kirby and Tailwind for theme customization
- Creating responsive Kirby snippets using Tailwind utilities
- Setting up brand colors that work across both systems
- Troubleshooting integration issues between Kirby and Tailwind
- Developing new features requiring deep knowledge of both frameworks
- Working on styling systems that leverage Kirby's configuration and Tailwind's utilities

## Notes

This command fetches documentation from both sources, which will consume more tokens than individual commands. Use this when you need comprehensive context across both systems, or use `/context7:kirby` or `/context7:tailwindcss` individually for focused assistance.
