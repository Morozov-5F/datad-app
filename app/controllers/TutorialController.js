const app = angular.module('app');

app.controller('TutorialController', function ($scope) {
    $scope.start = () => {
        window.localStorage.setItem('notFirstStart', 'true');
    };
});