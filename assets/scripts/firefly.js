import {createApp} from "vue";
import MenuToggle from "./components/menu-toggle.vue";

document.addEventListener('DOMContentLoaded', () => {
  const htmlClasses = document.getElementsByTagName('html')[0].classList;
  createApp(MenuToggle).mount('menu-toggle');
  htmlClasses.remove('no-js');
  htmlClasses.add('js');
});
