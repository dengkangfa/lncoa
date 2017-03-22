import config from './api-config'

let api = {
    //登录
    login: config.host + '/oauth/token',
    //用户信息
    user: config.host + config.prefix + '/user',
    //用户的可以访问的菜单树
    menu: config.host + config.prefix + '/menu',
}

let client = {
    client_id: '2',
    client_secret: 'OkABZOuxDMaiaaFJBrESpYnmIMf6eSwyU42fPVdM'
}

export default {
    api: api,
    client: client
}
