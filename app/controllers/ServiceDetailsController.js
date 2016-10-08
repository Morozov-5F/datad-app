const app = angular.module('app');

app.controller('ServiceDetailsController', function ($scope, $stateParams) {
    console.log($stateParams.id)
});