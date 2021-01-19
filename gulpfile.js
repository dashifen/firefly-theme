const {dest, src, task} = require('gulp');
const bump = require('gulp-bump');

const bumper = async function () {
  src(['package.json', 'style.css'])
    .pipe(bump())
    .pipe(dest('.'));
}

task('bump', bumper)
