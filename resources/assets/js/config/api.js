import config from './api-config'

let api = {
    //登录
    login: config.host + '/oauth/token',
    //用户信息
    user: config.host + config.prefix + '/user',
    //用户的可以访问的菜单树
    tree: config.host + config.prefix + '/tree',
}

let client = {
    client_id: '2',
    client_secret: 'whEHsJxz48FWlCmvd1U8SfNhuz9cvlY8jdR9KxP1'
}

export default {
    api: api,
    client: client
}
