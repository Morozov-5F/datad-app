const app = angular.module('app');

app.controller('SearchResultsController', function ($scope, $stateParams, Services, Users, Providers) {
    let searchParams = {};
    try {
        searchParams = JSON.parse($stateParams.fields);
    } catch (e) {
        console.log(e);
    }

    const serviceNames = [
        'youtube',
        'vk',
        'instagram'
    ];
    Providers.get(searchParams)
        .then((result) => $scope.searchResults = result.data.users)
        .finally(() => $scope.loading = false);

    let randomPrice = () => (Math.floor(Math.random() * 120) + 25) * 10;
    $scope.searchResults = [];
    $scope.loading = true;
    $scope.getServiceName = (socialID) => serviceNames[socialID - 1]; 
    $scope.getServiceIconPath = (socialID, size) => 'assets/img/service_icons/' + $scope.getServiceName(socialID) + '-colored.png';
});