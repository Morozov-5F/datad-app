const app = angular.module('app');

app.controller('SearchResultsController', function ($scope, $stateParams) {
    console.log(JSON.parse($stateParams.fields));
    let randomPrice = () => (Math.floor(Math.random() * 75) + 25) * 1000;
    $scope.searchResults = [
        {
            id: 1,
            name: 'Test Dunno Name',
            service: 'youtube',
            price: randomPrice(),
            description: 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit'
        },
        {
            id: 2,
            name: 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit',
            service: 'youtube',
            price: randomPrice(),
            description: 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit'
        },
        {
            id: 3,
            name: 'Name',
            service: 'instagram',
            price: randomPrice(),
            description: 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit'
        },
        {
            id: 4,
            name: 'Da',
            service: 'vk',
            price: randomPrice(),
            description: 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit'
        }                          
    ];

    $scope.getServiceIconPath = (service, size) => 'assets/img/service_icons/' + service + '-64.png';
});