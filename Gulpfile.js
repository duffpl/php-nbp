var gulp = require('gulp');
var phpunit = require('gulp-phpunit');
var run = require('gulp-run');
var notify = require('gulp-notify');
var _ = require('lodash');

gulp.task('test', function() {
    gulp.src('phpunit.xml.dist')
        .pipe(phpunit('./vendor/bin/phpunit', {notify: true}))
        .on('error', notify.onError(testNotification('fail', 'phpunit')))
        .pipe(notify(testNotification('pass', 'phpunit')));
});

gulp.task('watch', function() {
    gulp.watch(['tests/**/*Test.php', 'src/**/*.php'], ['test']);
});

gulp.task('default', ['test', 'watch']);

function testNotification(status, pluginName, override) {
    var options = {
        title:   ( status == 'pass' ) ? 'Tests Passed' : 'Tests Failed',
        message: ( status == 'pass' ) ? '\n\nAll tests have passed!\n\n' : '\n\nOne or more tests failed...\n\n',
        icon:    __dirname + '/node_modules/gulp-' + pluginName +'/assets/test-' + status + '.png'
    };
    options = _.merge(options, override);
    return options;
}