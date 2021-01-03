document.addEventListener('DOMContentLoaded', () => {
  const htmlClasses = document.getElementsByTagName('html')[0].classList;
  htmlClasses.remove('no-js');
  htmlClasses.add('js');

  const toggler = document.getElementById('toggler');
  toggler.addEventListener('click', (event) => {
    event.preventDefault();
    event.stopPropagation();
    toggler.classList.toggle('open');
  })
});
