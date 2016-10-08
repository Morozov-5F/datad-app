const app = angular.module('app');

app.controller('SimpleSearchController', function ($scope, $log, Users) {
    // Users.login('bredikhin.nikita@sas.pisas', '1234567')
        
    //     .then((result) => {
    //         console.log('Login success');

    //         return Users.get();
    //     })

    //     .then((result) => {
    //         console.log(result);
    //     });
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
            name: "vk",
            checked: true,
        },
        {
            name: "youtube",
            checked: true,
        },
        {
            name: "instagram",
            checked: false,
        }        
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
    $scope.getServiceIconClass = (service) => {
        let classes = ['search-service-icon'];
        if (service.checked)
            classes.push('search-service-icon-checked')
        else
            classes.push('search-service-icon-unchecked')

        return classes;
    };
    $scope.toggleServiceIcon = (index) => {
        $scope.services[index].checked = !$scope.services[index].checked;
    };
});