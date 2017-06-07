/* globals angular */

(function (angular) {
  angular.module('taDocuments', ['civitasks.appDocuments']).controller('ModalController', ['$scope', '$rootScope', '$window', '$rootElement', '$log', '$uibModal', 'DocumentService', 'FileService', 'config',
    function ($scope, $rootScope, $window, $rootElement, $log, $modal, DocumentService, FileService, config) {
      var vm = {};

      /**
       * Gets Document for the given document id and
       * opens Modal with retrived document data
       *
       * @param {integer} id
       * @param {string} role
       */
      vm.modalDocument = function (id, role) {
        DocumentService.get({ id: id })
          .then(function (data) {
             if (!data) {
               throw new Error('Requested Document is not available');
             }

             openModalDocument(data[0], role);
          })
          .catch(function (reason) {
            CRM.alert(reason, 'Error', 'error');
          });
      };

      (function init () {
        // Reloads page on 'document-saved' event
        $rootScope.$on('document-saved', function () {
          $window.location.reload();
        });
      })();

      /**
       * Opens Document Modal
       *
       * @param {object} data
       * @param {string} role
       */
      function openModalDocument (data, role) {
        var modalInstance = $modal.open({
          appendTo: $rootElement.find('div').eq(0),
          templateUrl: config.path.TPL + 'modal/document.html?v=3',
          controller: 'ModalDocumentCtrl',
          resolve: {
            role: function () {
              return role;
            },
            data: function () {
              return data;
            },
            files: function () {
              if (!data.id || !+data.file_count) {
                return [];
              }

              return FileService.get(data.id, 'civicrm_activity');
            }
          }
        });
      }

      return vm;
    }
  ]);
})(angular);