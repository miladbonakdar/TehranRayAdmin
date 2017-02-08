module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            scripts: {
                files: ['./Angular/**/*.js','gruntfile.js'],
                tasks: ['vendor'],
                options: {
                }
            },
            html: {
                files: ['./Angular/**/*.html'],
                tasks: ['templates'],
                options: {
                }
            }
        },
        uglify: {
            angular:{
                options: {
                    beautify: false,
                    mangle: true,
                    banner: '/*\n <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> \n*/\n'
                },files: {
                    'Build/Admin/ng-vendor.min.js': ['angular/admin/min/ng-vendor.js'],
                    'Build/app/ng-vendor.min.js': ['angular/main/min/ng-vendor.js']
                }
            },
            jsfiles:{
                options: {
                    beautify: false,
                    mangle: true,
                    banner: '/*\n <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> \n*/\n'
                },
                files: {
                    'Build/cms/js/page-script.min.js': ['javascript/jQuery-2.1.4.min.js','javascript/jquery-ui.min.js','javascript/bootstrap.min.js'
                        ,'javascript/jquery.slimscroll.min.js','javascript/fastclick.min.js','javascript/app.js','javascript/demo.js'
                        ,'javascript/persian.min.js','javascript/angular.js','javascript/angular-route.min.js'
                        ,'javascript/angular-animate.min.js','javascript/angular-sanitize.min.js','javascript/angular-ui-router.js'
                        ,'javascript/ng-img-crop.js','javascript/ng-fx.min.js'
                        ,'javascript/ADM-dateTimePicker.min.js','javascript/ng-file-upload-shim.min.js','javascript/anim-in-out.js'
                        ,'javascript/ng-file-upload.min.js','javascript/angularpersian.min.js','javascript/treasure-overlay-spinner.js'
                        ,'javascript/ocLazyLoad.min.js','javascript/ui-bootstrap-tpls-1.2.5.min.js'
                        ,'javascript/select.min.js','javascript/textAngular-rangy.min.js','javascript/textAngular-sanitize.min.js'
                        ,'javascript/textAngular.min.js'
                    ]
                }

            }
        },ngAnnotate: {
            options: {
                singleQuotes: true
            },
            app: {
                files: {
                    'angular/admin/min-safe/annontate.js': ['angular/admin/admin-app.js', 'angular/admin/partials/**/*.js'/*, 'angular/admin/directives/*.js' */ ],
                    'angular/main/min-safe/annontate.js': ['angular/main/app.js','angular/main/authCtrl.js', 'angular/main/partials/**/*.js'/*, 'angular/admin/directives/*.js' */ ],
                }
            }
        },
        concat: {
            options: {
                separator:'\n---------------------separator-----------------------\n'
            },
            basic_and_extras: {
                files: {
                    'angular/admin/min/ng-vendor.js': ['angular/admin/min-safe/annontate.js'],
                    'angular/main/min/ng-vendor.js': ['angular/main/min-safe/annontate.js'],
                 },
            },
        },
        ngtemplates:  {
            adminApp: {
                options: {
                    prefix: '',
                    htmlmin: {
                        collapseBooleanAttributes:      true,
                        collapseWhitespace:             true,
                        removeAttributeQuotes:          true,
                        removeComments:                 true, // Only if you don't use comment directives!
                        removeEmptyAttributes:          true,
                        removeRedundantAttributes:      true,
                        removeScriptTypeAttributes:     true,
                        removeStyleLinkTypeAttributes:  true,
                        keepClosingSlash:true
                    },
                    url: function(url) {
                        var filename = url.replace(/^.*[\\\/]/, '')
                        if(url.indexOf("partials") > 0){
                            console.log('angular.partial.' + filename);
                            return 'angular.partial.' + filename;
                        }else{
                            return  url ;
                        }
                    }
                },
                files: {
                    'Build/Admin/templates.min.js': ['angular/admin/**/**.html'],
                },
            },
            myApp: {
                options: {
                    prefix: '',
                    htmlmin: {
                        collapseBooleanAttributes:      true,
                        collapseWhitespace:             true,
                        removeAttributeQuotes:          true,
                        removeComments:                 true, // Only if you don't use comment directives!
                        removeEmptyAttributes:          true,
                        removeRedundantAttributes:      true,
                        removeScriptTypeAttributes:     true,
                        removeStyleLinkTypeAttributes:  true,
                        keepClosingSlash:true
                    },
                    url: function(url) {
                        var filename = url.replace(/^.*[\\\/]/, '')
                        if(url.indexOf("partials") > 0){
                            console.log('angular.partial.' + filename);
                            return 'angular.partial.' + filename;
                        }else{
                            return  url ;
                        }
                    }
                },
                files: {
                    'Build/app/templates.min.js': ['angular/main/**/**.html'],
                },
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'Build/css/page-style.min.css': [
                         'styles/export.css','styles/textAngular.css'
                        ,'styles/ng-img-crop.min.css','styles/anim-in-out.css','styles/select.css','styles/font-awesome-animation.min.css'
                        ,'styles/hover-min.css','styles/bootstrap.min.css','styles/font-awesome.min.css'
                        ,'styles/site-styles.css','styles/pallete.css','styles/AdminLTE-rtl.css'
                        ,'styles/AdminLTE-rtl-fix.css','styles/_all-skins-srtl.css']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-angular-templates');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-ng-annotate');

    grunt.registerTask('default', ['ngAnnotate', 'concat', 'uglify', 'ngtemplates','cssmin','watch']);
    grunt.registerTask('vendor', ['ngAnnotate', 'concat', 'uglify:angular' ]);
    grunt.registerTask('jsmin', ['uglify:jsfiles']);
    grunt.registerTask('templates', ['ngtemplates']);
};