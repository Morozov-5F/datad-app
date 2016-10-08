var app = angular.module('app', ['ionic']);

app.run(function($ionicPlatform) {
    $ionicPlatform.ready(function() {
        if (window.cordova && window.cordova.plugins.Keyboard) {
            cordova.plugins.Keyboard.hideKeyboardAccessoryBar(false);
            cordova.plugins.Keyboard.disableScroll(true);
        }
        if (window.StatusBar) {
            StatusBar.styleDefault();
        }
    });
});

app.config(function($httpProvider, $stateProvider, $urlRouterProvider) {
    $httpProvider.defaults.headers = { 
        'Content-Type': undefined 
    };

    $stateProvider
        .state('menu.simpleSearch', {
            url: '/search',
            views: {
                'side-menu21': {
                    templateUrl: 'templates/simpleSearch.html',
                    controller: 'SimpleSearchController'
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
            url: '/search_results?fields',
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