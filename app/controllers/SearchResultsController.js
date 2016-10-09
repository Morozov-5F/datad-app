const app = angular.module('app');

app.controller('SearchResultsController', function ($scope, $stateParams, Services, Users, Providers) {
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
        .then((result) => $scope.searchResults = result.data.users)
        .catch((error) => console.log(error))
        .finally(() => $scope.loading = false);


    $scope.$watch('searchResults', () => {
        $scope.totalResultsCount = $scope.searchResults.length;
        console.log($scope.searchResults.length, $scope.totalResultsCount);
    });

    let randomPrice = () => (Math.floor(Math.random() * 120) + 25) * 10;
    $scope.loading = true;
    $scope.getServiceName = (socialID) => serviceNames[socialID - 1]; 
    $scope.getServiceIconPath = (socialID, size) => 'assets/img/service_icons/' + $scope.getServiceName(socialID) + '-colored.png';
});