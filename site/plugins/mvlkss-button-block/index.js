/**
 * =============================================================================
 * Block: MvLKSS Button
 * =============================================================================
 */

panel.plugin("mvlkss/button-block", {
  blocks: {
    mvlkssbutton: `
      <button type="button" @click="open">
        {{ content.text }}
      </button>
    `,
  },
});
