var app = angular.module('app', ['ionic']);

app.run(function ($ionicPlatform) {
    $ionicPlatform.ready(function () {
        if (window.cordova && window.cordova.plugins.Keyboard) {
            cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
            cordova.plugins.Keyboard.disableScroll(true);
        }
        if (window.StatusBar) {
            StatusBar.styleDefault();
        }
    });
});

app.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider
    
      .state('menu.simpleSearch', {
    url: '/search',
    views: {
      'side-menu21': {
        templateUrl: 'templates/simpleSearch.html',
        // controller: 'simpleSearchCtrl'
      }
    }
  })

  .state('menu.advancedSearch', {
    url: '/adv_serach',
    views: {
      'side-menu21': {
        templateUrl: 'templates/advancedSearch.html',
        // controller: 'advancedSearchCtrl'
      }
    }
  })

  .state('menu.profile', {
    url: '/profile',
    views: {
      'side-menu21': {
        templateUrl: 'templates/profile.html',
        // controller: 'profileCtrl'
      }
    }
  })

  .state('menu', {
    url: '/side-menu',
    templateUrl: 'templates/menu.html',
    // controller: 'menuCtrl'
  })

  .state('menu.searchResults', {
    url: '/search_results',
    views: {
      'side-menu21': {
        templateUrl: 'templates/searchResults.html',
        controller: 'SearchResultsController'
      }
    }
  })

  .state('menu.serviceDetails', {
    url: '/details?id',
    views: {
      'side-menu21': {
        templateUrl: 'templates/serviceDetails.html',
        controller: 'ServiceDetailsController'
      }
    }
  })

$urlRouterProvider.otherwise('/side-menu/search')

  

});