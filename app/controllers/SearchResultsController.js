const app = angular.module('app');

app.controller('SearchResultsController', function ($scope, $stateParams, Services, Users, Providers, $ionicPopup) {
    let searchParams = {};
    try {
        searchParams = JSON.parse($stateParams.fields);
    } catch (e) {
        
    }
    searchParams.limit = 50;
    const serviceNames = [
        'youtube',
        'vk',
        'instagram'
    ];
    $scope.random = function() {
        return 0.5 - Math.random();
    }
    $scope.searchTerms = '';
    $scope.orderField = 'price';
    $scope.searchResults = [];
    $scope.totalResultsCount = 0;
    Providers.search(searchParams)
        .then(result => {
            $scope.searchResults = [];
            angular.forEach(result.data.users, user => {
                user.price = parseInt(user.price);
                user.subscribers = parseInt(user.subscribers);
                $scope.searchResults.push(user);
            });; 
        })
        .catch(error => $ionicPopup.alert({
            title: 'Connection error',
            template: 'Failed to connect to the server'
        }))
        .finally(() => $scope.loading = false);


    $scope.$watch('searchResults', () => {
        $scope.totalResultsCount = $scope.searchResults.length;
    });

    let randomPrice = () => (Math.floor(Math.random() * 120) + 25) * 10;
    $scope.loading = true;
    $scope.getServiceName = (socialID) => serviceNames[socialID - 1]; 
    $scope.getServiceIconPath = (socialID, size) => 'assets/img/service_icons/' + $scope.getServiceName(socialID) + '-colored.png';

    $scope.cropValue = (val) => {
        let res = '';
        if (val >= 1e+6)
        {
            res = Math.round(Math.floor(val / 1e+5)) / 10 + 'M';
        }
        else if (val >= 1e+3)
        {
            res = Math.round(val / 1e+2) / 10 + 'k';
        }
        else
        {
            res = Math.floor(val);
        }
        return res;
    };
});