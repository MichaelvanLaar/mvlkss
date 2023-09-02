/**
 * =============================================================================
 * Block: MvLKSS Button
 * =============================================================================
 */

panel.plugin("mvlkss/button-block", {
  blocks: {
    mvlkssbutton: `
      <div :class="\`button-align-\${content.align}\`">
        <button type="button" @click="open" :class="\`button-width-\${content.width}\`">
          {{ content.text }}
        </button>
      </div>
    `,
  },
});
