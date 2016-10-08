const app = angular.module('app');

app.controller('ServiceDetailsController', function ($scope, $stateParams) {
    $scope.profile = {
        nickname: 'SomeYouTubeChannel',
        img: 'https://unsplash.it/200/300/?random',
        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sem augue, laoreet ut lorem ut, malesuada ultricies sapien.',
        subscribers: '120k',
        posts: '1k',
        price: 600,
        service: 'Youtube'
    };
});