const app = angular.module('app');

app.filter('keywords', function ($filter) {
    return (data, text) => {
        if (typeof text != 'string') {
            text = '';
        }
        let keywords = text.split(' ');
        angular.forEach(keywords, keyword => {
            data = $filter('filter')(data, keyword);
        });
        return data;
    };
});