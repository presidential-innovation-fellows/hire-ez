module.exports = function(grunt) {

  var tasks = 'stylus jader coffee concat cssmin min';

  var path = require('path');
  var exec = require('child_process').exec;

  grunt.loadNpmTasks('grunt-css');
  grunt.loadNpmTasks('grunt-coffee');
  grunt.loadNpmTasks('grunt-contrib-stylus');

  grunt.registerTask('jader', 'Compiles jade templates to PHP.', function() {
    var cb = this.async();

    var child = exec('php ../../artisan jader', function (error, stdout, stderr) {
      console.log(error ? error : "Done");
      cb();
    });
  });


  grunt.initConfig({

    pkg: '<json:package.json>',

    coffee: {
      backbone_collections: {
        src: ['../coffee/backbone/collections/*.coffee'],
        dest: '../js/backbone/collections',
        options: {bare: true}
      },
      backbone_models: {
        src: ['../coffee/backbone/models/*.coffee'],
        dest: '../js/backbone/models',
        options: {bare: true}
      },
      backbone_routers: {
        src: ['../coffee/backbone/routers/*.coffee'],
        dest: '../js/backbone/routers',
        options: {bare: true}
      },
      backbone_views: {
        src: ['../coffee/backbone/views/*.coffee'],
        dest: '../js/backbone/views',
        options: {bare: true}
      },
      all: {
        src: ['../coffee/*.coffee'],
        dest: '../js',
        options: {bare: true}
      },
    },

    stylus: {
      compile: {
        files: {
          '../css/compiled_styl.css': ['../styl/main.styl']
        }
      }
    },


    concat: {
      css: {
        src: [
          '../css/bootstrap.css',
          '../css/bootstrap-responsive.css',
          '../css/bootstrap-wysihtml5.css',
          '../css/datepicker.css',
          '../css/compiled_styl.css'
        ],
        dest: '../../public/css/all.css'
      },

      js_global: {
        src: [
          // vendor files
          '../js/vendor/bootstrap.js',
          '../js/vendor/jquery.validate.js',
          '../js/vendor/jquery.validate_rfpez.js',
          '../js/vendor/jquery.timeago.js',
          '../js/vendor/jquery.placeholder.js',
          '../js/vendor/jquery.form.js',
          '../js/vendor/underscore.js',
          '../js/vendor/backbone.js',
          '../js/vendor/bootstrap-datepicker.js',
          '../js/vendor/wysihtml5.min.js',
          '../js/vendor/bootstrap-wysihtml5.js',
          '../js/vendor/jquery.sortable.js',
          '../js/vendor/autogrow-input.js',
          '../js/vendor/keymaster.min.js',

          // global scripts
          '../js/flash-button.js',
          '../js/prototype-hacks.js',

          // has to be loaded first
          '../js/main.js',

          // backbone
          '../js/backbone/models/*.js',
          '../js/backbone/collections/*.js',
          '../js/backbone/views/*.js',

          // now comes everything eles
          '../js/*.js'
        ],
        dest: '../../public/js/global.js'
      }

    },

    cssmin: {
      all: {
        src: ['../../public/css/all.css'],
        dest: '../../public/css/all.min.css'
      }
    },

    min: {
      js_global: {
        src: ['../../public/js/global.js'],
        dest: '../../public/js/global.min.js'
      },
    },


    watch: {
      app: {
        files: ['../coffee/**/*.coffee', '../styl/**/*.styl', '../css/**/*.css', '../js/**/*.js', '../../application/views/**/**/*.jade'],
        tasks: tasks
      }
    }
  });

  grunt.registerTask('default', tasks);

};
