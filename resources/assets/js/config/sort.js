export default [
    {
        label: 'sidebar.dashboard',
        icon : 'ion-ios-speedometer',
        menus : [
            {
              label: 'sidebar.category',
              icon : 'ion-ios-list',
              uri  : '/categories'
            },
            {
              label: 'sidebar.visitor',
              icon : 'ion-chatbubble-working',
              uri  : '/visitors'
            }
        ]
    },
    {
        label: 'sidebar.user',
        icon : 'ion-person-stalker',
        menus : [
            {
              label: 'sidebar.file',
              icon : 'ion-ios-folder',
              uri  : '/files'
            },
            {
              label: 'sidebar.tag',
              icon : 'ion-ios-pricetags',
              uri  : '/tags'
            }
        ]
    }
]
