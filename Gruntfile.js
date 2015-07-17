'use strict';

module.exports = function (grunt) {

  var config = grunt.file.readJSON('config.json');
  var configBranch = {
      "name": ''
  };

  // Define the configuration for all the tasks
  grunt.initConfig({

    // deploy
    config: config,
    configBranch: configBranch,

    sshconfig: {
        dev: config
    },
    sshexec: {
        test: {
            command: 'uptime',
            options: {
                config: 'dev'
            }
        },
        deploy: {
            command: [
                'echo Deploying <%= configBranch.name %>',
                'sh /www/demoreal.focusonemotions.com/deploy-code.sh <%= configBranch.name %>'
            ],
            options: {
                config: 'dev'
            }
        },
        status: {
            command: 'git branch | grep \\*',
            options: {
                config: 'dev'
            }
        }
    }
  });
  grunt.loadNpmTasks('grunt-ssh');

  grunt.registerTask('deploy', function(branch){
      configBranch.name = branch;
      grunt.task.run('sshexec:deploy');
  });

};
