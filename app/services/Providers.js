const app = angular.module('app');

app.factory('Providers', function ($http, $q, Users) {
    const serviceIDs = {
        'youtube': 1,
        'vk': 2,
        'instagram': 3
    }

    let get = () => {
        let token = Users.getAccessToken();
        
        return $http({
            url: 'http://nk5.ru/api/providers.get',
            method: 'GET',
            headers: {
                'X-Access-Token': token
            },
            params: { }
        });
    };

    let search = (params) => {
        let token = Users.getAccessToken();
        if (!angular.isArray(params.services)) {
            params.services = [];
        }
        params.categoryID = params.category;
        delete params.category;
        let servicesArray = [];
        angular.forEach(params.services, (v) => {
            servicesArray.push(serviceIDs[v]);
        }); 
        servicesArray.sort();
        delete params.services;
        params.socialID = servicesArray.join(',');

        return $http({
            url: 'http://nk5.ru/api/providers.search',
            method: 'GET',
            headers: {
                'X-Access-Token': token
            },
            params
        });        
    };

    let getByID = (id) => {
        let token = Users.getAccessToken();

        return $http({
            url: 'http://nk5.ru/api/providers.getByID',
            method: 'GET',
            headers: {
                'X-Access-Token': token
            },
            params: { ids: id }
        });          
    };

    return {
        get,
        search,
        getByID
    };
});