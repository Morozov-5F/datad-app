const app = angular.module('app');

app.controller('SimpleSearchController', function ($scope, $log, Categories) {
    Categories.get()
        .then((result) => {            
            $scope.categories = result.data.categories;
        });    
    $scope.categories = [];
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
        category: '1'
    };    
    $scope.$watch('services', () => {
        $scope.searchFields.services = [];
        angular.forEach($scope.services, (value, key) => {
            if (value.checked) {
                $scope.searchFields.services.push(value.name);
            }
        });
    }, true);
    $scope.getFieldsString = () => {
        return JSON.stringify($scope.searchFields);
    };

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