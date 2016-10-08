const app = angular.module('app');

app.controller('SimpleSearchController', function ($scope, $log) {
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
    $scope.services =  [
        {
            name: "VK",
            checked: true,
        },
        {
            name: "YouTube",
            checked: true,
        },
    ];
    $scope.searchFields = {
        price: 1000,
        services: [],
        category: $scope.categories[0].name
    };
    $scope.$watch('services', () => {
        $scope.searchFields.services = [];
        angular.forEach($scope.services, (value, key) => {
            if (value.checked) {
                $scope.searchFields.services.push(value.name);
            }
        });
    }, true);
    $scope.getFieldsString = () => JSON.stringify($scope.searchFields);
});