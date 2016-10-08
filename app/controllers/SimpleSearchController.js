const app = angular.module('app');

app.controller('SimpleSearchController', function ($scope, $log) {
    $scope.price = 1000;
    $scope.testMessage = 'Hello World';
    $scope.vkChecked = true;
    $scope.youtubeChecked = true;

    $scope.categories = [
        {
            name : "Game"
        },
        {
            name : "Software"
        },
        {
            name : "Food"
        },
        {
            name : "Clothes"
        },
    ];
});