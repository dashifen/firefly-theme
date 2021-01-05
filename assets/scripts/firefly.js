document.addEventListener('DOMContentLoaded', () => {
  const toggler = document.getElementById('toggler');
  const htmlClasses = document.getElementsByTagName('html')[0].classList;
  const firstMenuItem = document.querySelector("#main-menu .menu-item:first-child");

  htmlClasses.add('js');
  htmlClasses.remove('no-js');
  toggler.addEventListener('click', (event) => {
    event.preventDefault();
    event.stopPropagation();
    toggler.title = toggler.title === "Show Menu" ? "Hide Menu" : "Show Menu";
    toggler.classList.toggle('open');
  })
});
