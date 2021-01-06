import {createApp} from "vue";
import MenuToggle from "./components/menu-toggle.vue";

document.addEventListener('DOMContentLoaded', () => {
  const htmlClasses = document.getElementsByTagName('html')[0].classList;
  htmlClasses.remove('no-js');
  htmlClasses.add('js');

  // the following are the initializations of our Vue components.

  createApp(MenuToggle).mount("#toggle");
});
