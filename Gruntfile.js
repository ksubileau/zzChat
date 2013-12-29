'use strict';

module.exports = function (grunt) {

    grunt.initConfig({
        // Configurable paths
        paths: {
            api: 'api',
            app: 'app',
            dist: 'dist',
            build: 'build'
        },

        pkg: grunt.file.readJSON('package.json'),

        meta: {
            license: '<%= _.pluck(pkg.licenses, "type").join(", ") %>',
            licenseUrl: '<%= _.pluck(pkg.licenses, "url").join(", ") %>',
            copyright: 'Copyright (c) 2013-<%= grunt.template.today("yyyy") %>',
            banner:
                '/*!\n' +
                ' * <%= pkg.name %> - <%= pkg.description %>\n' +
                ' * @version <%= pkg.version %>\n' +
                ' *\n' +
                ' * @author <%= pkg.author.name %>\n' +
                ' * @link <%= pkg.homepage %>\n' +
                ' * @copyright <%= meta.copyright %>, <%= pkg.author.name %>\n' +
                ' * @license <%= meta.license %> (<%= meta.licenseUrl %> also in /LICENSE)\n' +
                ' */ \n'
        },

        phpunit: {
            all: {
                dir: 'tests/PHPUnit/'
            },
            options: {
                bin: 'phpunit',
                colors: true,
                coverage: true,
                followOutput: true,
                configuration: 'tests/PHPUnit/phpunit.xml.dist'
            }
        },

        qunit: {
            options: {
                timeout: 15000,
            },
            all: ['tests/javascript/index.html']
        },

        jshint: {
            options: {
                node: true,
                browser: true,
                esnext: true,
                bitwise: true,
                camelcase: true,
                curly: true,
                eqeqeq: true,
                immed: true,
                indent: 4,
                latedef: true,
                newcap: true,
                noarg: true,
                quotmark: 'single',
                regexp: true,
                undef: true,
                unused: true,
                strict: true,
                trailing: true,
                smarttabs: true,
                //maxlen: 80,
                /* Relaxing options */
                expr: true,
                globals: {
                    zzChat: true,
                    require: false,
                    define: false,

                    // QUnit globals
                    asyncTest      : false,
                    deepEqual      : false,
                    equal          : false,
                    expect         : false,
                    module         : false,
                    notDeepEqual   : false,
                    notEqual       : false,
                    notStrictEqual : false,
                    ok             : false,
                    QUnit          : false,
                    raises         : false,
                    start          : false,
                    stop           : false,
                    strictEqual    : false,
                    test           : false
                },
                ignores: [
                    '**/libs/**/*.js',
                    'tests/javascript/testrunner.js'
                ]
            },
            all: [
                'Gruntfile.js',
                '<%= paths.app %>/js/**/*.js',
                'tests/javascript/**/*.js',
            ]
        },

        requirejs: {
            build: {
                options: {
                    name: 'main',
                    out: '<%= paths.build + "/" + paths.app %>/js/zzChat.js',
                    mainConfigFile: '<%= paths.app %>/js/main.js',
                    baseUrl: 'app/js',
                    preserveLicenseComments: false,
                    useStrict: true,
                    waitSeconds: 0,
                    paths: {
                        // Exclude config file
                        'config': 'empty:'
                    }
                }
            }
        },

        less: {
            options: {
                paths: ['<%= paths.app %>/less']
            },
            files: {
                '<%= paths.app %>/css/style.css': '<%= paths.app %>/less/style.less'
            }
        },

        cssmin: {
            build: {
                options: {
                    keepSpecialComments: 0,
                    banner: '<%= meta.banner %>',
                    report: 'min'
                },
                files: {
                    '<%= paths.build + "/" + paths.app %>/css/style.css': ['<%= paths.app %>/css/style.css']
                }
            }
        },

        usebanner: {
            options: {
                position: 'top',
                banner: '<%= meta.banner %>',
                linebreak: true
            },
            files: {
                src: [ '<%= requirejs.build.options.out %>', '<%= paths.build + "/" + paths.app %>/js/config.js' ]
            }
        },

        'regex-replace': {
            build: {
                src: ['<%= paths.build + "/" + paths.app %>/index.html'],
                actions: [{
                    name: 'requirejs-newpath',
                    search: '<script data-main=".*" src=".*/require(.min)?.js"></script>',
                    replace: function (match) {
                        var regex = /src="(.*require(.min)?.js)"/;
                        var result = regex.exec(match);
                        return '<script data-main="js/zzChat.js" src="' + result[1] + '"></script>';
                    },
                    flags: 'g'
                }]
            }
        },

        copy: {
            build: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= paths.app %>',
                    dest: '<%= paths.build + "/" + paths.app %>',
                    src: [
                        '*.{ico,png,css,html}',
                        '.htaccess',
                        'font/**',
                        'img/**',
                        'locales/**',
                        'js/libs/require/require.min.js',
                        'js/config.js',
                    ]
                }]
            }
        },

        compress: {
            app: {
                options: {
                    archive: '<%= paths.dist %>/zzChat-client.zip'
                },
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= paths.build + "/" + paths.app %>',
                    src: ['**']
                },
                {
                    expand: true,
                    dot: true,
                    src: ['README.md', 'LICENSE'],
                }]
            },
            api: {
                options: {
                    archive: '<%= paths.dist %>/zzChat-server.zip'
                },
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= paths.api %>',
                    src: ['**', '!**/Storage/**']
                },
                {
                    expand: true,
                    dot: true,
                    src: ['README.md', 'LICENSE'],
                }]
            },
            all: {
                options: {
                    archive: '<%= paths.dist %>/zzChat.zip'
                },
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= paths.build + "/" + paths.app %>',
                    src: ['**'],
                    dest: '<%= paths.app %>'
                },
                {
                    expand: true,
                    dot: true,
                    cwd: '<%= paths.api %>',
                    src: ['**', '!**/Storage/**'],
                    dest: '<%= paths.api %>'
                },
                {
                    expand: true,
                    dot: true,
                    src: ['README.md', 'LICENSE'],
                }]
            }
        },

        clean: {
            dist: ['.tmp', '<%= paths.dist %>/*'],
            build: ['.tmp', '<%= paths.build %>/*'],
        }

    });

    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

    grunt.registerTask('tests', [
        'phpunit',
        'qunit'
    ]);

    grunt.registerTask('build', [
        'clean:build',
        //'jshint',
        'tests',
        'less',
        'cssmin',
        'requirejs',
        'copy',
        'regex-replace:build',
        'usebanner'
    ]);

    grunt.registerTask('dist', [
        'clean:dist',
        'build',
        'compress'
    ]);

    grunt.registerTask('default', 'build');
};