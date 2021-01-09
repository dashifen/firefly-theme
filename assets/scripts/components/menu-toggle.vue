<template>
  <button
      id="toggler"
      type="button"
      title="Toggle Main Menu Display"
      aria-controls="main-menu"
      :class="{ open: open }"
      @click="clicked"
  >
      <span class="toggle">
        <span class="mid-line"></span>
      </span>
  </button>
</template>

<script>
// noinspection JSUnusedGlobalSymbols

const body = document.getElementsByTagName('body')[0];

export default {
  name: "menu-toggle",

  data() {
    return {
      'open': false
    };
  },

  methods: {
    clicked() {
      this.open = !this.open
      if (!this.open) {
        body.classList.remove('menu-open');
      } else {
        body.classList.add('menu-open');
      }
    }
  }
};
</script>

<style scoped>

</style>
