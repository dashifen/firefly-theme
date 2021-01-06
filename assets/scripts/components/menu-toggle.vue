<template>
  <button
      id="toggler"
      type="button"
      title="Toggle Main Menu Display"
      aria-controls="main-menu"
      :class="{ open: open }"
      @click.prevent="open = !open"
  >
      <span class="toggle">
        <span class="mid-line"></span>
      </span>
  </button>
</template>

<script>
// noinspection JSUnusedGlobalSymbols

export default {
  name: "menu-toggle",

  data() {
    return {
      'open': false
    };
  }
};
</script>

<style scoped>

</style>
